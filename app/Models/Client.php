<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VendingMachineTransaction;

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
        return $this->hasMany('App\Models\VendingMachine', 'client_id')->where('type', 1);
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'register_at_client_id');
    }

    public function stands()
    {
        return $this->hasMany('App\Models\VendingMachine', 'client_id')->where('type', 2);
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /** Get profit yang dibagi dari setiap transaksi. Diambil dari slot */
    public function profitShare()
    {
        $vending_machine = $this->vendingMachines->first();
        if (!$vending_machine) return null;

        $slot = $vending_machine->slots->first();
        if (!$slot) return null;

        return $slot->profitPlatform();
    }

    /** Get total income dari client */
    public function shareIncome()
    {
        return VendingMachineTransaction::search()->where('client_id', $this->id)->sum('profit_platform');
    }

    /** Get total transaksi dari client
     * @param $status [1, 2, 3] ['success', 'failed', 'all']
     */
    public function totalTransaction($status=3)
    {
        return VendingMachineTransaction::search()->where('client_id', $this->id)
            ->where(function($q) use ($status) {
                if ($status != 3) {
                    $q->where('status_transaction', $status);
                }
            })
            ->count();
    }
}
