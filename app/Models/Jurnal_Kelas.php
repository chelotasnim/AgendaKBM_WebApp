<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal_Kelas extends Model
{
    use HasFactory;

    protected $table = 'jurnal_kelas';
    protected $guarded = ['id'];
}
