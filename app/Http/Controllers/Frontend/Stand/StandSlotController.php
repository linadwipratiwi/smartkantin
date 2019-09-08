<?php

namespace App\Http\Controllers\Frontend\Stand;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class StandSlotController extends Controller
{
    
    public function index($vending_machine_id)
    {
        $view = view('frontend.stand.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function create($vending_machine_id)
    {
        $view = view('frontend.stand.slot._create');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function store(Request $request, $vending_machine_id)
    {
        // store
        AdminHelper::createVendingMachineSlotByClient($request);

        // show index
        $view = view('frontend.stand.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function edit($vending_machine_id, $id)
    {
        $view = view('frontend.stand.slot._edit');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->vending_machine_slot = VendingMachineSlot::findOrFail($id);

        return $view;
    }

    public function destroy($vending_machine_id, $id)
    {
        $vending_machine = VendingMachineSlot::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('front/stand');
    }
}
