<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorSalesHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\POSSandvikExport;
use App\Exports\POSmmmExport;
use Maatwebsite\Excel\Facades\Excel;


class POSReportController extends Controller
{

    public function view()
    {
        return view('posreport.sandvikposreport');
    }

    
    // public function sandvikpos()
    // {
    //     $start = '2026-06-01';
    //     $end = '2026-07-01';


    //     $sandvik = EpicorSalesHistory::select('ship2_name', 'ship2_postal_code', 'bill2_postal_code', 'item_desc', 'item_id', 'qty_shipped', 'unit_price', 'unit_of_measure', 'invoice_date', 'source_loc_id', 'source_location_name', 'period', 'year_for_period')->where('supplier_id', '14711')->whereBetween(DB::raw('CAST(invoice_date AS DATE)'), [$start, $end])->orderBy('invoice_date', 'ASC')->get();

    //     dd($sandvik);
    // }



    public function sandvikexport(Request $request)
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



    public function mmmpos()
    {
        // $start = '2026-06-01';
        // $end = '2026-06-30';


        // $mmm = EpicorSalesHistory::select('company_id', 'customer_id','ship2_name', 'ship2_address1', 'ship2_address2', 'ship2_city', 'ship2_state', 'ship2_postal_code', 'ship2_country', 'item_id', 'item_desc', 'invoice_date', 'invoice_no', 'qty_shipped', 'unit_of_measure', 'unit_price', 'extended_price')->where('supplier_id', '13202')->whereBetween(DB::raw('CAST(invoice_date AS DATE)'), [$start, $end])->orderBy('invoice_date', 'ASC')->get();

        // dd($mmm);

        return view('posreport.mmmposreport');
    }


    public function mmmexport(Request $request)
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

   

        $date = Carbon::now()->format('Ymd');
        
        // Pass parameters to the Export class
        return Excel::download(new POSmmmExport($start, $end), "Industrial_Mill_&_Maintenance_Supply_POS_{$date}.xlsx", \Maatwebsite\Excel\Excel::XLSX);
    }



}
