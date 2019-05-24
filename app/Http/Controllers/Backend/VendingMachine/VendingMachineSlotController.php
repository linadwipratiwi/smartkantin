<?php

namespace App\Http\Controllers\Backend\VendingMachine;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
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

    public function edit($vending_machine_id, $id)
    {
        $view = view('backend.vending-machine.slot._edit');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->vending_machine_slot = VendingMachineSlot::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $vending_machine_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachineSlot($request);
        toaster_success('create form success');
        return redirect('vending-machine');
    }

    public function destroy($vending_machine_id, $id)
    {
        $vending_machine = VendingMachineSlot::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('vending-machine');
    }
}
