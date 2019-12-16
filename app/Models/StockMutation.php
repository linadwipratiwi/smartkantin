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

    public function food()
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

    public function typeTransaction($type=null)
    {
        if ($this->type == 'stock_mutation') {
            if ($type == 'excel') {
                return 'Stock Opname';
            }
            return '<span class="label label-info capitalize-font inline-block ml-10">Stock Opname</span>';
        }

        if ($this->type == 'transaction_fail') {
            if ($type == 'excel') {
                return 'Transaction Fail';
            }
            return '<span class="label label-warning capitalize-font inline-block ml-10">Transaction Fail</span>';
        }
        
        if ($type == 'excel') {
            return 'Transaction';
        }
        return '<span class="label label-success capitalize-font inline-block ml-10">Transaction</span>';
    }
}
