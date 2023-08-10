<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $guarded = ['id'];
}
