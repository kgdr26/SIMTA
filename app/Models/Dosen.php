<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    
    protected $fillable = [
        'nip',
        'tandaTangan',
        'id_users',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_users');
    }
    public function logBimbingan(){
        return $this->hasMany(LogBimbingan::class, 'id_dosen');
    }

}
