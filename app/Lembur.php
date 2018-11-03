<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    protected $table = 'lemburs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'karyawan_id', 'durasi', 'alasan'
    ];

    public function karyawan()
    {
    	return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

}
