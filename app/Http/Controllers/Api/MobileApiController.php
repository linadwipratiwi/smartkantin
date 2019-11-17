<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Inventory;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Helpers\ApiStandHelper;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class MobileApiController extends Controller
{
    /** Find customer by ID */
    public function findCustomer($identity_number)
    {
        $customer = Customer::where('identity_number', $identity_number)->first();
        if (!$customer) {
            return response()->json([
                'status' => 0,
                'msg' => 'Data not found'
            ]);
        }

        $customer->status = 1;
        $customer->msg = '';
        return response()->json(
            $customer
        );
    }
}
