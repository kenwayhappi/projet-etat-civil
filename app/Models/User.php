<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role', 'center_id'];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
