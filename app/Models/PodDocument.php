<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Log;

class PodDocument extends Model
{
    use Prunable;

    protected $fillable = [
        'filename',
        'file_path',
        'file_modified_at',
        'content',
    ];


    public function getSnippet(string $query, int $radius = 120): string
    {
        $text = $this->content ?? '';

        if ($text === '') {
            return '';
        }

        // Split search query into unique terms (ignoring 1-char symbols)
        $terms = array_filter(
            array_unique(preg_split('/[\s\+\-\*"\(\)<>~]+/', trim($query))),
            fn ($term) => mb_strlen($term) >= 2
        );

        if (empty($terms)) {
            return e(mb_strimwidth($text, 0, $radius * 2, '...'));
        }

        // Build regex matching any of the search terms (case-insensitive)
        $escapedTerms = array_map(fn ($t) => preg_quote($t, '/'), $terms);
        $pattern = '/(' . implode('|', $escapedTerms) . ')/iu';

        // Find the first match position so we center the snippet around it
        $startPos = 0;
        if (preg_match($pattern, $text, $match, PREG_OFFSET_CAPTURE)) {
            // Convert byte offset to multibyte character offset
            $charOffset = mb_strlen(substr($text, 0, $match[0][1]));
            $startPos = max(0, $charOffset - $radius);
        }

        $excerpt = mb_substr($text, $startPos, ($radius * 2) + 40);

        // Escape raw OCR text FIRST to prevent XSS before adding <mark> tags
        $safeExcerpt = e($excerpt);

        // Highlight matched terms
        $highlighted = preg_replace(
            $pattern,
            '<mark class="bg-warning-subtle fw-semibold px-1 rounded">$1</mark>',
            $safeExcerpt
        );

        $prefix = $startPos > 0 ? '...' : '';
        $suffix = ($startPos + mb_strlen($excerpt)) < mb_strlen($text) ? '...' : '';

        return $prefix . $highlighted . $suffix;
    }
    

    /**
     * Get the prunable model query (records older than 1 year).
     */
    public function prunable(): Builder
    {
        // Use your preferred date column: 'created_at', 'indexed_at', or a parsed document date
        return static::where('created_at', '<=', now()->subYear());
    }

    /**
     * Delete the physical file on the mounted SMB share before deleting the DB row.
     */
    protected function pruning(): void
    {
        $mountPath = '/mnt/windowspod';
        $filePath = $mountPath . '/' . ltrim($this->filename, '/');

        // Safety check: Ensure the SMB mount is actually connected
        if (!is_dir($mountPath) || !is_writable($mountPath)) {
            Log::error("SMB mount unavailable or not writable during prune for: {$filePath}");
            throw new \RuntimeException("Mount point {$mountPath} is unavailable. Aborting prune.");
        }

        if (file_exists($filePath)) {
            if (!@unlink($filePath)) {
                Log::error("Failed to delete physical PDF on SMB share: {$filePath}");
                // Throw an exception to stop from deleting the DB row if the file couldn't be removed
                throw new \RuntimeException("Could not delete file: {$filePath}");
            }
        }
    }

}
