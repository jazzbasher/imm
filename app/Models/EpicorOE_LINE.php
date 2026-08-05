<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorOE_LINE extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'oe_line';


    public function po()
    {
        return $this->hasOne(EpicorPO_HDR::class, 'sales_order_number', 'order_no');
    }

    public function item()
    {
        return $this->hasOne(EpicorInvMast::class, 'inv_mast_uid', 'inv_mast_uid');
    }

    public function hdr()
    {
        return $this->hasOne(EpicorOE_HDR::class, 'order_no', 'order_no');
    }

}
