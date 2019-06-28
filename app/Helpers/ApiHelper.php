<?php

namespace App\Helpers;

use App\Helpers;
use App\Models\Customer;
use App\Helpers\ApiHelper;
use App\Models\StockMutation;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Models\VendingMachineTransaction;

class ApiHelper
{
    public static function transaction($request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $slot_alias = $request->input('slot_alias');
        $type = $request->input('type') ? : 'mini'; // normal, mini

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
        $vending_machine_slot = VendingMachineSlot::where('alias', $slot_alias)->first();
        if (!$vending_machine_slot) {
            if ($type == 'mini') {
                return "0:Vending Machine Slot not found";
            }

            return json_encode([
                'status' => 0,
                'data' => 'Vending Machine Slot not found'
            ]);
        }

        if ($vending_machine_slot->stock < 1) {
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
        $transaction->quantity = 1;
        $transaction->status_transaction = 1;
        try {
            $transaction->save();
            self::updateStockTransaction($transaction);
            \DB::commit();

            $transaction = VendingMachineTransaction::where('id', $transaction->id)->with('customer')->first();
            if ($type == 'mini') {
                return '1:'.$transaction->customer->name;
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

    public static function updateStockTransaction($transaction)
    {
        // tambah record stock opname
        $stock_mutation = new StockMutation;
        $stock_mutation->vending_machine_id = $transaction->vending_machine_id;
        $stock_mutation->vending_machine_slot_id = $transaction->vending_machine_slot_id;
        $stock_mutation->stock = -1; // stok dikurang 1
        $stock_mutation->type = 'transaction';
        $stock_mutation->food_name = $transaction->food_name;
        $stock_mutation->hpp = $transaction->hpp;
        $stock_mutation->selling_price_client = $transaction->selling_price_client;
        $stock_mutation->created_by = 1; // system
        $stock_mutation->save();

        // update stock di vending machine
        $vending_machine_slot = VendingMachineSlot::find($transaction->vending_machine_slot_id);
        $vending_machine_slot->stock = $vending_machine_slot->stock - 1; // stok di vending machine dikurang 1
        $vending_machine_slot->save();
    }

    public static function createCustomer($request)
    {
        $name = $request->input('name');
        $identity_type = $request->input('identity_type') ? : 'ktp';
        $identity_number = $request->input('identity_number');
        $email = $request->input('email') ? : null;
        $phone = $request->input('phone')  ? : null;
        $address = $request->input('address') ? : null;
        $vending_machine_alias = $request->input('vending_machine_alias');
        
        $type = $request->input('type') ? : 'mini';
        
        $vending_machine = VendingMachine::where('alias', $vending_machine_alias)->first();
        if (!$vending_machine) {
            if ($type == 'mini') {
                return '0:Vending machine not found';
            }

            return json_encode([
                'status' => 0,
                'data' => 'Vending machine not found'
            ]);
        }

        $customer = Customer::where('identity_number', $identity_number)->first();
        if ($customer) {

            if ($type == 'mini') {
                return '1:'.$customer->name.':'.$customer->id.':'.$customer->phone;
            }

            return json_encode([
                'status' => 1, // return true
                'data' => $customer
            ]);
        }

        $customer = new Customer;
        $customer->name = $name;
        $customer->identity_type = $identity_type;
        $customer->identity_number = $identity_number;
        $customer->email = $email;
        $customer->address = $address;
        $customer->phone = $phone;
        $customer->register_at_client_id = $vending_machine->client_id;
        $customer->register_at_vending_machine_id = $vending_machine->id;
        $customer->save();
        
        if ($type == 'mini') {
            return '1:'.$customer->name.':'.$customer->id.':'.$customer->phone;
        }

        return json_encode([
            'status' => 1, // return true
            'data' => $customer
        ]);
    }
}