<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'nama', 'jenis_kelamin', 'jabatan', 'divisi', 'nik', 'pin', 'status', 'fp', 'device_token'
    ];

    public function absen()
    {
    	return $this->hasMany(Absen::class, 'id');
    }

    public function lembur()
    {
    	return $this->hasMany(Lembur::class, 'id');
    }

    public function rekapDurasi()
    {
        return $this->hasMany(RekapDurasi::class, 'id');
    }

}
