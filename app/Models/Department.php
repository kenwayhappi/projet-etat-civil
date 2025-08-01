<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = ['name', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
