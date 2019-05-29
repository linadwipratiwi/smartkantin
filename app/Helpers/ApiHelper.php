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
}