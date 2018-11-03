<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    protected $table = 'master_jam';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'start', 'tolerance', 'end', 'status'
    ];
}
