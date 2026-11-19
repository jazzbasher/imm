<?php

namespace App\Console\Commands;

use App\Models\PodDocument;
use Illuminate\Console\Command;
use Spatie\PdfToText\Pdf;
use DirectoryIterator;
use Illuminate\Support\Facades\Storage;

class IndexPodPdfs extends Command
{
    protected $signature = 'pod:index';
    protected $description = 'Scan /mnt/windowspod and index new or updated PDFs into MySQL';

    public function handle(): int
    {
        $binaryPath = config('services.poppler.pdftotext_path');
        $directory = '/mnt/windowspod';
        // $directory = Storage::path('documents\pod');

        if (!is_dir($directory)) {
            $this->error("Directory {$directory} is not mounted or accessible.");
            return self::FAILURE;
        }

        // Load existing filenames and timestamps into memory for fast lookup
        $existing = PodDocument::pluck('file_modified_at', 'filename')->toArray();

        $indexedCount = 0;
        $iterator = new DirectoryIterator($directory);

        foreach ($iterator as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }

            if (strtolower($file->getExtension()) !== 'pdf') {
                continue;
            }

            $filename = $file->getFilename();
            $mtime = $file->getMTime();

            // Skip if already indexed and file hasn't changed
            if (isset($existing[$filename]) && $existing[$filename] == $mtime) {
                continue;
            }

            $fullPath = $file->getPathname();

            try {
                $rawText = Pdf::getText($fullPath, $binaryPath);
                // Collapse extra spaces/newlines for cleaner indexing
                $cleanText = preg_replace('/\s+/', ' ', trim($rawText));

                PodDocument::updateOrCreate(
                    ['filename' => $filename],
                    [
                        'file_path' => $fullPath,
                        'file_modified_at' => $mtime,
                        'content' => $cleanText,
                    ]
                );

                $indexedCount++;

                if ($indexedCount % 500 === 0) {
                    $this->info("Indexed {$indexedCount} PDFs so far...");
                }
            } catch (\Throwable $e) {
                $this->warn("Failed to read {$filename}: " . $e->getMessage());
            }
        }

        $this->info("Done! Newly indexed/updated PDFs: {$indexedCount}");
        return self::SUCCESS;
    }
}
