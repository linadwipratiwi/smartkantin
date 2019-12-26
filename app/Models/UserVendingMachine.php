<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVendingMachine extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'user_vending_machine';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'vending_machine_id');
    }

    public function scopeJoinUser($q)
    {
        $q->join('users', 'users.id', '=', $this->table.'.user_id');
    }
}
