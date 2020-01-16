<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Customer;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\TransferSaldo;
use App\Http\Controllers\Controller;
use App\Models\VendingMachineTransaction;

class OtherController extends Controller
{
    public function withdraw()
    {
        $client_id = \Input::get('client_id');

        $view = view('backend.other.withdraw');
        $view->list_client = Client::all();
        $view->list_topup = $client_id ? TransferSaldo::search()->fromClient($client_id)->whereNull('withdraw_at')->orderBy('created_at', 'desc')->get() : null;
        $view->total_topup = $client_id ? TransferSaldo::search()->fromClient($client_id)->whereNull('withdraw_at')->orderBy('created_at', 'desc')->sum('saldo') : 0;

        return $view;
    }

    public function withdrawProcess(Request $request)
    {
        $id = $request->id;
        $transfer_saldo = TransferSaldo::whereIn('id', $id)->update(['withdraw_at' => Carbon::now()]);

        toaster_success('withdraw success');
        return redirect('other/withdraw');
    }

    public function pakMahfud()
    {
        return view('backend.other.pak-mahfud');
    }

    public function pakMahfudExport()
    {
        $date = explode('-', \Input::get('date'));
        $date_start = DateHelper::formatDB(trim($date[0]), 'start');
        $date_end = DateHelper::formatDB(trim($date[1]), 'end');
        
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
        $date_range = [$date_start, $date_end];
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
            'sisa_saldo' => format_price($sisa_saldo),
            'sisa_saldo_pens' => format_price($sisa_saldo_pens),
            'total_topup' => format_price($total_topup),
            'saldo_kantin' => format_price($saldo_kantin),
            'total_transaksi' => format_price($total_transaksi),
            'saldo_pens' => format_price($saldo_pens),
            'total_transaksi_penjualan' => format_price($total_transaksi_penjualan),
            'profit_kantin_mesin' => '@500 ' .format_price($profit_kantin_mesin),
            'pak_mahfud' => format_price($saldo_kantin - $profit_kantin_mesin)
        ];

        $pdf = \PDF::loadView('backend.other.pak-mahfud-pdf', $data);
        return $pdf->stream();
    }

    
}
