<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorOEHDR extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'apinv_hdr';

    public function vendor()
    {
        return $this->hasOne(EpicorADVendor::class, 'vendor_id', 'vendor_id');
    }


}
