<?php

namespace App\Http\Controllers;

use App\Models\PodDocument;
use Illuminate\Http\Request;

class PodSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));
        $results = collect();

        if ($query !== '') {
            $terms = preg_split('/\s+/', $query);
            
            // Separate normal words (3+ alphanumeric chars) from dates/symbols/short codes
            $ftTerms = [];
            $likeTerms = [];

            foreach ($terms as $term) {
                $clean = preg_replace('/[+\-><\(\)~*\"@]+/', '', $term);
                if ($clean === '') {
                    continue;
                }

                // If the term contains a slash/dash or is under 3 chars, use LIKE
                if (preg_match('/[\/\-]/', $clean) || strlen($clean) < 3) {
                    $likeTerms[] = $clean;
                } else {
                    $ftTerms[] = '+' . $clean . '*';
                }
            }

            $builder = PodDocument::query()
                ->select(['id', 'filename', 'content', 'created_at', 'updated_at']);

            //  Run ultra-fast FULLTEXT index on standard words/numbers (3+ chars)
            if (!empty($ftTerms)) {
                $booleanQuery = implode(' ', $ftTerms);
                $builder->whereRaw("MATCH(filename, content) AGAINST(? IN BOOLEAN MODE)", [$booleanQuery]);
            }

            //  Run exact substring LIKE match for dates and short codes
            foreach ($likeTerms as $likeTerm) {
                $builder->where(function ($q) use ($likeTerm) {
                    $q->where('content', 'LIKE', '%' . $likeTerm . '%')
                      ->orWhere('filename', 'LIKE', '%' . $likeTerm . '%');
                });
            }

            $results = $builder->orderByDesc('created_at')->paginate(25)->withQueryString();
        }

        return view('pod.search', compact('results', 'query'));
    }

    public function show(PodDocument $document)
    {
        abort_unless(file_exists($document->file_path), 404, 'PDF file not found on share.');

        // Streams the PDF directly into the browser tab
        return response()->file($document->file_path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document->filename . '"',
        ]);
    }



}
