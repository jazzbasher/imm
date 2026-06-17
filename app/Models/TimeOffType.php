<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOffType extends Model
{
    protected $table = 'timeoff_type';

    public function requests()
    {
        return $this->hasMany(TimeOffRequest::class, 'type', 'type_id');
    }
}
