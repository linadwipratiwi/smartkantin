<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Models\Customer;
use App\Models\TransferSaldo;
use App\Helpers\ExcelHelper;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.customer.index');
        $view->customers = Customer::clientId(client()->id)->orderBy('created_at', 'desc')->paginate(25);
        return $view;
    }

    public function create()
    {
        return view('frontend.customer.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('front/customer/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createCustomer($request);
        toaster_success('create form success');
        return redirect('front/customer');
    }

    public function edit($id)
    {
        $view = view('frontend.customer.edit');
        $view->customer = Customer::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createCustomer($request, $id);
        toaster_success('create form success');
        return redirect('front/customer');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $delete = AdminHelper::delete($customer);
        
        toaster_success('delete form success');
        return redirect('front/customer');
    }

    public function download(Request $request) 
    {
        $list_customer = Customer::clientId(client()->id)->orderBy('created_at', 'desc')->get();
        $content = array(array('NAMA', 'JENIS IDENTITAS', 'NOMOR IDENTITAS', 'REGISTER DI VENDING MACHINE', 'TANGGAL DAFTAR'));
        foreach ($list_customer as $customer) {
            array_push($content, [$customer->name, $customer->identity_type, $customer->identity_number, $customer->vendingMachine->name, $customer->created_at ? date_format_view($customer->created_at) : '-']);
        }

        $file_name = 'REPORT CUSTOMER' .date('YmdHis');
        $header = 'REPORT CUSTOMER';
        ExcelHelper::excel($file_name, $content, $header);
    }

     /** List Saldo */
     public function _topupIndex($id) 
     {
         $view = view('frontend.customer.topup._index');
         $view->customer = Customer::findOrFail($id);
         $view->list_topup = TransferSaldo::where('to_type', get_class(new Customer))->where('to_type_id', $id)->orderBy('created_at', 'desc')->paginate(10);
         return $view;
     }
 
     /** Create topup */
     public function _topupCreate($id)
     {
         $view = view('frontend.customer.topup._create');
         $view->customer = Customer::findOrFail($id);
 
         return $view;
     }
 
     /** Store topup */
     public function _topupStore(Request $request)
     {
         $topup = AdminHelper::createTopupCustomer($request);
         $view = view('frontend.customer.topup._index');
         $view->customer = Customer::findOrFail($topup->to_type_id);
         $view->list_topup = TransferSaldo::where('to_type', get_class(new Customer))->where('to_type_id', $topup->to_type_id)->orderBy('created_at', 'desc')->paginate(10);
 
         return $view;
    }

    /** Export Topup ke Customer */
    public function export($id) 
    {
        $customer = Customer::findOrFail($id);
        $list_topup = TransferSaldo::fromClient(client()->id)->toCustomer($id)->orderBy('created_at', 'desc')->get();
        $content = array(array('NO', 'TANGGAL', 'JUMLAH TOPUP', 'PELANGGAN', 'JENIS IDENTITAS', 'NOMER IDENTITAS', 'TOPUP OLEH'));
        foreach ($list_topup as $row => $topup) {
            $customer = $topup->to_type::find($topup->to_type_id);
            array_push($content, [++$row, date_format_view($topup->created_at), format_price($topup->saldo),
                $customer->name, $customer->identity_type, $customer->identity_number, $topup->createdBy->name]);
        }

        $file_name = 'RIWAYAT TOPUP KE  ' .$customer->name;
        $header = $customer->name;
        ExcelHelper::excel($file_name, $content, $header);
    }
}
