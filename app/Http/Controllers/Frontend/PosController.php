<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Temp;
use App\Models\Category;
use App\Models\Customer;
use App\Helpers\PosHelper;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Helpers\TempDataHelper;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;
use App\Models\VendingMachineTransaction;

class PosController extends Controller
{
    public function index()
    {
        $temp_key = PosHelper::getTempKey();

        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $total_item = count($data);
        $total_price = 0;
        $stand_active = null;

        $cart = [];
        $stand_id = [];
        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
            $stand_active = VendingMachine::find($value['stand_id']);
            if (!in_array($value['stand_id'], $stand_id)) {
                array_push($stand_id, $value['stand_id']);
            }
        }
        $cart['stand_id'] = $stand_id;
        $cart['total_item'] = $total_item;
        $cart['total_price'] = format_price($total_price);
        
        $view = view('frontend.c.pos.index');
        $view->list_stand = VendingMachine::clientId(customer()->client->id)->stand()->get();
        // $view->stand = VendingMachine::clientId(customer()->client->id)->stand()->get();
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
        $stand_id = [];
        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
            $stand_active = VendingMachine::find($value['stand_id']);
            if (!in_array($value['stand_id'], $stand_id)) {
                array_push($stand_id, $stand_active->id);
            }
        }
        $stand_name = VendingMachine::whereIn('id', $stand_id)->select('name')->get()->pluck('name')->toArray();
        $str_stand_name = implode(', ', $stand_name);
        
        $search['stand_name'] = $str_stand_name;
        $search['total_item'] = $total_item;
        $search['total_price'] = format_price($total_price);
        return $search;

    }

    /** cart */
    public function cart()
    {
        $temp_key = PosHelper::getTempKey();

        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $view = view('frontend.c.pos.cart');
        $view->list_cart = $data;
        return $view;
    }

    /** proses checkout */
    public function checkout()
    {
        $temp_key = PosHelper::getTempKey();
        $list_cart = TempDataHelper::get($temp_key, auth()->user()->id);

        foreach ($list_cart as $cart) {
            // $vending_machine_slot = VendingMachineSlot::findOrFail($cart['item_id']);
            // $customer = Customer::where('identity_number', $customer_identity_number)->first();

            // $client = $vending_machine_slot->vendingMachine->client;
            // $transaction = new VendingMachineTransaction;

            // $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
            // $transaction->vending_machine_slot_id = $vending_machine_slot->id;
            // $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
            // $transaction->customer_id = $customer->id;
            // $transaction->hpp = $vending_machine_slot->food ? $vending_machine_slot->food->hpp : 0;
            // $transaction->food_name = $vending_machine_slot->food ? $vending_machine_slot->food->name : null;
            // $transaction->selling_price_client = $vending_machine_slot->food ? $vending_machine_slot->food->selling_price_client : null;
            // $transaction->profit_client = $vending_machine_slot->food ? $vending_machine_slot->food->profit_client : null;
            // $transaction->profit_platform_type = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_type : null;
            // $transaction->profit_platform_percent = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_percent : null;
            // $transaction->profit_platform_value = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_value : null;
            
            // // jumlah keutungan real untuk platform. Secara default ambil dari value, namun jika profit type percent, maka dijumlah ulang
            // $transaction->profit_platform = $client->profit_platform_value;
            // if ($transaction->profit_platform_type == 'percent') {
            //     $transaction->profit_platform = $vending_machine_slot->selling_price_vending_machine * $vending_machine_slot->profit_platform_percent / 100;
            // }

            // $transaction->selling_price_vending_machine = $vending_machine_slot->food->selling_price_vending_machine;
            // $transaction->quantity = $cart['quantity'];
            // $transaction->status_transaction = 2; // set pending payment

            // /** Update flaging transaksi. Digunakan untuk Smansa */
            // $vending_machine = $transaction->vendingMachine;
            // $vending_machine->flaging_transaction = Str::random(10);;
            // $vending_machine->save();
        }
    }
}
