<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'interests',
        'qr_token',
        'checked_in'
    ];

    public function subGuests()
{
    return $this->hasMany(SubGuest::class);
}

    use HasFactory;
}
