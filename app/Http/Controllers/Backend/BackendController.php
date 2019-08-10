<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Models\VendingMachineTransaction;
use App\Http\Controllers\Controller;

class BackendController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.dashboard.index');
        $view->total_client = Client::count();
        $view->total_customer = Customer::count();
        $view->total_vending_machine = VendingMachine::count();
        $view->total_user = User::count();
        $view->total_profit =  VendingMachineTransaction::search()->success()->sum('profit_platform');
        $view->total_transaction =  VendingMachineTransaction::search()->count();
        $view->list_client = Client::all();
        return $view;
    }
}
