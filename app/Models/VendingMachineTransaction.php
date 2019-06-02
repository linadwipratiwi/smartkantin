<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Helpers\DateHelper;

class VendingMachineTransaction extends Model
{
    protected $table = 'vending_machine_transactions';   
    public $timestamps = true;

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'vending_machine_id');
    }

    public function vendingMachineSlot()
    {
        return $this->belongsTo('App\Models\VendingMachineSlot', 'vending_machine_slot_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function scopeSearch($q)
    {
        $type = \Input::get('type');
        if ($type == 'today') {
            $q->whereDate('created_at', Carbon::today());
        }

        if ($type == 'yesterday') {
            $q->whereDate('created_at', Carbon::yesterday()->format('Y-m-d'));
        }

        if ($type == 'month') {
            $q->whereMonth('created_at', date('m'));
            $q->whereYear('created_at', date('Y'));
        }

        if ($type == 'select-month') {
            $month = \Input::get('month');
            
            $q->whereMonth('created_at', $month);
            $q->whereYear('created_at', date('Y'));
        }

        if ($type == 'custom') {
            $date = explode('-', \Input::get('date'));
            $date_start = DateHelper::formatDB(trim($date[0]), 'start');
            $date_end = DateHelper::formatDB(trim($date[1]), 'end');

            $q->whereBetween('created_at', [$date_start, $date_end]);
            
        }

        return $q;
    }
}
