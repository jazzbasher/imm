<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorSalesHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\POSSandvikExport;
use Maatwebsite\Excel\Facades\Excel;


class POSReportController extends Controller
{

    public function view()
    {
        return view('posreport.sandvikposreport');
    }

    
    public function sandvikpos()
    {
        $start = '2026-06-01';
        $end = '2026-07-01';


        $sandvik = EpicorSalesHistory::select('ship2_name', 'ship2_postal_code', 'bill2_postal_code', 'item_desc', 'item_id', 'qty_shipped', 'unit_price', 'unit_of_measure', 'invoice_date', 'source_loc_id', 'source_location_name', 'period', 'year_for_period')->where('supplier_id', '14711')->whereBetween(DB::raw('CAST(invoice_date AS DATE)'), [$start, $end])->orderBy('invoice_date', 'ASC')->get();

        dd($sandvik);
    }



    public function export(Request $request)
    {



        if ($request->input('dateparam') == 'lastmonth') {

                $start = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $end   = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');



            } elseif ($request->input('dateparam') == 'daterange') {


                $request->validate([
                    'start' => 'required|date_format:Y-m-d',
                    'end' => 'required|date_format:Y-m-d',
                ]);


                $start = $request->input('start');
                $end = $request->input('end');

            }

   

        $date = Carbon::now()->format('Y-m-d');
        
        // Pass parameters to the Export class
        return Excel::download(new POSSandvikExport($start, $end), "sandvik_{$date}.xlsx", \Maatwebsite\Excel\Excel::XLSX);
    }



}
