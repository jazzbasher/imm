<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EpicorOEHDR;

class APRemittanceController extends Controller
{

    public function report() 

    {

        $remitreport = EpicorOEHDR::where('invoice_amount', '>', 0)->whereNotNull('check_no')->whereDate('check_date', '2026-07-08')->whereHas('vendor')->get();

        dd($remitreport);

    }


    
}
