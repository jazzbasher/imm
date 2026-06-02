<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorsController extends Controller
{
    public function index()
    {
        return view('vendors.index');
    }
}
