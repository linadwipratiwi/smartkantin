<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Exceptions\AppException;
use Bican\Roles\Models\Permission;
use App\Models\VendingMachineTransaction;

class GrafikHelper
{
    /**
     * Grafik total transaction
     */
    public static function grafikTransaction($year, $month, $vending_type=null)
    {
        $array = [];
        $total_transaction = 0;
        $total_transaction_failed = 0;
        $total_transaction_success = 0;
        if (!$month) {
            for ($i=1; $i<=12; $i++) {
                $transaction = VendingMachineTransaction::joinVendingMachine()->where('vending_machine_transactions.client_id', client()->id)
                    ->where(function ($q) use ($vending_type) {
                        if ($vending_type) {
                            $q->where('vending_machines.type', $vending_type);
                        }
                    })
                    ->whereMonth('vending_machine_transactions.created_at', $i)
                    ->whereYear('vending_machine_transactions.created_at', $year)
                    ->select('vending_machine_transactions.*')
                    ->count();
                
                $transaction_failed = VendingMachineTransaction::joinVendingMachine()->where('vending_machine_transactions.client_id', client()->id)
                    ->where(function ($q) use ($vending_type) {
                        if ($vending_type) {
                            $q->where('vending_machines.type', $vending_type);
                        }
                    })
                    ->whereMonth('vending_machine_transactions.created_at', $i)
                    ->whereYear('vending_machine_transactions.created_at', $year)
                    ->select('vending_machine_transactions.*')
                    ->failed()
                    ->count();
                
                $transaction_success = VendingMachineTransaction::joinVendingMachine()->where('vending_machine_transactions.client_id', client()->id)
                    ->where(function ($q) use ($vending_type) {
                        if ($vending_type) {
                            $q->where('vending_machines.type', $vending_type);
                        }
                    })
                    ->whereMonth('vending_machine_transactions.created_at', $i)
                    ->whereYear('vending_machine_transactions.created_at', $year)
                    ->select('vending_machine_transactions.*')
                    ->success()
                    ->count();
                
                $total_transaction += $transaction;
                $total_transaction_failed += $transaction_failed;
                $total_transaction_success += $transaction_success;
    
                $date = $year.'-'.$i.'-01';
                $date = new Carbon($date);
    
                $period = $date->localeMonth;
                array_push($array, [
                    'transaction' => $transaction,
                    'transaction_failed' => $transaction_failed,
                    'transaction_success' => $transaction_success,
                    'period' => $period,
                ]);
            }
        } else {
            $list_days = DateHelper::getAllDaysByMonth($month, $year);
            for ($i=0; $i<count($list_days); $i++) {
                $transaction = VendingMachineTransaction::joinVendingMachine()->where('vending_machine_transactions.client_id', client()->id)
                    ->where(function ($q) use ($vending_type) {
                        if ($vending_type) {
                            $q->where('vending_machines.type', $vending_type);
                        }
                    })
                    ->whereDate('vending_machine_transactions.created_at', $list_days[$i]['date'])
                    ->count();
                
                $transaction_failed = VendingMachineTransaction::joinVendingMachine()->where('vending_machine_transactions.client_id', client()->id)
                    ->where(function ($q) use ($vending_type) {
                        if ($vending_type) {
                            $q->where('vending_machines.type', $vending_type);
                        }
                    })
                    ->whereDate('vending_machine_transactions.created_at', $list_days[$i]['date'])
                    ->failed()
                    ->count();
                
                $transaction_success = VendingMachineTransaction::joinVendingMachine()->where('vending_machine_transactions.client_id', client()->id)
                    ->where(function ($q) use ($vending_type) {
                        if ($vending_type) {
                            $q->where('vending_machines.type', $vending_type);
                        }
                    })
                    ->whereDate('vending_machine_transactions.created_at', $list_days[$i]['date'])
                    ->success()
                    ->count();
                
                $total_transaction += $transaction;
                $total_transaction_failed += $transaction_failed;
                $total_transaction_success += $transaction_success;
    
                $date = new Carbon($list_days[$i]['date']);
    
                $period = $date->format('d');
                array_push($array, [
                    'transaction' => $transaction,
                    'transaction_failed' => $transaction_failed,
                    'transaction_success' => $transaction_success,
                    'period' => $period,
                ]);
            }
        }
        

        return [
            'grafik' => json_encode($array),
            'label' => json_encode(['Total Transaksi', 'Gagal', 'Berhasil']),
            'keys' => json_encode(['transaction', 'transaction_failed', 'transaction_success']),
            'total_transaction' => $total_transaction,
            'total_transaction_failed' => $total_transaction_failed,
            'total_transaction_success' => $total_transaction_success,
        ];
    }

    /**
     * Grafik rasio dosen yang mengajukan paten per tahun
     */
    public static function grafikRasioPaten($year)
    {
        $total_yang_mengajukan = Paten::whereYear('created_at', $year)->groupBy('user_id')->count();
        $total_pegawai = Employee::count();
        $total = $total_yang_mengajukan + $total_pegawai;
        $total_yang_tidak_mengajukan = $total_pegawai - $total_yang_mengajukan;

        $rasio_yang_mengajukan = $total_yang_mengajukan / $total * 100;
        $rasio_yang_tidak_mengajukan = $total_pegawai  / $total * 100;

        return [
            'total_yang_mengajukan' => $total_yang_mengajukan,
            'total_pegawai' => $total_pegawai,
            'total_yang_tidak_mengajukan' => $total_yang_tidak_mengajukan,
            'rasio_yang_mengajukan' => $rasio_yang_mengajukan,
            'rasio_yang_tidak_mengajukan' => $rasio_yang_tidak_mengajukan,
            'total' => $total,
        ];
    }
}
