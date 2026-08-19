<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorOEHDR;
use App\Models\ADSupplierMap;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\SPRemitExport;
use App\Exports\ADRemitExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class APRemittanceController extends Controller
{
    public function view()
    {
        return view('remit.dateform');
    }


    public function map()
    {

        $mapping = ADSupplierMap::with('vendor')->get();


        $heads = ['VendorID', 'AD-ID', 'ProphetName', 'ADName', 'ISC', 'SP', ['label' => 'Edit', 'no-export' => true, 'width' => 2]];

        $data = [];

        foreach ($mapping as $map) {
            if(empty($map->vendor->vendor_name)) {

                $vendorname = '!!! Vendor ID does not exist in Prophet !!!';
            } else {
                $vendorname = $map->vendor->vendor_name;
            }

            if($map->is_isc == true) {
                    $isc = '<i class="fas fa-check text-success"></i>';
                } else {
                    $isc = '';
                }

            if($map->is_sp == true) {
                    $sp = '<i class="fas fa-check text-success"></i>';
                } else {
                    $sp = '';
                }


            $data[] = [

                $map->vendor_id,
                $map->supplier_id,
                $vendorname,
                $map->ad_vendorname,
                $isc,
                $sp,
                '<a class=btn btn-link" style="color: #018786;" href="/admap/edit/' . $map->vendor_id . '"><i class="fas fa-pencil-alt"/></a>',
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[2, 'asc']],
            'responsive' => true,
            'lengthChange' => true,
            'lengthMenu' => [[25, 50,-1], [25, 50, "All"]],
            'dom' => 'lBfrtip',
            'buttons' => ["excel", "pdf", "print"],
  
            'language' => ['emptyTable' => 'There are no AD Mapping Results', 'zeroRecords' => 'There are no AD Mapping Results'],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],

        ];

        return view('admap.advendormap', compact('heads', 'config'));

    }


    public function admapedit($vendor)
    {
        $advendor = ADSupplierMap::where('vendor_id', $vendor)->get();

        return view('admap.editvendor', compact('advendor'));
       
    }


    public function admapupdate(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|numeric',
            'ad_vendorname' => 'required|string'
           
        ]);


        $updatetrustee = ADSupplierMap::find($id);
        $updatetrustee->update($request->all());

        return redirect()->route('remit.mapping')->with('success', 'Vendor Mapping Updated Successfully');

    }


    public function admapcreate()
    {
        return view('admap.createvendor');
    }


    public function store(Request $request)
    {
         
        $validated = $request->validate([
            'vendor_id' => ['required', 'numeric', 'unique:adtrustee_map,vendor_id'],
            'supplier_id' => ['required', 'numeric'],
            'ad_vendorname' => ['required', 'string', 'min:2'],

        ]);
  
        ADSupplierMap::create([
            'vendor_id' => $validated['vendor_id'],
            'supplier_id' => $validated['supplier_id'],
            'ad_vendorname' => $validated['ad_vendorname'],
        ]);

        
        return redirect()->route('remit.mapping')->with('success', 'Vendor mapping added successfully!');
    }

    public function admapdestroy($id)
    {
        dd($id);
    }





    public function export(Request $request)
    {
        $date = $request->input('date');
        $formatteddate = carbon::parse($date)->format('mdY');
      
        return Excel::download(new ADRemitExport($date), "PaymentDetails_{$formatteddate}.xls", \Maatwebsite\Excel\Excel::XLS);
    }



    public function serviceprovider(Request $request)
    {
        $date = $request->input('date');
        $formatteddate = carbon::parse($date)->format('mdY');
      
        return Excel::download(new SPRemitExport($date), "PaymentDetails_{$formatteddate}.xls", \Maatwebsite\Excel\Excel::XLS);
    }



    public function report(Request $request) 

    {
        // This is not the report itself which exports to excel.  That is found in App/Exports/ADRemitExport
        
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $reportdate = $request->input('date');

        $origin = 'Industrial, Safety and Construction';
        // $remitreport = EpicorOEHDR::select('vendor_id','invoice_no', 'invoice_date', 'invoice_amount', 'terms_amount_taken')->whereNotNull('check_no')->whereDate('check_date', $reportdate)->whereHas('vendor')->with('vendor')->with('address')->get();


         $remitreport = EpicorOEHDR::select('vendor_id')->selectRaw('SUM(invoice_amount - terms_amount_taken) as total')->selectRaw('COUNT(*) as invoicecount')->whereNotNull('check_no')->whereDate('check_date', $reportdate)->whereHas('vendor')->with('vendor')->with('admap')->groupBy('vendor_id')->get();



         $totalinvoices = 0;
         $totalremittance = 0;
         $spcount = 0;




         $heads = ['Supplier Name', 'AD Supplier ID', 'Number of Invoices', 'Total Remittance'];


        $data = [];

        foreach ($remitreport as $remit) {

            if($remit->admap->is_isc == true) {



            $totalinvoices += $remit['invoicecount'];
            $totalremittance += $remit['total'];
            
            $data[] = [
                $remit->vendor->vendor_name,
                $remit->admap->supplier_id,
                $remit->invoicecount,
                number_format($remit->total, 2, '.', ',')
               
            ];

            } elseif ($remit->admap->is_sp == true) {

                $spcount += $remit['invoicecount'];

            }
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc'],[2,'asc']],
            'lengthChange' => false,
            'paging' => false,
            'info' => true,
            'language' => ['emptyTable' => 'There are no results for the date you selected', 'zeroRecords' => 'There are no results for the date you selected'],
            'columns' => [null, null, null, null],
            'buttons' =>  [
                 
                ]

        ];

         return view('remit.remitreport', compact('heads', 'config', 'reportdate', 'totalinvoices', 'totalremittance', 'spcount', 'origin'));

    }



    public function spreport($reportdate)
    {
        // $request->validate([
        //     'date' => 'required|date_format:Y-m-d',
        // ]);

        // $reportdate = $request->input('date');


        // $remitreport = EpicorOEHDR::select('vendor_id','invoice_no', 'invoice_date', 'invoice_amount', 'terms_amount_taken')->whereNotNull('check_no')->whereDate('check_date', $reportdate)->whereHas('vendor')->with('vendor')->with('address')->get();

        $origin = 'Service Provider Program';


         $remitreport = EpicorOEHDR::select('vendor_id')->selectRaw('SUM(invoice_amount - terms_amount_taken) as total')->selectRaw('COUNT(*) as invoicecount')->whereNotNull('check_no')->whereDate('check_date', $reportdate)->whereHas('vendor')->with('vendor')->with('admap')->groupBy('vendor_id')->get();



         $totalinvoices = 0;
         $totalremittance = 0;
         $spcount = 0;




         $heads = ['Service Provider Name', 'AD Supplier ID', 'Number of Invoices', 'Total Remittance'];


        $data = [];

        foreach ($remitreport as $remit) {

            if($remit->admap->is_sp == true) {



            $totalinvoices += $remit['invoicecount'];
            $totalremittance += $remit['total'];
            
            $data[] = [
                $remit->vendor->vendor_name,
                $remit->admap->supplier_id,
                $remit->invoicecount,
                number_format($remit->total, 2, '.', ',')
               
            ];

            } elseif ($remit->admap->is_isc == true) {

                $spcount += $remit['invoicecount'];

            }
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc'],[2,'asc']],
            'lengthChange' => false,
            'paging' => false,
            'info' => true,
            'language' => ['emptyTable' => 'There are no results for the date you selected', 'zeroRecords' => 'There are no results for the date you selected'],
            'columns' => [null, null, null, null],
            'buttons' =>  [
                 
                ]
        ];

         return view('remit.remitreport', compact('heads', 'config', 'reportdate', 'totalinvoices', 'totalremittance', 'spcount', 'origin'));

    }
    


    
}
