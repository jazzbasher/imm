<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorSalesHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\POSSandvikExport;
use App\Exports\POSmmmExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;


class POSReportController extends Controller
{

    public function view()
    {
        return view('posreport.sandvikposreport');
    }


     public function allposview(Request $request)
    {
        $vendor = Route::currentRouteName();

        return view('posreport.posreports', compact('vendor'));

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


    // public function ceratizitpos(Request $request)
    // {
    //     $startdate = Carbon::parse('2026-01-01')->startOfDay();
    //     $enddate = Carbon::parse('2026-09-30')->endOfDay();

    //     $ceratizit = EpicorSalesHistory::select('branch_id', 'customer_id', 'ship2_name', 'ship2_city', 'ship2_state',
    //     'ship2_country', 'ship2_postal_code', 'item_id', 'item_desc', 'qty_shipped', 'cogs_amount', 'invoice_date')
    //     ->where('supplier_id', '14142')
    //     ->whereBetween('invoice_date', [$startdate, $enddate])
    //     ->where('rma_flag', 'N')->orderBy('invoice_date')->get();


    //  dd($ceratizit);

    // }


    // public function ingersolpos(Request $request)
    // {
    //     $startdate = Carbon::parse('2026-09-01')->startOfDay();
    //     $enddate = Carbon::parse('2026-09-30')->endOfDay();


    //     $ingersol = EpicorSalesHistory::select('company_name', 'invoice_date', 'ship2_postal_code', 'cogs_amount',
    //     'item_id', 'qty_shipped', 'ship2_name')
    //     ->where('supplier_id', '11125')
    //     ->whereBetween('invoice_date', [$startdate, $enddate])
    //     ->orderBy('invoice_date')->get();

    // dd($ingersol);

    // }


    // public function iscarpos(Request $request)
    // {
    //     $startdate = Carbon::parse('2026-09-01')->startOfDay();
    //     $enddate = Carbon::parse('2026-09-30')->endOfDay();

    //     $iscar = EpicorSalesHistory::selectRaw('DATEPART(year, invoice_date) AS Year, DATEPART(month, invoice_date) AS Month, ship2_state AS State, ship2_postal_code, ship2_name, item_id, cogs_amount, qty_shipped')
    //     ->where('supplier_id', '18915')
    //     ->whereBetween('invoice_date', [$startdate, $enddate])
    //     ->where('rma_flag', 'N')->orderBy('invoice_date')->get();


    //  dd($iscar);

    // }


    // public function mitsubishipos(Request $request)
    // {
    //     $startdate = Carbon::parse('2026-09-01')->startOfDay();
    //     $enddate = Carbon::parse('2026-09-30')->endOfDay();

    //     $mitsubishi = EpicorSalesHistory::select('branch_id', 'customer_id', 'ship2_name', 'ship2_address1', 'ship2_city', 'ship2_state', 'ship2_country', 'ship2_postal_code', 'item_id', 'item_desc', 'qty_shipped', 'cogs_amount', 'invoice_date')
    //     ->where('supplier_id', '14340')
    //     ->whereBetween('invoice_date', [$startdate, $enddate])
    //     ->where('rma_flag', 'N')->orderBy('invoice_date')->get();


    //  dd($mitsubishi);

    // }


    public function ciimposreport(Request $request)
    {
        $request->validate([
                    'start' => 'required|date_format:Y-m-d',
                    'end' => 'required|date_format:Y-m-d',
                    'vendor' => 'required',
                ]);

        $vendor = $request->input('vendor');
        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();


        // dd($vendor, $start, $end);


        if($vendor == 'Ceratizit') {

            $dataset = EpicorSalesHistory::select('branch_id', 'customer_id', 'ship2_name', 'ship2_city', 'ship2_state',
            'ship2_country', 'ship2_postal_code', 'item_id', 'item_desc', 'qty_shipped', 'cogs_amount', 'invoice_date')
            ->where('supplier_id', '14142')
            ->whereBetween('invoice_date', [$start, $end])
            ->where('rma_flag', 'N')->orderBy('invoice_date')->get();



            $heads = ['Branch', 'CustID', 'Name', 'City', 'State', 'Zip', 'ItemID', 'ItemDesc', 'Qty', 'Amount', 'Date'];

            $data = [];

            foreach ($dataset as $set) {
                
                $data[] = [

                    $set->branch_id,
                    $set->customer_id,
                    ucwords(strtolower($set->ship2_name)),
                    ucwords(strtolower($set->ship2_city)),
                    $set->ship2_state,
                    $set->ship2_postal_code,
                    $set->item_id,
                    $set->item_desc,
                    (int)$set->qty_shipped,
                    number_format($set->cogs_amount, 2, '.', ''),
                    date('m/d/y', strtotime($set->invoice_date))

                ];
            }

            $config = [
                'data' => $data,
                'order' => [[10, 'asc']],
                'responsive' => true,
                'lengthChange' => true,
                'lengthMenu' => [[50, 100,-1], [50, 100, "All"]],
                'language' => ['emptyTable' => 'There are no results', 'zeroRecords' => 'There are no results'],
                'buttons' => [
                    [
                        'extend' => 'excelHtml5',
                        'filename' => 'Ceratizit_POS_Report_' . date('Y-m-d'), 
                        'text' => '<i class="far fa-file-excel"></i><small> Excel</small>',
                        'title' => 'Ceratizit POS Report'                      
                    ],
                    [
                        'extend' => 'pdfHtml5',
                        'filename' => 'Ceratizit_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="far fa-file-pdf"></i><small> PDF</small>',
                        'title' => 'Ceratizit POS Report'
                    ],
                    [
                        'extend' => 'print',
                        'filename' => 'Ceratizit_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="fas fa-print"></i><small> Print</small>',
                        'title' => 'Ceratizit POS Report'
                    ]
                ],
                'columns' => [null, null, null, null, null, null, null, null, null, null, null],

            ];





        } elseif($vendor == 'Ingersoll') {

            $dataset = EpicorSalesHistory::select('company_name', 'invoice_date', 'ship2_postal_code', 'cogs_amount',
            'item_id', 'qty_shipped', 'ship2_name')
            ->where('supplier_id', '11125')
            ->whereBetween('invoice_date', [$start, $end])
            ->orderBy('invoice_date')->get();


            $heads = ['Company', 'Date', 'Zip', 'Amount', 'ItemID', 'Qty', 'ShipTo'];

            $data = [];

            foreach ($dataset as $set) {
                
                $data[] = [

                    ucwords(strtolower($set->company_name)),
                    date('m/d/y', strtotime($set->invoice_date)),
                    $set->ship2_postal_code,
                    number_format($set->cogs_amount, 2, '.', ''),
                    $set->item_id,
                    (int)$set->qty_shipped,
                    ucwords(strtolower($set->ship2_name))
                    
                ];
            }

            $config = [
                'data' => $data,
                'order' => [[1, 'asc']],
                'responsive' => true,
                'lengthChange' => true,
                'lengthMenu' => [[50, 100,-1], [50, 100, "All"]],
                'language' => ['emptyTable' => 'There are no results', 'zeroRecords' => 'There are no results'],
                'buttons' => [
                    [
                        'extend' => 'excelHtml5',
                        'filename' => 'Ingersoll_POS_Report_' . date('Y-m-d'), 
                        'text' => '<i class="far fa-file-excel"></i><small> Excel</small>',
                        'title' => 'Ingersoll POS Report'                      
                    ],
                    [
                        'extend' => 'pdfHtml5',
                        'filename' => 'Ingersoll_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="far fa-file-pdf"></i><small> PDF</small>',
                        'title' => 'Ingersoll POS Report'
                    ],
                    [
                        'extend' => 'print',
                        'filename' => 'Ingersoll_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="fas fa-print"></i><small> Print</small>',
                        'title' => 'Ingersoll POS Report'
                    ]
                ],
                'columns' => [null, null, null, null, null, null, null],

            ];




        } elseif($vendor == 'Iscar') {

            $dataset = EpicorSalesHistory::selectRaw('DATEPART(year, invoice_date) AS Year, DATEPART(month, invoice_date) AS Month, ship2_state AS State, ship2_postal_code, ship2_name, item_id, cogs_amount, qty_shipped')
            ->where('supplier_id', '18915')
            ->whereBetween('invoice_date', [$start, $end])
            ->where('rma_flag', 'N')->orderBy('invoice_date')->get();



            $heads = ['Year', 'Month', 'State', 'Zip', 'Name', 'ItemID', 'Amount', 'Qty'];

            $data = [];

            foreach ($dataset as $set) {
                
                $data[] = [

                    $set->Year,
                    $set->Month,
                    $set->State,
                    $set->ship2_postal_code,
                    ucwords(strtolower($set->ship2_name)),
                    $set->item_id,
                    number_format($set->cogs_amount, 2, '.', ''),
                    (int)$set->qty_shipped


                ];
            }

            $config = [
                'data' => $data,
                'order' => [[0, 'asc'],[1, 'asc']],
                'responsive' => true,
                'lengthChange' => true,
                'lengthMenu' => [[50, 100,-1], [50, 100, "All"]],
                'language' => ['emptyTable' => 'There are no results', 'zeroRecords' => 'There are no results'],
                'buttons' => [
                    [
                        'extend' => 'excelHtml5',
                        'filename' => 'Iscar_POS_Report_' . date('Y-m-d'), 
                        'text' => '<i class="far fa-file-excel"></i><small> Excel</small>',
                        'title' => 'Iscar POS Report'                      
                    ],
                    [
                        'extend' => 'pdfHtml5',
                        'filename' => 'Iscar_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="far fa-file-pdf"></i><small> PDF</small>',
                        'title' => 'Iscar POS Report'
                    ],
                    [
                        'extend' => 'print',
                        'filename' => 'Iscar_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="fas fa-print"></i><small> Print</small>',
                        'title' => 'Iscar POS Report'
                    ]
                ],
                'columns' => [null, null, null, null, null, null, null, null],

            ];



        } elseif($vendor == 'Mitsubishi') {

            $dataset = EpicorSalesHistory::select('branch_id', 'customer_id', 'ship2_name', 'ship2_address1', 'ship2_city', 'ship2_state', 'ship2_country', 'ship2_postal_code', 'item_id', 'item_desc', 'qty_shipped', 'cogs_amount', 'invoice_date')
            ->where('supplier_id', '14340')
            ->whereBetween('invoice_date', [$start, $end])
            ->where('rma_flag', 'N')->orderBy('invoice_date')->get();



            $heads = ['Branch', 'CustID', 'Name', 'City', 'State', 'Zip', 'ItemID', 'ItemDesc', 'Qty', 'Amount', 'Date'];

            $data = [];

            foreach ($dataset as $set) {
                
                $data[] = [

                    $set->branch_id,
                    $set->customer_id,
                    ucwords(strtolower($set->ship2_name)),
                    ucwords(strtolower($set->ship2_city)),
                    $set->ship2_state,
                    $set->ship2_postal_code,
                    $set->item_id,
                    $set->item_desc,
                    (int)$set->qty_shipped,
                    number_format($set->cogs_amount, 2, '.', ''),
                    date('m/d/y', strtotime($set->invoice_date))

                ];
            }

            $config = [
                'data' => $data,
                'order' => [[10, 'asc']],
                'responsive' => true,
                'lengthChange' => true,
                'lengthMenu' => [[50, 100,-1], [50, 100, "All"]],
                'language' => ['emptyTable' => 'There are no results', 'zeroRecords' => 'There are no results'],
                'buttons' => [
                    [
                        'extend' => 'excelHtml5',
                        'filename' => 'Mitsubishi_POS_Report_' . date('Y-m-d'), 
                        'text' => '<i class="far fa-file-excel"></i><small> Excel</small>',
                        'title' => 'Mitsubishi POS Report'                      
                    ],
                    [
                        'extend' => 'pdfHtml5',
                        'filename' => 'Mitsubishi_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="far fa-file-pdf"></i><small> PDF</small>',
                        'title' => 'Mitsubishi POS Report'
                    ],
                    [
                        'extend' => 'print',
                        'filename' => 'Mitsubishi_POS_Report_' . date('Y-m-d'),
                        'text' => '<i class="fas fa-print"></i><small> Print</small>',
                        'title' => 'Mitsubishi POS Report'
                    ]
                ],
                'columns' => [null, null, null, null, null, null, null, null, null, null, null],

            ];



        } else {

            dd('No Vendor Selected');
        }


        return view('posreport.ciimreport', compact('heads', 'config', 'vendor'));


    }



}
