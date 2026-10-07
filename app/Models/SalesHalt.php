<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesHalt extends Model
{
    protected $table = 'salesfore';
    protected $fillable = ['item_id', 'item_desc', 'customer_id', 'customer_name', 'times_sold_cnt', 'total_qty_sold', 'uom', 'unit_price', 'date_last_sold'];
}
