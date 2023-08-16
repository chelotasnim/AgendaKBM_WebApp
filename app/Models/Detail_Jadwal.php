<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Jadwal extends Model
{
    use HasFactory;

    protected $table = 'detail_jadwal';
    protected $guarded = ['id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}
