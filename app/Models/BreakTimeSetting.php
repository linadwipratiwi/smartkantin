<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakTimeSetting extends Model
{
    protected $table = 'break_time_setting';
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
}
