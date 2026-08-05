<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorInvMast extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'inv_mast';
}
