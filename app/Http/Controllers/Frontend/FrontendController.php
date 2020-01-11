<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\GrafikHelper;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;
use App\Models\VendingMachineTransaction;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $type = \Input::get('type');
        $view = view('frontend.dashboard.index');
        $view->total_transaction = VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)
            ->select('vending_machine_transactions.*')->search()->where('vending_machine_transactions.client_id', client()->id)->count();
        $view->total_customer = Customer::count();
        $view->year = date('Y');
        $view->month = date('m');
        $view->graph_transaction = GrafikHelper::grafikTransaction(date('Y'), date('m'), $type);
        $view->total_vending_machine = VendingMachine::where('type', $type)->where('client_id', client()->id)->count();
        $view->total_profit = VendingMachineTransaction::joinVendingMachine()->where('vending_machines.type', $type)
            ->select('vending_machine_transactions.*')->search()->where('vending_machine_transactions.client_id', client()->id)->sum('profit_client');
        $view->url_ajax_call = url('front/load-grafik-transaction?type='.$type);

        return $view;
    }

    public function loadGrafikTransaction(Request $request)
    {
        $year = \Input::get('year');
        $month = \Input::get('month');
        $type = \Input::get('type');
        $view = view('frontend.dashboard._chart-morris');
        $view->graph_transaction = GrafikHelper::grafikTransaction($year, $month, $type);
        $view->year = $year;
        $view->month = $month;
        $view->url_ajax_call = url('front/load-grafik-transaction?type='.$type);

        return $view;
    }
}
