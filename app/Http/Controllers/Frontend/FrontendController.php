<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.dashboard.index');
        $view->total_transaction = 12000;
        $view->total_customer = Customer::count();
        $view->total_vending_machine = VendingMachine::count();
        $view->total_profit = 185500;
        return $view;
    }
}
