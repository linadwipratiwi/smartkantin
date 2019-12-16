<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firmware extends Model
{
    protected $table = 'firmwares';   
    public $timestamps = true;

    public function scopeFirmware($q)
    {
        $q->where('type', 'firmware');
    }

    public function scopeUi($q)
    {
        $q->where('type', 'ui');
    }
}
