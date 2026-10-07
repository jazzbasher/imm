<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesHalt;
use App\Models\EpicorViewInvoiceLine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesHaltController extends Controller
{
    public function index()
    {
        $saleshalts = SalesHalt::where('times_sold_cnt', '>=', 6)->where('date_last_sold', '<=', '2026-06-01')->orderBy('times_sold_cnt', 'DESC')->orderBy('unit_price', 'DESC')->orderBy('date_last_sold', 'ASC')->get();


        $heads = ['ItemID', 'ItemDesc', 'CustID', 'Cust', 'Orders', 'Qty', 'UOM', 'Price', 'DateLastPurchase', ['label' => 'Details', 'no-export' => true, 'width' => 2]];

        $data = [];

        foreach ($saleshalts as $saleshalt) {

            $data[] = [
                $saleshalt->item_id,
                $saleshalt->item_desc,
                $saleshalt->customer_id,
                $saleshalt->customer_name,
                $saleshalt->times_sold_cnt,
                $saleshalt->total_qty_sold,
                $saleshalt->uom,
                $saleshalt->unit_price,
                $saleshalt->date_last_sold,

                '<a class=btn btn-link" style="color: #018786;" href="/saleshalt/details/' . $saleshalt->item_id . '/' . $saleshalt->customer_id . '"><i class="far fa-eye"/></a>',
            ];
        }

        $config = [
            'data' => $data,
            // 'order' => [[0, 'desc']],
            'lengthChange' => true,
            'paging' => true,
            'info' => false,
            'language' => ['emptyTable' => 'There are no freight charges for the selected month', 'zeroRecords' => 'There are no freight charges for the selected month'],
            'columns' => [null, ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false],['orderable' => false]],
        ];

        return view('saleshalt.index', compact('heads', 'config'));
    }


    public function details($itemid, $custid)
    {
        $details = EpicorViewInvoiceLine::select('p21_view_invoice_line.item_id')->selectRaw('SUM(CAST(p21_view_invoice_line.qty_shipped as int)) AS qty')->selectRaw('CAST(p21_view_invoice_hdr.order_date as date) AS orderdate')->join('p21_view_invoice_hdr', 'p21_view_invoice_line.invoice_no', '=', 'p21_view_invoice_hdr.invoice_no')->where('p21_view_invoice_line.item_id', '=', $itemid)->where('p21_view_invoice_hdr.customer_id', '=', $custid)->where('p21_view_invoice_hdr.invoice_date', '>=', '2024-10-02')->groupBy('p21_view_invoice_line.item_id', DB::raw('CAST(p21_view_invoice_hdr.order_date as date)'))->orderBy('orderdate', 'ASC')->take(100)->get();


        $yoy = $details->pluck('qty', 'orderdate');
        $yoylabel = [];
        $yoydata = [];

        foreach($yoy as $k => $v) {
            $yoylabel[] = $k;
            $yoydata[] = $v;
        }




        // dd($yoylabel, $yoydata);

        return view('saleshalt.details', compact('yoylabel', 'yoydata', 'itemid', 'custid'));
    }
}
 