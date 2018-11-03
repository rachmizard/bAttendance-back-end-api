<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'karyawan_id', 'verifikasi_id', 'status', 'alasan', 'created_at', 'updated_at'
    ];
    protected $dates = ['created_at'];

    // public function getCreatedAtAttribute()
    // {
    //     return \Carbon\Carbon::parse($this->attributes['created_at'])
    //        ->format('H:i:s');
    // }

    public function karyawan()
    {
    	return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function verifikasi()
    {	
    	return $this->belongsTo(Verifikasi::class, 'verifikasi_id', 'id');
    }

}
