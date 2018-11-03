<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterFilter extends Model
{
    protected $table = 'master_filters';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'tgl_history',
    ];
    protected $dates = ['tgl_history'];

    public function getTglHistoryRekapAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['tgl_history'])
           ->format('Y-m-d');
    }
}
