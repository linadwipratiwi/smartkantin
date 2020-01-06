<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Models\Customer;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\TransferSaldo;
use App\Models\GopayTransaction;
use App\Http\Controllers\Controller;

class TopupController extends Controller
{
    public function index()
    {
        $view = view('frontend.c.topup.index');
        $view->list_topup = TransferSaldo::where('to_type', get_class(new Customer))->where('to_type_id', customer()->id)->orderBy('created_at', 'desc')->paginate(100);

        return $view;
    }

    public function create()
    {
        $view = view('frontend.c.topup.create');
        return $view;
    }

    public function store(Request $request)
    {
        $request['customer_identity_number'] = customer()->identity_number;
        $request['saldo'] = format_db($request->saldo);

        $respon = ApiHelper::topupTransaction($request);
        if ($respon['status_code'] != 201) {
            toaster_error($respon['status_message']);
            return redirect('c/topup/create');
        }

        $gopay_transaction = GopayTransaction::where('refer_type', get_class(new TransferSaldo))->where('refer_type_id', $respon['id'])->first();
        $gopay_transaction->url_qrcode = $respon['actions'][0]['url'];
        $gopay_transaction->url_deeplink = $respon['actions'][1]['url'];
        $gopay_transaction->url_get_status = $respon['actions'][2]['url'];
        $gopay_transaction->url_cancel = $respon['actions'][3]['url'];
        $gopay_transaction->save();

        toaster_success('Silahkan bayar dengan Gopay');
        return redirect('c/topup/pending/'.$gopay_transaction->id);
    }

    public function pending($id)
    {
        $view = view('frontend.c.topup.pending');
        $view->gopay_transaction = GopayTransaction::findOrFail($id);
        return $view;
    }
}
