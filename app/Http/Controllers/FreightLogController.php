<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreightLog;
use App\Models\User;
use Carbon\Carbon;

class FreightLogController extends Controller
{

    public function index()
    {


        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);
   
        $logs = FreightLog::whereBetween('date', [$currentpayperiod['start_date'], $currentpayperiod['end_date']])->orderBy('date', 'desc')->with('user')->with('outsidesales')->get();
    

        $viewparam = 1;

        
        $heads = ['Date', 'Customer', 'Buyer', 'Sales Rep', 'PO', 'Amount', 'Order #', 'Notes', 'Added', ['label' => 'Edit', 'no-export' => true, 'width' => 2]];

        $data = [];

        foreach ($logs as $log) {
            if(isset($log->user->name)) {
                $user = strtok($log->user->name, " ");
            } else {
                $user = $log->initials;
            }
            $data[] = [
                Carbon::parse($log->date)->format('Y/m/d'),
                $log->customer_id,
                ucwords(strtolower($log->buyer)),
                $log->outsidesales->name,
                $log->po,
                $log->amount,
                $log->order_no,
                $log->notes,
                $user,
                '<a class=btn btn-link" style="color: #018786;" href="/freightlog/edit/' . $log->id . '"><i class="fas fa-pencil-alt"/></a>',
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'desc']],
            'lengthChange' => false,
            'paging' => false,
            'info' => false,
            'language' => ['emptyTable' => 'There are no freight charges for the selected payperiod', 'zeroRecords' => 'There are no freight charges for the selected payperiod'],
            'columns' => [null, ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false],['orderable' => false]],
        ];

        return view('freightlog.index', compact('heads', 'config', 'currentpayperiod', 'viewparam'));
    }






    public function lastmonth()
    {
        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);
   
        $logs = FreightLog::whereBetween('date', [$previouspayperiod['start_date'], $previouspayperiod['end_date']])->orderBy('date', 'desc')->orderBy('date', 'desc')->with('user')->with('outsidesales')->get();


        $viewparam = 2;

        
        $heads = ['Date', 'Customer', 'Buyer', 'Sales Rep', 'PO', 'Amount', 'Order #', 'Notes', 'Added', ['label' => 'Edit', 'no-export' => true, 'width' => 2]];

        $data = [];

        foreach ($logs as $log) {
            if(isset($log->user->name)) {
                $user = strtok($log->user->name, " ");
            } else {
                $user = $log->initials;
            }
            $data[] = [
                Carbon::parse($log->date)->format('Y/m/d'),
                $log->customer_id,
                ucwords(strtolower($log->buyer)),
                $log->outsidesales->name,
                $log->po,
                $log->amount,
                $log->order_no,
                $log->notes,
                $user,
                '<a class=btn btn-link" style="color: #018786;" href="/freightlog/edit/' . $log->id . '"><i class="fas fa-pencil-alt"/></a>',
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'desc']],
            'lengthChange' => false,
            'paging' => false,
            'info' => false,
            'columns' => [null, ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false],['orderable' => false]],
        ];

        return view('freightlog.index', compact('heads', 'config', 'previouspayperiod', 'viewparam'));
    }





    public function create()
    {
        $salespeople = User::where('outside_sales', 1)->orderBy('name', 'asc')->pluck('id', 'name');


        return view('freightlog.create', compact('salespeople'));
    }




    public function edit($id)
    {

        $logs = FreightLog::where('id', $id)->with('outsidesales')->get();
        $salespeople = User::where('outside_sales', 1)->orderBy('name', 'asc')->pluck('id', 'name');
 
        if($logs->isNotEmpty()) {

            return view('freightlog.edit', compact('logs', 'id', 'salespeople'));

        } else {

            return redirect()->route('freightlog')->with('error','That freight log not found');
        }


    }



    public function updatelog(Request $request, $id)
    {
        
        $request->validate([
            'customer_id' => 'required|string',
            'buyer' => 'required|string',
            'salesrep' => 'required|string',
            'po' => 'required|string',
            'amount' => 'required|numeric|between:0.01,999999.99',
            'order_no' => 'required|string',
            'notes'   => 'nullable'
        ]);


        $updatelog = FreightLog::find($id);
        $updatelog->update($request->all());

        return redirect()->route('freightlog')->with('success', 'Freight Charge Updated Successfully');

    }


    public function store(Request $request)
    {
        $date = now()->toDateTimeString();


        $request->merge([
            'date' => $date 
        ]);

        $request->validate([
            'customer_id' => 'required|string',
            'buyer' => 'required|string',
            'salesrep' => 'required|string',
            'po' => 'required|string',
            'amount' => 'required|numeric|between:0.01,999999.99',
            'order_no' => 'required|string',
            'notes'   => 'nullable',
            'user_id' => 'required'
        ]);

        $freightadd = FreightLog::create($request->all());

        return redirect()->route('freightlog')->with('success','Freght Charge Succesfully Added!');
    }



    public function adminreport()
    {
        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);

         $logs = FreightLog::selectRaw('salesrep, SUM(amount) AS total')->whereBetween('date', [$previouspayperiod['start_date'], $previouspayperiod['end_date']])->with('outsidesales')->groupBy('salesrep')->orderBy('total', 'DESC')->get();


         $itemized = FreightLog::whereBetween('date', [$previouspayperiod['start_date'], $previouspayperiod['end_date']])->with('outsidesales')->get();

$keyed = $itemized->groupBy('outsidesales.name');


   return view('admin.freight.freightreport', compact('logs', 'previouspayperiod', 'keyed'));


    }



    
}

