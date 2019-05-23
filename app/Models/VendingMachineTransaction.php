<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendingMachineTransaction extends Model
{
    protected $table = 'vending_machine_transactions';   
    public $timestamps = true;

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendigMachine', 'vending_machine_id');
    }

    public function vendingMachineSlot()
    {
        return $this->belongsTo('App\Models\VendigMachineSlot', 'vending_machine_slot_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
