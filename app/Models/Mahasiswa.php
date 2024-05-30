<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'id_users',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_users');
    }

    public function dosenPembimbings(){
        return $this->hasMany(DosenPembimbing::class, 'id_mahasiswa');
    }

    public function logBimbingan(){
        return $this->hasMany(LogBimbingan::class, 'id_mahasiswa');
    }
}
