<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodDocument extends Model
{
    protected $fillable = [
        'filename',
        'file_path',
        'file_modified_at',
        'content',
    ];
}
