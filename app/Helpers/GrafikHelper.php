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
    public static function grafikTransaction($year, $from="")
    {
        $array = [];
        $total_transaction = 0;
        $total_transaction_failed = 0;
        $total_transaction_success = 0;
        for ($i=1; $i<=12; $i++) {
            $transaction = VendingMachineTransaction::where('client_id', client()->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->count();
            
            $transaction_failed = VendingMachineTransaction::where('client_id', client()->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->failed()
                ->count();
            
            $transaction_success = VendingMachineTransaction::where('client_id', client()->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
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