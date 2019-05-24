<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';   
    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'register_at_client_id');
    }

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'register_at_vending_machine_id');
    }
}
