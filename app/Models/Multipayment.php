<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multipayment extends Model
{
    protected $table = 'multipayments';   
    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function stand()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'stand_id');
    }
}
