<?php

namespace App\Http\Controllers\Frontend\Stand;

use App\Models\Food;
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
        $view = view('frontend.stand.slot.create');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->categories = Category::food()->get();
        $view->list_food = Food::where('client_id', client()->id)->get();
        return $view;
    }

    public function store(Request $request, $vending_machine_id)
    {
        FrontHelper::createProduct($request);
        return redirect('front/stand');
    }

    public function destroy($vending_machine_id, $id)
    {
        $product = VendingMachineSlot::findOrFail($id);
        $stock_mutation = StockMutation::find($product->ref_stock_mutation_id);
        $stock_mutation ? $stock_mutation->delete() : '';

        $vending_machine = $product->vendingMachine;
        $vending_machine->flaging_transaction = str_random(10);
        ;
        $vending_machine->save();

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
        $product->stock = format_db($request->stock);
        $product->save();

        FrontHelper::creteStockFromCreateProduct($product);

        $vending_machine = $product->vendingMachine;
        $vending_machine->flaging_transaction = str_random(10);
        ;
        $vending_machine->save();

        return 1;
    }
}
