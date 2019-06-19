<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\VendingMachineSlot;
use App\Models\VendingMachine;
use App\Models\Inventory;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /** Get Client for Select2 */
    public function getClient(Request $request)
    {
        $page = \Input::get('page');
        $resultCount = 100;

        $offset = ($page - 1) * $resultCount;
        
        $client = Client::where('name', 'like', '%' . \Input::get('search') . '%')
            ->orderBy('name')
            ->skip($offset)
            ->take($resultCount)
            ->get(['id', \DB::raw('name AS text')]);
        
        $count = Client::get()->count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count;

        $results = [
            "results" => $client,
            "pagination" => [
                "more" => $morePages
            ]
        ];
        return response()->json($results);
    }

    /** Store data transaction */
    public function transaction(Request $request)
    {
        return ApiHelper::transaction($request);
    }

    /** Store data customer */
    public function customer(Request $request)
    {
        return ApiHelper::createCustomer($request);
    }

    /** Find customer by ID */
    public function findCustomer($identity_number)
    {
        $customer = Customer::where('identity_number', $identity_number)->first();
        if (!$customer) {
            return response()->json([
                'status' => 0,
                'data' => 'Data not found'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => $customer
        ]);
    }

    /** Find slot by alias */
    public function findSlot($alias)
    {
        $slot = VendingMachineSlot::where('alias', $alias)->first();
        if (!$slot) {
            return response()->json([
                'status' => 0,
                'data' => 'Data not found'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => $slot
        ]);
    }

    /** Find firmware */
    public function getFirmware($alias)
    {
        $vending_machine = VendingMachine::where('alias', $alias)->with('firmware')->first();
        if (!$vending_machine) {
            return response()->json([
                'status' => 0,
                'data' => 'Data not found'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => $vending_machine
        ]);
    }
}
