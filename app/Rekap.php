<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekap extends Model
{
    protected $table = 'rekap';
    protected $primaryKey = 'id';
    protected $fillable = [
        'absen_id', 'jml_hadir', 'jml_izin', 'jml_sakit', 'jml_alfa', 'tgl_rekap'
    ];


    public function absen()
    {
      $this->belongsTo(Absen::class, 'absen_id', 'id');
    }
}
