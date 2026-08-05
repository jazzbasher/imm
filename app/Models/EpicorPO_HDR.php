<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpicorPO_HDR extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'po_hdr';
}
