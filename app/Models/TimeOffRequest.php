<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOffRequest extends Model
{
    protected $table = 'timeoff_request';
    protected $fillable = ['user_id', 'title', 'type', 'start', 'end', 'status', 'reason'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
