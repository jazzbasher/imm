<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class PendingRequestCount extends Model
{
    protected $table = 'timeoff_request';

    protected static function booted(): void
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', 0);
        });
    }


}
