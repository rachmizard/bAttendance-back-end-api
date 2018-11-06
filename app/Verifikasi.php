<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $table = 'verifikasis';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'status', 'pin'
    ];
    protected $dates = ['created_at', 'updated_at'];

    // protected $dateFormat = '';


    public function absen()
    {
    	return $this->hasMany(Absen::class, 'id');
    }
}
