<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $guarded = ['id'];

    public function jam_mengajar()
    {
        return $this->hasMany(Detail_Jadwal::class, 'guru_id');
    }
}
