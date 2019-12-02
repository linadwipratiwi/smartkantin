<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Temp;
use App\Models\Category;
use App\Helpers\PosHelper;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Helpers\TempDataHelper;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index()
    {
        $temp_key = PosHelper::getTempKey();

        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $total_item = count($data);
        $total_price = 0;
        $cart = [];
        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
        }
        $cart['total_item'] = $total_item;
        $cart['total_price'] = format_price($total_price);
        
        $view = view('frontend.c.pos.index');
        $view->list_stand = VendingMachine::clientId(customer()->client->id)->stand()->get();
        $view->stand = VendingMachine::clientId(customer()->client->id)->stand()->first();
        $view->categories = Category::food()->get();
        $view->cart = $cart;
        return $view;
    }

    public function _addToCart($id)
    {
        $is_remove = \Input::get('is_remove');
        $temp_key = PosHelper::getTempKey();

        $search = TempDataHelper::searchKeyValue($temp_key, auth()->user()->id, ['item_id'], [$id]);
        $item = VendingMachineSlot::findOrFail($id);

        $quantity = 1;
        if ($search) {
            $quantity = $is_remove ? $search['quantity'] - 1 : $search['quantity'] + 1;
        }
        $data = [
            'item_id' => $id,
            'name' => $item->food->name,
            'selling_price_item' => $item->food->selling_price_vending_machine,
            'quantity' => $quantity,
            'photo' => asset($item->photo),
            'stand_id' => $item->vending_machine_id
        ];

        /** jika qty 0, hapus row */
        if ($data['quantity'] == 0) {
            $temp = Temp::findOrFail($search['rowid']);
            $temp->delete();
        } else {
            /** Jika tidak ada maka buat baru */
            $temp = $search ? Temp::findOrFail($search['rowid']) : new Temp;
            $temp->user_id = auth()->user()->id;
            $temp->name = $temp_key;
            $temp->keys = serialize($data);
            $temp->save();
        }
        
        $search = TempDataHelper::searchKeyValue($temp_key, auth()->user()->id, ['item_id'], [$id]);

        /** get semua item */
        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $total_item = count($data);
        $total_price = 0;
        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
        }
        $search['total_item'] = $total_item;
        $search['total_price'] = format_price($total_price);
        return $search;

    }
}
