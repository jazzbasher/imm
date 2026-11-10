<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PowerBIController extends Controller
{
    public function itemsaleshistory($itemid, $custid)
    {
        return view('powerbi.itemsaleshistory', compact('itemid', 'custid'));
    }
}
