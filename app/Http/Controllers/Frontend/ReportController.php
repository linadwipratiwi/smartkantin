<?php

namespace App\Http\Controllers\Frontend;

use App\Models\VendingMachineTransaction;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        $view = view('frontend.report.index');
        $view->list_transaction =  VendingMachineTransaction::search()->where('client_id', client()->id)->orderBy('created_at', 'desc')->paginate(50);
        $view->total_profit =  VendingMachineTransaction::search()->where('client_id', client()->id)->sum('profit_client');
        $view->total_transaction =  VendingMachineTransaction::search()->where('client_id', client()->id)->count();
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
