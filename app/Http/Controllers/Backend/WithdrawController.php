<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\TransferSaldo;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{
    public function index()
    {
        $client_id = \Input::get('client_id');

        $view = view('backend.withdraw.index');
        $view->list_client = Client::all();
        $view->list_topup = $client_id ? TransferSaldo::search()->fromClient($client_id)->whereNull('withdraw_at')->orderBy('created_at', 'desc')->get() : null;
        $view->total_topup = $client_id ? TransferSaldo::search()->fromClient($client_id)->whereNull('withdraw_at')->orderBy('created_at', 'desc')->sum('saldo') : 0;

        return $view;
    }

    public function process(Request $request)
    {
        $id = $request->id;
        $transfer_saldo = TransferSaldo::whereIn('id', $id)->update(['withdraw_at' => Carbon::now()]);

        toaster_success('withdraw success');
        return redirect('withdraw');
    }
}
