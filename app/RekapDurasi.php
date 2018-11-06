<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekapDurasi extends Model
{
    protected $table = 'rekap_durasi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'durasi_kerja', 'duras_telat', 'karyawan_id'
    ];


    public function karyawan()
    {
      $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
}
