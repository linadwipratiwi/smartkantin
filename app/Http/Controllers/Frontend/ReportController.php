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
        $type = \Input::get('vending_type');

        $view = view('frontend.report.transaction');
        $view->list_transaction =  VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)->search()
            ->select('vending_machine_transactions.*')->where('vending_machine_transactions.client_id', client()->id)->orderBy('created_at', 'desc')->paginate(50);
        $view->total_profit =  VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)->search()
            ->success()->select('vending_machine_transactions.*')->where('vending_machine_transactions.client_id', client()->id)->sum('profit_client');
        $view->total_transaction =  VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)->search()
            ->select('vending_machine_transactions.*')->where('vending_machine_transactions.client_id', client()->id)->count();
        $view->total_transaction_failed =  VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)->search()
            ->failed()->select('vending_machine_transactions.*')->where('vending_machine_transactions.client_id', client()->id)->count();
        $view->total_transaction_success =  VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)->search()
            ->success()->select('vending_machine_transactions.*')->where('vending_machine_transactions.client_id', client()->id)->count();
        return $view;
    }

    public function topup()
    {
        $view = view('frontend.report.topup');

        $view->list_topup = TransferSaldo::search()->fromClient(client()->id)->orderBy('created_at', 'desc')->get();
        $view->total_topup = TransferSaldo::search()->fromClient(client()->id)->orderBy('created_at', 'desc')->sum('saldo');
        return $view;
    }
    
    public function withdraw()
    {
        $view = view('frontend.report.withdraw');

        $view->total_sudah_diambil = TransferSaldo::fromClient(client()->id)->withdraw()->sum('saldo');
        $view->total_belum_diambil = TransferSaldo::fromClient(client()->id)->notWithdraw()->sum('saldo');

        return $view;
    }
}
