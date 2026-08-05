<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ADSupplierMap extends Model
{
    protected $connection = 'mysql';
    protected $table = 'adtrustee_map';
    protected $primaryKey = 'vendor_id';
    protected $fillable = ['vendor_id', 'supplier_id', 'ad_vendorname'];


    public function vendor()
    {
        return $this->hasOne(EpicorADVendor::class, 'vendor_id', 'vendor_id')->select(['vendor_id', 'vendor_name']);
    }
}


