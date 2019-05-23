<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\Customer;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.customer.index');
        $view->customers = Customer::paginate(25);
        return $view;
    }

    public function create()
    {
        return view('backend.customer.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('customer/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createCustomer($request);
        toaster_success('create form success');
        return redirect('customer');
    }

    public function edit($id)
    {
        $view = view('backend.customer.edit');
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
        return redirect('customer');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $delete = AdminHelper::delete($customer);
        
        toaster_success('delete form success');
        return redirect('customer');
    }
}
