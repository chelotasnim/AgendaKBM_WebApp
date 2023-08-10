<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $guarded = ['id'];

    public function jenjang()
    {
        return $this->belongsTo(Jenjang_Kelas::class, 'jenjang_kelas_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kelas_id');
    }
}
