<?php

namespace App\Helpers;

use App\User;
use App\Models\Client;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Models\StockMutation;
use App\Models\PermissionUser;
use App\Models\VendingMachine;
use App\Exceptions\AppException;
use App\Models\VendingMachineSlot;
use Bican\Roles\Models\Permission;
use App\Models\VendingMachineTransaction;

class ApiStandHelper
{
    /** 
     * Digunakan untuk mengambil stock di semua vending yang ada di client
     */
    public static function getStockAllVending($username)
    {
        /** check user */
        $user = User::where('username', $username)->first();
        if (!$user) {
            return json_encode([
                'status' => 0,
                'data' => 'User not found'
            ]);
        }

        /** check client */
        $client = Client::where('user_id', $user->id)->first();
        if (!$user) {
            return json_encode([
                'status' => 0,
                'data' => 'Client not found'
            ]);
        }

        /** get all stand */
        $list_all_stand = VendingMachine::stand()->where('client_id', $client->id)->with(['slots'])->get();
        return json_encode([
            'status' => 1,
            'data' => $list_all_stand
        ]);
    }

    /** 
     * Digunakan untuk mengambil flag di semua vending yang ada di client
     */
    public static function getFlagClient($username)
    {
        /** check user */
        $user = User::where('username', $username)->first();
        if (!$user) {
            return json_encode([
                'status' => 0,
                'data' => 'User not found'
            ]);
        }

        /** check client */
        $client = Client::where('user_id', $user->id)->first();
        if (!$user) {
            return json_encode([
                'status' => 0,
                'data' => 'Client not found'
            ]);
        }

        /** get all stand */
        $list_all_stand = VendingMachine::select('flaging_transaction')->stand()->where('client_id', $client->id)->get();
        return json_encode([
            'status' => 1,
            'data' => $list_all_stand
        ]);
    }

    public static function transaction($request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $item_id = $request->input('item_id');
        $quantity = $request->input('quantity');
        $type = $request->input('type') ? : 'normal'; // normal, mini

        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) {
            if ($type == 'mini') {
                return "0:Identity number customer not found";
            }

            // normal
            return json_encode([
                'status' => 0,
                'data' => 'Identity number customer not found'
            ]);
        }

        /** Cek slot vending machine */
        $vending_machine_slot = VendingMachineSlot::find($item_id);
        if (!$vending_machine_slot) {
            if ($type == 'mini') {
                return "0:Item not found";
            }

            return json_encode([
                'status' => 0,
                'data' => 'Item not found'
            ]);
        }

        if ($vending_machine_slot->stock < $quantity) {
            if ($type == 'mini') {
                return '0:Stock '.$vending_machine_slot->food_name .' is empty';
            }

            return json_encode([
                'status' => 0,
                'data' => 'Stock '.$vending_machine_slot->food_name .' is empty'
            ]);
        }

        \DB::beginTransaction();
        $transaction = new VendingMachineTransaction;
        $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
        $transaction->vending_machine_slot_id = $vending_machine_slot->id;
        $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
        $transaction->customer_id = $customer->id;
        $transaction->hpp = $vending_machine_slot->hpp;
        $transaction->food_name = $vending_machine_slot->food_name;
        $transaction->selling_price_client = $vending_machine_slot->selling_price_client;
        $transaction->profit_client = $vending_machine_slot->profit_client;
        $transaction->profit_platform_type = $vending_machine_slot->profit_platform_type;
        $transaction->profit_platform_percent = $vending_machine_slot->profit_platform_percent;
        $transaction->profit_platform_value = $vending_machine_slot->profit_platform_value;
        
        // jumlah keutungan real untuk platform. Secara default ambil dari value, namun jika profit type percent, maka dijumlah ulang
        $transaction->profit_platform = $vending_machine_slot->profit_platform_value;
        if ($transaction->profit_platform_type == 'percent') {
            $transaction->profit_platform = $vending_machine_slot->selling_price_vending_machine * $vending_machine_slot->profit_platform_percent / 100;
        }

        $transaction->selling_price_vending_machine = $vending_machine_slot->selling_price_vending_machine;
        $transaction->quantity = $quantity;
        $transaction->status_transaction = 1;

        /** Update flaging transaksi. Digunakan untuk Smansa */
        $vending_machine = $transaction->vendingMachine;
        $vending_machine->flaging_transaction = Str::random(10);;
        $vending_machine->save();

        try {
            $transaction->save();
            self::updateStockTransaction($transaction);
            \DB::commit();

            $transaction = VendingMachineTransaction::where('id', $transaction->id)->with('customer')->first();
            if ($type == 'mini') {
                return '1:'.$transaction->id.':'.$transaction->customer->name;
            }

            return json_encode([
                'status' => 1,
                'data' => $transaction
            ]);

        } catch (\Throwable $th) {
            if ($type == 'mini') {
                return '0:Transaction failed';
            }

            return json_encode([
                'status' => 0,
                'data' => 'Transaction failed'
            ]);
        }

    }

    /** 
     * Update stock setelah transaksi
     */
    public static function updateStockTransaction($transaction)
    {
        // tambah record stock opname
        $stock_mutation = new StockMutation;
        $stock_mutation->vending_machine_id = $transaction->vending_machine_id;
        $stock_mutation->vending_machine_slot_id = $transaction->vending_machine_slot_id;
        $stock_mutation->stock = $transaction->quantity * -1; // stok dikurang quantity
        $stock_mutation->type = 'transaction';
        $stock_mutation->food_name = $transaction->food_name;
        $stock_mutation->hpp = $transaction->hpp;
        $stock_mutation->selling_price_client = $transaction->selling_price_client;
        $stock_mutation->created_by = 1; // system
        $stock_mutation->save();

        // update stock di vending machine
        $vending_machine_slot = VendingMachineSlot::find($transaction->vending_machine_slot_id);
        $vending_machine_slot->stock = $vending_machine_slot->stock - $transaction->quantity; // stok di vending machine dikurang quantity
        $vending_machine_slot->save();
    }

}