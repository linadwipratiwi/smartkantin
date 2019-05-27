<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';   
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vendingMachines()
    {
        return $this->hasMany('App\Models\VendingMachine', 'client_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
