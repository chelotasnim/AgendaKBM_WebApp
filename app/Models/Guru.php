<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;
use Laravel\Sanctum\HasApiTokens;

class Guru extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'guru';
    protected $guarded = ['id'];

    public function jurnal()
    {
        return $this->hasMany(Jurnal_Kelas::class, 'guru_id');
    }

    public function guru_mapel()
    {
        return $this->hasMany(GuruMapel::class, 'guru_id');
    }
}
