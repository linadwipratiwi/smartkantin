<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Temp;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Helpers\TempDataHelper;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index()
    {
        $view = view('frontend.c.pos.index');
        $view->list_stand = VendingMachine::clientId(customer()->client->id)->stand()->get();
        $view->list_food = VendingMachineSlot::whereNotNull('category_id')->get();
        $view->categories = Category::food()->get();

        return $view;
    }

    public function _addToCart($id)
    {
        $temp_key = 'customer.basket.'.customer()->id;

        $search = TempDataHelper::searchKeyValue($temp_key, auth()->user()->id, ['item_id'], [$id]);
        $item = VendingMachineSlot::findOrFail($id);

        $quantity = 1;
        if ($search) {
            $quantity = $search['quantity'] + 1;
        }
        $data = [
            'item_id' => $id,
            'name' => $item->food_name,
            'quantity' => $quantity,
            'photo' => asset($item->photo),
        ];

        /** Jika tidak ada maka buat baru */
        if (!$search) {
            
            $temp = new Temp;
            $temp->user_id = auth()->user()->id;
            $temp->name = $temp_key;
            $temp->keys = serialize($data);
            $temp->save();
        }

        dd($temp);

    }
}
