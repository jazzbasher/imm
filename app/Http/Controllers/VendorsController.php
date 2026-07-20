<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use App\Models\Media;

class VendorsController extends Controller
{
    public function index()
    {
        return view('vendors.index');
    }


    public function showForm()
    {
        return view('vendors.pricelist.pricelistform');
    }

    public function storeFile(Request $request)
    {
        // 1. Validate the file (e.g., max 2MB, must be pdf, png, or jpg)
        $request->validate([
            'document' => 'required|file|mimes:pdf,png,jpg|min:10|max:22048',
        ]);

        // 2. Store the file in the "uploads" folder on the "public" disk
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents/pricelist/lennox', 'public');

// working on the below
            Media::create([
                'file_path' => $path,
                'original_name' => $request->file('document')->getClientOriginalName(),
                'mime_type' => $request->file('document')->getMimeType(),
                'file_size' => $request->file('document')->getSize(),
            ]);



            return back()->with('success', "File uploaded successfully to: {$path}");
        }

        return back()->with('error', 'No file was selected.');
    }



    public function lennoxpricelist()
    {
        $pricelist = Media::where('file_path', 'LIKE', 'documents/pricelist/lennox/%')->latest()->first();

        return view('vendors.pricelist.vendors', compact('pricelist'));

    }



}
