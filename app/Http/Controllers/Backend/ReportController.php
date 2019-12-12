<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\GopayTransaction;
use App\Http\Controllers\Controller;
use App\Models\VendingMachineTransaction;

class ReportController extends Controller
{
    public function gopayTransaction()
    {
        $view = view('backend.report.gopay-transaction');
        $view->list_transaction =  GopayTransaction::orderBy('created_at', 'desc')->paginate(50);
        return $view;
    }

    public function transaction()
    {
        $view = view('backend.transaction.transaction');
        $view->list_transaction =  VendingMachineTransaction::search()->orderBy('created_at', 'desc')->paginate(50);
        $view->total_profit =  VendingMachineTransaction::search()->success()->sum('profit_platform');
        $view->total_transaction =  VendingMachineTransaction::search()->count();
        return $view;
    }

    public function download(Request $request) 
    {
        $employess = Employee::all();
        $content = array(array('NAMA',  'NIP', 'KARTU', 'TGL. LAHIR', 'ALAMAT', 'TELEPON', 'EMAIL', 'POSISI/JABATAN', 'BAGIAN', 'BIDANG'));
        foreach ($employess as $employes) {
            $card = CardAccess::findOrFail($employes->card_access_id);
            array_push($content, [$employes->name, $card->card_number,$employes->nip, $employes->date_of_birth, $employes->address, $employes->phone,
            $employes->email, $employes->position_in_company, $employes->district->name, $employes->area->name ]);
        }

        $file_name = 'PEGAWAI ' .date('YmdHis');
        $header = 'LAPORAN PEGAWAI ';
        self::excel($file_name, $content, $header);
    }
}
