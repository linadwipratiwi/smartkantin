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

    public function firmware()
    {
        return $this->belongsTo('App\Models\Firmware', 'firmware_id');
    }

    public function slots()
    {
        return $this->hasMany('App\Models\VendingMachineSlot', 'vending_machine_id');
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\StockMutation', 'vending_machine_id')->orderBy('created_at', 'desc');
    }

    public function scopeClientId($q, $client_id)
    {
        $q->where('client_id', $client_id);
    }
}
