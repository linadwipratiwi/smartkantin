<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransferSaldo;
use App\Models\VendingMachineTransaction;


class DanaTransaction extends Model
{
    //
    protected $table = 'dana_transactions';
    public $timestamps = true;

    public function transactionType()
    {
        $refer = $this->refer_type::find($this->refer_type_id);

        if (get_class($refer) == get_class(new VendingMachineTransaction)) {
            return 'Transaksi Vending Mesin';
        }

        return 'Topup';
    }

    public function getCustomer()
    {
        $refer = $this->refer_type::find($this->refer_type_id);

        if (get_class($refer) == get_class(new VendingMachineTransaction)) {
            return $refer->customer ? $refer->customer->name : '-';
        }

        if (get_class($refer) == get_class(new TransferSaldo)) {
            return $refer->toType() ? $refer->toType()->name : '-';
        }

        return '-';
    }

    public function getStatus()
    {
        return $this->status == 0 ? 'Belum diproses' : 'Sudah diproses';
    }
}
