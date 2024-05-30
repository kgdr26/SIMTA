<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing_m_h_s';

    protected $fillable = [
        'id_dosen',
        'id_mahasiswa',
        // 'id_users',
        'tipe'
    ];

    public function dosen(){
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
    // public function user(){
    //     return $this->belongsTo(User::class, 'id_users');
    // }


}
