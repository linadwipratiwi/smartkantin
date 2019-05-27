<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Models\Customer;
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
}
