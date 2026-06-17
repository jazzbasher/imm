<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOffRequest extends Model
{
    protected $table = 'timeoff_request';
    protected $fillable = ['user_id', 'manager_id', 'title', 'type', 'start', 'end', 'status', 'reason', 'allDay'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function requesttype()
    {
        return $this->hasOne(TimeOffType::class, 'type_id', 'type');
    }

}
