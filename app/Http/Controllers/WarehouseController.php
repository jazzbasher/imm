<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use App\Models\Media;

class WarehouseController extends Controller
{
    public function drumlabels()
    {
        $drumlabels = Media::where('identifier', 'LIKE', 'warehouse.drumlabels%')->get();

        return view('warehouse.drumlabels', compact('drumlabels'));
    }


    public function miscdocs()
    {
        $shippingchecklist = Media::where('identifier', 'warehouse.shippingchecklist')->value('file_path');
        $vehiclemaintenance = Media::where('identifier', 'warehouse.vehiclemaintenance')->value('file_path');


        return view('warehouse.miscdocuments', compact('shippingchecklist', 'vehiclemaintenance'));
    }

 
}

