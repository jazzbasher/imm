<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorItemView extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'p21_item_view';
}
