<?php

namespace App\Http\Controllers\Frontend\VendingMachine;

use App\Models\Food;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class VendingMachineSlotController extends Controller
{
    
    /** index slot */
    public function index($vending_machine_id)
    {
        $view = view('frontend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    /** update slot set food */
    public function store(Request $request, $vending_machine_id)
    {
        // store
        $slot = VendingMachineSlot::find($request->vending_machine_slot_id);
        $slot->food_id = $request->food_id;
        $slot->save();

        // show index
        $view = view('frontend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    /** form edit */
    public function edit($vending_machine_id, $id)
    {
        $view = view('frontend.vending-machine.slot._edit');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->vending_machine_slot = VendingMachineSlot::findOrFail($id);
        $view->list_food = Food::where('client_id', client()->id)->get();
        return $view;
    }

    /** form stock opname all */
    public function stockOpnameForm($vending_machine_id)
    {
        $view = view('frontend.vending-machine.slot.stock-opname-form');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->list_food = Food::where('client_id', client()->id)->get();
        return $view;
    }

    public function updateProduct(Request $request)
    {
        $product = VendingMachineSlot::findOrFail($request->id);
        $product->stock = $request->stock;
        $product->food_id = $request->food_id;
        $product->save();

        $vending_machine = $product->vendingMachine;
        $vending_machine->flaging_transaction = str_random(10);;
        $vending_machine->save();

        return 1;
    }
}
