<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendingMachineSlot extends Model
{
    protected $table = 'vending_machine_slots';   
    public $timestamps = false;

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendigMachine', 'vending_machine_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
}
