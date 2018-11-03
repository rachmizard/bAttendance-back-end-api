<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterRekap extends Model
{
    protected $table = 'master_rekap';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tanggal_aktif_rekap',
        'start',
        'end'
    ];
    // protected $dates = ['tanggal_aktif_rekap'];

    public function absen()
    {
      $this->hasMany(Absen::class, 'id');
    }

    public function getTanggalAktifRekapAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['tanggal_aktif_rekap'])
           ->format('Y-m');
    }
}
        
