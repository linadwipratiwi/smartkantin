<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VendingMachineTransaction;

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
        return $this->belongsTo('App\Models\Firmware', 'version_firmware_id');
    }

    public function ui()
    {
        return $this->belongsTo('App\Models\Firmware', 'version_ui_id');
    }

    public function slots()
    {
        return $this->hasMany('App\Models\VendingMachineSlot', 'vending_machine_id');
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\StockMutation', 'vending_machine_id')->orderBy('created_at', 'desc');
    }

    public function userVendingMachine()
    {
        return $this->hasMany('App\Models\UserVendingMachine', 'vending_machine_id');
    }

    public function scopeClientId($q, $client_id)
    {
        $q->where('client_id', $client_id);
    }

    public function scopeVending($q)
    {
        $q->where('type', 1);
    }

    public function scopeStand($q)
    {
        $q->where('type', 2);
    }

    public function totalTransactionToday()
    {
        return VendingMachineTransaction::whereDate('created_at', '=', date('Y-m-d'))->where('vending_machine_id', $this->id)->count();
    }
}
