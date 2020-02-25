<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\VendingMachineTransaction;

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

    public function food()
    {
        return $this->belongsTo('App\Models\Food', 'food_id');
    }

    public function scopeJoinVendingMachine($q)
    {
        $q->join('vending_machines', 'vending_machines.id', '=', $this->table.'.vending_machine_id');
    }

    public function scopeJoinCustomer($q)
    {
        $q->join('customers', 'customers.id', '=', $this->table.'.customer_id');
    }

    /**
     * Failed
     * Gagal
     */
    public function scopeFailed($q)
    {
        $q->where('vending_machine_transactions.status_transaction', 0);
    }

    /** 
     * Sukses
     * Stok sudah diambil dan saldo sudah terpotong
     */
    public function scopeSuccess($q)
    {
        $q->where('vending_machine_transactions.status_transaction', 1);
    }

    /**
     * Pending
     * Stok belum diambil dan saldo belum terpotong
     */
    public function scopePending($q)
    {
        $q->where('vending_machine_transactions.status_transaction', 2);
    }

    /**
     * Success not delivered
     * Stok belum diambil dan saldo sudah terpotong
     */
    public function scopeSuccessNotDelivered($q)
    {
        $q->where('vending_machine_transactions.status_transaction', 3);
    }

    public function scopeSearch($q)
    {
        $type = \Input::get('type');
        $status = \Input::get('status_transaction');
        if ($type == 'today' || $type == null) {
            $q->whereDate('vending_machine_transactions.created_at', Carbon::today());
        }

        if ($type == 'yesterday') {
            $q->whereDate('vending_machine_transactions.created_at', Carbon::yesterday()->format('Y-m-d'));
        }

        if ($type == 'month') {
            $q->whereMonth('vending_machine_transactions.created_at', date('m'));
            $q->whereYear('vending_machine_transactions.created_at', date('Y'));
        }

        if ($type == 'select-month') {
            $month = \Input::get('month');
            
            $q->whereMonth('vending_machine_transactions.created_at', $month);
            $q->whereYear('vending_machine_transactions.created_at', date('Y'));
        }

        if ($type == 'custom') {
            $date = explode('-', \Input::get('date'));
            $date_start = DateHelper::formatDB(trim($date[0]), 'start');
            $date_end = DateHelper::formatDB(trim($date[1]), 'end');

            $q->whereBetween('vending_machine_transactions.created_at', [$date_start, $date_end]);
        }

        if (!($status == null || $status == 'all')) {
            $q->where('vending_machine_transactions.status_transaction', $status);
        }

        return $q;
    }

    public function isPreorder()
    {
        if ($this->is_preorder) {
            return '<span class="label label-info">Preorder</span>';
        }
    }
    public function status($type=null)
    {
        if ($this->status_transaction == 0) {
            if ($type == 'excel') {
                return 'Failed';
            }
            return '<span class="label label-info capitalize-font inline-block ml-10">Failed</span>';
        }

        if ($this->status_transaction == 1) {
            if ($type == 'excel') {
                return 'Success with delivered';
            }
            return '<span class="label label-success capitalize-font inline-block ml-10">Success with delivered</span>';
        }

        if ($this->status_transaction == 2) {
            if ($type == 'excel') {
                return 'Payment Pending';
            }
            return '<span class="label label-warning capitalize-font inline-block ml-10">Payment Pending</span>';
        }

        if ($this->status_transaction == 3) {
            if ($type == 'excel') {
                return 'Success with not delivered';
            }
            return '<span class="label label-success capitalize-font inline-block ml-10">Success with not delivered</span>';
        }
    }

    /** generate number transaction */
    public static function generateNumber()
    {
        # format : TAHUN/BULAN/KODE Pelanggan/JAM:DETIK
        return 'POS-'.date('Y').'-'.date('m').'-'.customer()->id.'-'.date('his');
    }

       /** generate number transaction */
       public static function generateCustomNumber($customerid)
       {
           # format : TAHUN/BULAN/KODE Pelanggan/JAM:DETIK
           return 'POS-'.date('Y').'-'.date('m').'-'.$customerid.'-'.date('his');
       }
   
    public function scopeSearchByCustomer($q, $stand_id)
    {
        $search = \Input::get('search');
        $q->joinCustomer()
            ->where(function ($que) use ($search) {
                if ($search) {
                    $que->where('customers.name', 'like', '%'.$search.'%');
                }
            })
            ->where('vending_machine_transactions.vending_machine_id', $stand_id)
            ->where('vending_machine_transactions.status_transaction', 3)
            ->whereDate('vending_machine_transactions.preorder_date', Carbon::today());
        
    }
}
