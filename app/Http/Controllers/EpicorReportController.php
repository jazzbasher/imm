<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorOE_LINE;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EpicorReportController extends Controller
{

    public function lineitemview()
    {
        return view('epicor.lineitemreportform');
    }
    

    public function deena(Request $request)
    {
   

        $request->validate([
                    'start' => 'required|date_format:Y-m-d',
                    'end' => 'required|date_format:Y-m-d',
        ]);


        $startdate = $request->input('start');
        $enddate = $request->input('end');

// dd($startdate, $enddate);

        $dataset = EpicorOE_LINE::select('order_no', 'inv_mast_uid', 'date_created', 'disposition')->whereNotNull('disposition')->where('disposition', '<>', '')
        ->whereHas('hdr', function ($query) use ($startdate, $enddate) {
                $query->whereDate('order_date', '>=', $startdate)
                ->whereDate('order_date', '<=', $enddate)
                ->where('taker', 'DEDWARDS');
        })
        ->with(['hdr' => function ($query) {
            $query->select('order_no', 'taker', 'order_date');
        }])
        ->with(['item' => function ($query) {
            $query->select('inv_mast_uid', 'item_id', 'item_desc');
        }])
        ->with(['po' => function ($query) {
            $query->select('sales_order_number', 'po_no');
        }])->get();



        $heads = ['OrderNo', 'OrderDate', 'Disposition', 'ItemID', 'ItemDesc', 'PO'];

        $data = [];

         foreach ($dataset as $datas) {

            if(!empty($datas->po->po_no)) {
                $po = $datas->po->po_no;

            } else {
                $po = '';
            }

            $data[] = [

                $datas->order_no,
                Carbon::parse($datas->hdr->order_date)->format('Y-m-d'),
                $datas->disposition,
                $datas->item->item_id,
                $datas->item->item_desc,
                $po

            ];
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'responsive' => true,
            'lengthChange' => true,
            'lengthMenu' => [[50, 100,-1], [50, 100, "All"]],
            'dom' => 'lBfrtip',
            'buttons' => ["excel", "pdf", "print"],
  
            'language' => ['emptyTable' => 'There are no Results', 'zeroRecords' => 'There are no Results'],
            'columns' => [null, null, null, null, null, null],

        ];



        return view('epicor.deenareport', compact('heads', 'config'));




    }






}
