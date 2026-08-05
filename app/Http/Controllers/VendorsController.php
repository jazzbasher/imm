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
        
        // $request->validate([
        //     'document' => 'required|file|mimes:pdf,png,jpg|min:10|max:22048',
        // ]);

        
        // if ($request->hasFile('document')) {
        //     $lenox = $request->file('document')->store('documents/pricelist/lenox', 'public');


        //     Media::create([
        //         'identifier' => 'lenox.pricelist.bandsaw',
        //         'file_path' => $path,
        //         'original_name' => $request->file('document')->getClientOriginalName(),
        //         'mime_type' => $request->file('document')->getMimeType(),
        //         'file_size' => $request->file('document')->getSize(),
        //     ]);


        $request->validate([
            'document' => 'required|file|mimes:pdf,png,jpg,xls,xlsx,csv,docx,txt|min:10|max:22048',
        ]);

        
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents/warehouse', 'public');


            Media::create([
                'identifier' => 'warehouse.vehiclemaintenance',
                'file_path' => $path,
                'original_name' => $request->file('document')->getClientOriginalName(),
                'mime_type' => $request->file('document')->getMimeType(),
                'file_size' => $request->file('document')->getSize(),
            ]);



            return back()->with('success', "File uploaded successfully to: {$path}");
        }

        return back()->with('error', 'No file was selected.');
    }



    public function lenoxpricelist()
    {
        $lenoxbandsaw = Media::where('identifier', 'lenox.pricelist.bandsaw')->latest()->first();


        return view('vendors.pricelist.vendors', compact('lenoxbandsaw'));

    }



}
