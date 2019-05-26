<?php

namespace App\Http\Controllers\Backend\VendingMachine;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StockMutation;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Helpers\AdminHelper;

class StockMutationController extends Controller
{
    public function index($vending_machine_id)
    {
        $view = view('backend.vending-machine.stock._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function create($vending_machine_id)
    {
        $view = view('backend.vending-machine.stock._create');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function show($vending_machine_id, $id)
    {
        $vending_machine_slot = VendingMachineSlot::findOrFail($id);
        return $vending_machine_slot;
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
        AdminHelper::createVendingMachineStock($request);

        // show index
        $view = view('backend.vending-machine.stock._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function edit($vending_machine_id, $id)
    {
        $view = view('backend.vending-machine.stock._edit');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->vending_machine_slot = VendingMachineSlot::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $vending_machine_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachineStock($request);
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
