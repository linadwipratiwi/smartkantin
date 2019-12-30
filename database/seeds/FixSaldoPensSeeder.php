<?php

use App\Models\Client;
use App\Models\Customer;
use App\Models\TransferSaldo;
use Illuminate\Database\Seeder;
use App\Models\VendingMachineTransaction;

class FixSaldoPensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** sisa saldo */
        $sisa_saldo = Customer::where('register_at_client_id', 3)->sum('saldo');
        
        /** saldo pens */
        $sisa_saldo_pens = Customer::where('register_at_client_id', 3)->sum('saldo_pens');

        /** Total topup */
        $list_transfer_saldo = TransferSaldo::where('from_type', get_class(new Client))
            ->where('from_type_id', 3)
            // ->whereDate('created_at', '>=', '2019-12-06 08:00:00')
            ->get();
        $total_topup = 0;
        foreach ($list_transfer_saldo as $transfer_saldo) {
            $customer = $transfer_saldo->to_type::find($transfer_saldo->to_type_id);
            if (!$customer) continue;
            $total_topup += $transfer_saldo->saldo;
        }

        /** saldo kantin mesin */
        $saldo_kantin = $total_topup - $sisa_saldo;

        /** Total Transaksi */
        $date_range = ['2019-11-11', '2019-12-16 15:00:00'];
        $total_transaksi = VendingMachineTransaction::where('vending_machine_id', 16)
            ->where('status_transaction', 1)
            ->whereBetween('created_at', $date_range)
            ->sum('selling_price_vending_machine');
        
        $total_transaksi_penjualan = VendingMachineTransaction::where('vending_machine_id', 16)
            ->where('status_transaction', 1)
            ->whereBetween('created_at', $date_range)
            ->count();
        $profit_kantin_mesin = $total_transaksi_penjualan * 500;
        
        /** saldo pens */
        $saldo_pens = $total_transaksi - $saldo_kantin;
        $data = [
            'sisa saldo' => format_price($sisa_saldo),
            'sisa saldo pens' => format_price($sisa_saldo_pens),
            'total topup' => format_price($total_topup),
            'saldo kantin transaksi' => format_price($saldo_kantin),
            'total transaksi' => format_price($total_transaksi),
            'saldo pens transaksi' => format_price($saldo_pens),
            'total transaksi penjualan' => format_price($total_transaksi_penjualan),
            'profit kantin mesin' => '@500 ' .format_price($profit_kantin_mesin),
            'untuk pak mahfud' => format_price($saldo_kantin - $profit_kantin_mesin)
        ];

        dd($data);
    }
}
