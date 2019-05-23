<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendingMachine extends Model
{
    protected $table = 'vending_machines';   
    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function slots()
    {
        return $this->hasMany('App\Models\VendingMachineSlot', 'vending_machine_id');
    }
}
