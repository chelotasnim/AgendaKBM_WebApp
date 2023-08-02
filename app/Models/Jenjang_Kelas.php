<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenjang_Kelas extends Model
{
    use HasFactory;

    protected $table = 'jenjang_kelas';
    protected $guarded = ['id'];
}
