<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EpicorADVendor extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'vendor';

    protected static function booted()
    {
        static::addGlobalScope('AD', function (Builder $builder) {
            $builder->where('class_1id', 'AD');
        });
    }


}
