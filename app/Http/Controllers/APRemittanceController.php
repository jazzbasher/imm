<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorOEHDR;
use App\Models\ADSupplierMap;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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


        $heads = ['VendorID', 'AD-ID', 'ProphetName', 'ADName', ['label' => 'Edit', 'no-export' => true, 'width' => 2]];

        $data = [];

        foreach ($mapping as $map) {
if(empty($map->vendor->vendor_name)) {

    dd($map);
}
            $data[] = [

                $map->vendor_id,
                $map->supplier_id,
                $map->vendor->vendor_name,
                $map->ad_vendorname,
                '<a class=btn btn-link" style="color: #018786;" href="/freightlog/edit/' . $map->vendor_id . '"><i class="fas fa-pencil-alt"/></a>',
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
            'columns' => [null, null, null, null,['orderable' => false]],

        ];

        return view('admap.advendormap', compact('heads', 'config'));

    }





    public function export(Request $request)
    {
        $date = $request->input('date');
      
        return Excel::download(new ADRemitExport($date), "adremit_{$date}.xlsx", \Maatwebsite\Excel\Excel::XLSX);
    }



    public function report(Request $request) 

    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $reportdate = $request->input('date');


        $remitreport = EpicorOEHDR::select('vendor_id','invoice_no', 'invoice_date', 'invoice_amount', 'terms_amount_taken')->whereNotNull('check_no')->whereDate('check_date', $reportdate)->whereHas('vendor')->with('vendor')->with('address')->get();


         $heads = ['Supplier Name', 'Supplier Acct ID', 'Invoice Number', 'Invoice Date', 'Original Invoice Amount', 'Remittance Amount', 'Member Discount Taken'];


        $data = [];

        foreach ($remitreport as $remit) {
            
            $data[] = [
                $remit->vendor->vendor_name,
                (int)preg_replace('/[^0-9]/', '', $remit->address->mail_address1),
                strval($remit->invoice_no),
                Carbon::parse($remit->invoice_date)->format('m/d/Y'),
                $remit->invoice_amount,
                number_format($remit->invoice_amount - $remit->terms_amount_taken, 2, '.', ''),
                $remit->terms_amount_taken
               
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc'],[2,'asc']],
            'lengthChange' => false,
            'paging' => false,
            'info' => true,
            'language' => ['emptyTable' => 'There are no results for the date you selected', 'zeroRecords' => 'There are no results for the date you selected'],
            'columns' => [null, null, null, null, null, null, null],
            'buttons' =>  [
                        [ 
                            'extend' => 'excelHtml5',
                            'text' => '<i class="fas fa-file-excel text-success"></i>',
                            'title' => '',
                            'className' => 'btn btn-success',
                            'titleAttr' => 'Excel Export',
                            'filename' => 'AD_Remit_' . $reportdate, 
                        ],
                        [ 
                            'extend' => 'print',
                            'text' => '<i class="fas fa-print text-warning"></i>',
                            'className' => 'btn btn-success',
                            'titleAttr' => 'Print',
                        ],
                ]
        ];

         return view('remit.remitreport', compact('heads', 'config', 'reportdate'));


    }


    
}
