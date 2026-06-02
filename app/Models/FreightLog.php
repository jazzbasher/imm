<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreightLog extends Model
{
    protected $table = 'freightlog';
    protected $fillable = ['date', 'customer_id', 'buyer', 'salesrep', 'po', 'amount', 'initials', 'order_no', 'notes', 'user_id'];
}
