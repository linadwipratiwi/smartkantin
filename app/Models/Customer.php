<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';   
    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'register_at_client_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'register_at_vending_machine_id');
    }

    public function scopeClientId($q, $client_id)
    {
        $q->where('register_at_client_id', $client_id);
    }

    public function createRandomUser()
    {
        if ($this->user) return;
        $this->default_password = $this->id.str_random(10);
        $this->save();

        $user = new User;
        $user->name = $this->name ? : str_random(10);
        $user->email = $this->email ? : null;
        $user->username = $this->default_password;
        $user->password = bcrypt($this->default_password);
        $user->save();

        $this->user_id = $user->id;
        $this->save();

        $user->attachRole(3);
    }

    public function showDefaultAccount()
    {
        $html = '<br>Username: '.$this->default_password.'<br>Password: '.$this->default_password;
        return $html;
    }
}
