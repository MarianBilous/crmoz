<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZohoToken extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'api_domain',
        'expires_in'
    ];

    public $timestamps = false;
}
