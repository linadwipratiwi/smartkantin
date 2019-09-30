<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\VendingMachineTransaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.dashboard.index');
        $view->total_transaction = VendingMachineTransaction::search()->where('client_id', client()->id)->count();;
        $view->total_customer = Customer::count();
        $view->total_vending_machine = VendingMachine::where('client_id', client()->id)->count();
        $view->total_profit = VendingMachineTransaction::search()->where('client_id', client()->id)->sum('profit_client');;
        return $view;
    }
}
