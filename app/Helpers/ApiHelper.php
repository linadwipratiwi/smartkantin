<?php

namespace App\Helpers;

use App\Helpers;
use App\Models\Customer;
use App\Models\VendingMachineSlot;

class ApiHelper
{
    public static function transaction($request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $slot_alias = $request->input('slot_alias');

        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) return false;

        /** Cek slot vending machine */
        $vending_machine_slot = VendingMachineSlot::where('alias', $slot_alias)->first();
        if (!$vending_machine_slot) return false;

        

    }

    public static function createCustomer($request)
    {
        $name = $request->input('name');
        $identity_type = $request->input('identity_type');
        $identity_number = $request->input('identity_number');
        $vending_machine_alias = $request->input('vending_machine_alias');

        
        $vending_machine = VendingMachine::where('alias', $vending_machine_alias)->first();
        if (!$vending_machine) {
            return json_encode([
                'status' => 0,
                'data' => 'Vending machine not found'
            ]);
        }

        $customer = Customer::where('identity_number', $identity_number)->first();
        if ($customer) {
            return json_encode([
                'status' => 1, // return true
                'data' => $customer
            ]);
        }

        $customer = new Customer;
        $customer->name = $name;
        $customer->identity_type = $identity_type;
        $customer->identity_number = $identity_number;
        $customer->register_at_client = $vending_machine->client_id;
        $customer->register_at_vending_machine_id = $vending_machine->id;
        $customer->save();
        
        return json_encode([
            'status' => 1, // return true
            'data' => $customer
        ]);
    }
}