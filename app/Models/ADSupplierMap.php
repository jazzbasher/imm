<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ADSupplierMap extends Model
{
    protected $connection = 'mysql';
    protected $table = 'adtrustee_map';
    protected $primaryKey = 'vendor_id';
    protected $fillable = ['vendor_id', 'supplier_id', 'ad_vendorname', 'is_isc', 'is_sp'];


    protected function isIscText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_isc ? 'Yes' : 'No',
        );
    }

    protected function isSpText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_sp ? 'Yes' : 'No',
        );
    }


    public function vendor()
    {
        return $this->hasOne(EpicorADVendor::class, 'vendor_id', 'vendor_id')->select(['vendor_id', 'vendor_name']);
    }
}


