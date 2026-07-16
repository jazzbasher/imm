<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorSalesHistory extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'P21_sales_history_view';
}
