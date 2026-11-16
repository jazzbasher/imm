<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorSalesHistory;

class PowerBIController extends Controller
{
    public function itemsaleshistory($itemid, $custid)
    {
        // $itemsales = EpicorSalesHistory::where('customer_id', $custid)->where('item_id', $itemid)->selectRaw('COUNT(*) as invoicecnt, SUM(qty_shipped) as qtyshipped, SUM(extended_price) as extendedprice, MIN(CAST(invoice_date as date)) as mindate, MAX(CAST(invoice_date as date)) as maxdate')->first();


        $itemsales = EpicorSalesHistory::select('customer_name', 'customer_id', 'invoice_no', 'invoice_date', 'item_id', 'qty_shipped', 'unit_of_measure', 'unit_price', 'extended_price')->where('customer_id', $custid)->where('item_id', $itemid)->where('invoice_date', '>=', '2026-01-01 00:00:01')->orderBy('invoice_date', 'asc')->get();

        $firstitem = $itemsales->first();
        $totalqty = $itemsales->sum('qty_shipped');
        $totalprice = $itemsales->sum('extended_price');


        return view('powerbi.itemsaleshistory', compact('itemid', 'custid', 'itemsales', 'firstitem', 'totalqty', 'totalprice'));
    }
}
