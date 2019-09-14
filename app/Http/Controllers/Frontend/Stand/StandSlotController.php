<?php

namespace App\Http\Controllers\Frontend\Stand;

use App\Models\Category;
use App\Helpers\AdminHelper;
use App\Helpers\FrontHelper;
use Illuminate\Http\Request;
use App\Models\StockMutation;
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
        $view->categories = Category::food()->get();
        return $view;
    }

    public function store(Request $request, $vending_machine_id)
    {
        // store
        FrontHelper::createProduct($request);

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
        $view->categories = Category::food()->get();

        return $view;
    }

    public function destroy($vending_machine_id, $id)
    {
        $product = VendingMachineSlot::findOrFail($id);
        $stock_mutation = StockMutation::find($product->ref_stock_mutation_id);
        $stock_mutation ? $stock_mutation->delete() : '';
        $delete = AdminHelper::delete($product);
        
        toaster_success('delete form success');
        return redirect('front/stand');
    }

    public function stockOpnameForm($vending_machine_id)
    {
        $view = view('frontend.stand.slot.stock-opname-form');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function updateProduct(Request $request)
    {
        $product = VendingMachineSlot::findOrFail($request->id);
        $product->stock = $request->stock;
        $product->food_name = $request->food_name;
        $product->selling_price_client = format_db($request->price);
        $product->selling_price_vending_machine = format_db($request->price);
        $product->save();
        return 1;
    }
}
