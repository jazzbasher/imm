<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreightLog;
use Carbon\Carbon;

class FreightLogController extends Controller
{

    public function index()
    {
   
        $logs = FreightLog::orderBy('date', 'desc')->with('user')->get();
        
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
                ucwords(strtolower($log->salesrep)),
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
            'pageLength' => 50,
            'columns' => [null, null, null, null, null, null, null, null, null,['orderable' => false]],
        ];

        return view('freightlog.index', compact('heads', 'config'));
    }


    public function create()
    {
        return view('freightlog.create');
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
    
}

