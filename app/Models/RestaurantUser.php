<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'role',
    ];

    const ROLE_ADMIN = 1;
    const ROLE_STAFF = 2;

    protected $casts = [
        'role' => "integer",
        'user_id' => "integer",
        'restaurant_id' => "integer",
    ];
}
