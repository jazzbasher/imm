<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $connection = 'mysql';
    protected $table = 'branch';
    protected $primaryKey = 'branch_id';


    public function user()
    {
        return $this->hasMany(User::class, 'branch', 'branch_id');
    }


}
