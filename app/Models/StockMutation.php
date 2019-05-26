<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $table = 'stock_mutations';   
    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'vending_machine_id');
    }

    public function vendingMachineSlot()
    {
        return $this->belongsTo('App\Models\VendingMachineSlot', 'vending_machine_slot_id');
    }
}
