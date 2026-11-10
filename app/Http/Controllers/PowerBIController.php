<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorSalesHistory;

class PowerBIController extends Controller
{
    public function itemsaleshistory($itemid, $custid)
    {
        $itemsales = EpicorSalesHistory::where('customer_id', $custid)->where('item_id', $itemid)->selectRaw('COUNT(*) as invoicecnt, SUM(qty_shipped) as qtyshipped, SUM(extended_price) as extendedprice')->first();



        return view('powerbi.itemsaleshistory', compact('itemid', 'custid', 'itemsales'));
    }
}
