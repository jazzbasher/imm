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
            // Prepare boolean wildcard query (e.g. "INV 1045" -> "+INV* +1045*")
            $terms = preg_split('/\s+/', $query);
            $booleanQuery = collect($terms)
                ->map(fn($term) => '+' . preg_replace('/[+\-><\(\)~*\"@]+/', '', $term) . '*')
                ->filter(fn($term) => strlen($term) > 2)
                ->implode(' ');

            $results = PodDocument::query()
                ->select(['id', 'filename', 'content', 'updated_at'])
                ->whereRaw("MATCH(filename, content) AGAINST(? IN BOOLEAN MODE)", [$booleanQuery])
                ->paginate(25)
                ->withQueryString();
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
