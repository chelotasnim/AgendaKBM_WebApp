<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal_Kelas extends Model
{
    use HasFactory;

    protected $table = 'jurnal_kelas';
    protected $guarded = ['id'];

    public function guru_mapel()
    {
        return $this->belongsTo(GuruMapel::class, 'guru_mapel_id');
    }
}
