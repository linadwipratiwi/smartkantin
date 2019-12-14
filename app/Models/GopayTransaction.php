<?php

namespace App\Models;

use App\Models\TransferSaldo;
use Illuminate\Database\Eloquent\Model;
use App\Models\VendingMachineTransaction;

class GopayTransaction extends Model
{
    protected $table = 'gopay_transactions';   
    public $timestamps = true;

    public static function init($class, $refer_id, $gross_amount)
    {
        $new = new GopayTransaction;
        $new->refer_type = get_class($class);
        $new->refer_type_id = $refer_id;
        $new->gopay_gross_amount = $gross_amount;
        $new->save();
        return $new;
    }

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
            return $refer->customer->name;
        }

        if (get_class($refer) == get_class(new TransferSaldo)) {
            return $refer->toType()->name;
        }

        return '-';
    }

    public function getStatus()
    {
        return $this->status == 0 ? 'Belum diproses' : 'Sudah diproses';
    }
}
