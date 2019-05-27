<?php

namespace App\Http\Controllers\Frontend\VendingMachine;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class VendingMachineController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.vending-machine.index');
        $view->vending_machines = VendingMachine::paginate(25);
        return $view;
    }

    public function create()
    {
        return view('frontend.vending-machine.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('vending-machine/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createVendingMachine($request);
        toaster_success('create form success');
        return redirect('vending-machine');
    }

    public function edit($id)
    {
        $view = view('frontend.vending-machine.edit');
        $view->vending_machine = VendingMachine::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachine($request, $id);
        toaster_success('create form success');
        return redirect('vending-machine');
    }

    public function destroy($id)
    {
        $vending_machine = VendingMachine::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('vending-machine');
    }
}
