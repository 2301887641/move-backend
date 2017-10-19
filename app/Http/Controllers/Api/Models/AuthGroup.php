<?php

namespace App\Http\Controllers\Api\Models;

use Illuminate\Database\Eloquent\Model;

class AuthGroup extends Model
{
    protected $fillable=["name","permission_id"];
}
