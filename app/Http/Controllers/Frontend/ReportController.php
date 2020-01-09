<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\TransferSaldo;
use App\Http\Controllers\Controller;
use App\Models\VendingMachineTransaction;

class ReportController extends Controller
{
    public function transaction()
    {
        $view = view('frontend.report.transaction');
        $view->list_transaction =  VendingMachineTransaction::search()->where('client_id', client()->id)->orderBy('created_at', 'desc')->paginate(50);
        $view->total_profit =  VendingMachineTransaction::search()->success()->where('client_id', client()->id)->sum('profit_client');
        $view->total_transaction =  VendingMachineTransaction::search()->where('client_id', client()->id)->count();
        $view->total_transaction_failed =  VendingMachineTransaction::search()->failed()->where('client_id', client()->id)->count();
        $view->total_transaction_success =  VendingMachineTransaction::search()->success()->where('client_id', client()->id)->count();
        return $view;
    }

    public function topup()
    {
        $view = view('frontend.report.topup');

        $view->list_topup = TransferSaldo::search()->fromClient(client()->id)->orderBy('created_at', 'desc')->get();
        $view->total_topup = TransferSaldo::search()->fromClient(client()->id)->orderBy('created_at', 'desc')->sum('saldo');
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

    public function withdraw()
    {
        $view = view('frontend.report.withdraw');

        $view->total_sudah_diambil = TransferSaldo::fromClient(client()->id)->withdraw()->sum('saldo');
        $view->total_belum_diambil = TransferSaldo::fromClient(client()->id)->notWithdraw()->sum('saldo');

        return $view;
    }
}
