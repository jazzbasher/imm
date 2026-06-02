<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreightLog;
use Carbon\Carbon;

class FreightLogController extends Controller
{

    public function index()
    {
   
        $logs = FreightLog::orderBy('date', 'desc')->get();
        
        $heads = ['Date', 'Customer', 'Buyer', 'Sales Rep', 'PO', 'Amount', 'Initials', 'Order #', 'Notes', ['label' => 'Actions', 'no-export' => true, 'width' => 5]];
        $data = [];

        foreach ($logs as $log) {
            $data[] = [
                Carbon::parse($log->date)->format('Y/m/d'),
                $log->customer_id,
                $log->buyer,
                $log->salesrep,
                $log->po,
                $log->amount,
                $log->initials,
                $log->order_no,
                $log->notes,
                '<button class="btn btn-xs btn-default text-secondary mx-1"><i class="fa fa-lg fa-fw fa-pen"></i></button>',
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
    
}
