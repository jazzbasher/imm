<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeClock extends Model
{
    protected $table = 'timeclock';
    protected $fillable = ['user_id', 'clock_in', 'clock_out'];

    // Relate attendance back to the User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
