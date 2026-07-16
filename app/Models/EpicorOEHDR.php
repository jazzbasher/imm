<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class EpicorOEHDR extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'apinv_hdr';


    protected function casts(): array
    {
        return [
            'invoice_amount' => 'decimal:2',
            'terms_amount_taken' => 'decimal:2',
        ];
    }



    public function vendor()
    {
        return $this->hasOne(EpicorADVendor::class, 'vendor_id', 'vendor_id');
    }

    public function address()
    {
        return $this->hasOne(EpicorAddress::class, 'id', 'vendor_id');
    }


}
