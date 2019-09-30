<?php

namespace App\Http\Controllers\Frontend\VendingMachine;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class VendingMachineSlotController extends Controller
{
    
    public function index($vending_machine_id)
    {
        $view = view('frontend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function create($vending_machine_id)
    {
        $view = view('frontend.vending-machine.slot._create');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function store(Request $request, $vending_machine_id)
    {
        // store
        AdminHelper::createVendingMachineSlotByClient($request);

        // show index
        $view = view('frontend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function edit($vending_machine_id, $id)
    {
        $view = view('frontend.vending-machine.slot._edit');
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
        return redirect('front/vending-machine');
    }

    public function stockOpnameForm($vending_machine_id)
    {
        $view = view('frontend.vending-machine.slot.stock-opname-form');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function updateProduct(Request $request)
    {

        $product = VendingMachineSlot::findOrFail($request->id);
        $product->stock = $request->stock;
        $product->food_name = $request->food_name;
        $product->selling_price_client = $request->price;
        $product->selling_price_vending_machine = $request->price_platform;
        $product->selling_price_client = $request->price_client;
        $product->hpp = $request->hpp;
        $product->save();

        $vending_machine = $product->vendingMachine;
        $vending_machine->flaging_transaction = str_random(10);;
        $vending_machine->save();

        return 1;
    }
}
