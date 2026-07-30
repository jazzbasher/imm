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


            $reportdate = Carbon::parse($start)->format('Ym');

        
        // Pass parameters to the Export class
        return Excel::download(new POSmmmExport($start, $end), "Industrial_Mill_&_Maintenance_Supply_POS_{$reportdate}.xlsx", \Maatwebsite\Excel\Excel::XLSX);
    }



}
