<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGuest extends Model
{

    protected $fillable = [
        'guest_id',
        'name'
    ];
    

    public function guest()
{
    return $this->belongsTo(Guest::class);
}
    use HasFactory;
}
