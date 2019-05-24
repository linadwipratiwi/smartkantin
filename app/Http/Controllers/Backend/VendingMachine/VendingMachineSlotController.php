<?php

namespace App\Http\Controllers\Backend\VendingMachine;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class VendingMachineSlotController extends Controller
{
    
    public function index($vending_machine_id)
    {
        $view = view('backend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function create($vending_machine_id)
    {
        $view = view('backend.vending-machine.slot._create');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function store(Request $request, $vending_machine_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('vending-machine/create')->withErrors($validator)
                ->withInput();
        }

        // store
        AdminHelper::createVendingMachineSlot($request);

        // show index
        $view = view('backend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function edit($id)
    {
        $view = view('backend.vending-machine.edit');
        $view->vending_machine = VendingMachine::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachineSlot($request, $id);
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
