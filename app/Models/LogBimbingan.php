<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBimbingan extends Model
{
    use HasFactory;

    protected $table = 'log_bimbingan';

    protected $fillable = [
        'id_mahasiswa',
        'id_dosen',
        'catatan_bimbingan',
        'tanggal_bimbingan',
        'action_plan',
    ];

    public function dosen(){
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
    
}
