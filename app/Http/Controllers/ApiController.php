<?php

namespace App\Http\Controllers;

use MidtransTrait;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Inventory;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransferSaldo;
use App\Models\VendingMachine;
use App\Helpers\ApiStandHelper;
use App\Models\GopayTransaction;
use App\Models\VendingMachineSlot;
use App\Models\VendingMachineTransaction;

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
        if ($request->payment_type == 'gopay') {
            return ApiHelper::gopayTransaction($request);
        } elseif ($request->payment_type == 'gopayAnonim') {
            return ApiHelper::gopayTransactionAnonim($request);
        }

        return ApiHelper::transaction($request);
    }

    /** get status transaction */
    public function statusTransaction($request)
    {
        $transaction=VendingMachineTransaction::find($request);
        if (!$transaction) {
            return response()->json([
                'status'=>0,
                'data'=>'no transaction found'
            ]);
        }
        return response()->json([
            'status'=>1,
            'data'=>$transaction->status_transaction
        ]);
    }

    /** get status topup */
    public function statusTopup($request)
    {
        $transaction=TransferSaldo::find($request);
        if (!$transaction) {
            return response()->json([
                  'status'=>0,
                  'data'=>'no transaction found'
              ]);
        }
        return response()->json([
              'status'=>1,
              'data'=>$transaction->payment_status
          ]);
    }

    /** Transaction detail */
    public function transactionDetail($id)
    {
        return VendingMachineTransaction::findOrFail($id);
    }

    /** Hadler gopay respon */
    public function gopayRespon(Request $request)
    {
        $gopay_transaction = GopayTransaction::find($request->order_id);
        if (!$gopay_transaction) {
            return null;
        }

        $refer = $gopay_transaction->refer_type::find($gopay_transaction->refer_type_id);
        if (get_class($refer) == get_class(new VendingMachineTransaction)) {
            /** jika refer, adalah vm transaksi */
            if ($request->transaction_status == 'settlement') {
                $refer->status_transaction = 1; // Lunas
                $refer->save();
                ApiHelper::updateStockTransaction($refer);

                $gopay_transaction->status = 1;
                $gopay_transaction->gopay_transaction_time = $request->transaction_time;
                $gopay_transaction->gopay_transaction_status = $request->transaction_status;
                $gopay_transaction->gopay_status_message = $request->status_message;
                $gopay_transaction->gopay_status_code = $request->status_code;
                $gopay_transaction->gopay_fraud_status = $request->fraud_status;
                $gopay_transaction->save();
            }
        }

        if (get_class($refer) == get_class(new TransferSaldo)) {
            /** jika refer, adalah vm transaksi */
            if ($request->transaction_status == 'settlement') {
                $refer->payment_status = 1; // Lunas
                $refer->save();
                
                // update saldo customer
                if ($refer->toType()) {
                    $customer = $refer->toType();
                    $customer->saldo += $gopay_transaction->gopay_gross_amount;
                    $customer->save();
                }

                $gopay_transaction->status = 1;
                $gopay_transaction->gopay_transaction_time = $request->transaction_time;
                $gopay_transaction->gopay_transaction_status = $request->transaction_status;
                $gopay_transaction->gopay_status_message = $request->status_message;
                $gopay_transaction->gopay_status_code = $request->status_code;
                $gopay_transaction->gopay_fraud_status = $request->fraud_status;
                $gopay_transaction->save();
            }
        }
    }

    /** Topup transaction */
    public function topupTransaction(Request $request)
    {
        return ApiHelper::topupTransaction($request);
    }

    /** Transaction fail */
    public function transactionFail(Request $request)
    {
        return ApiHelper::transactionFail($request);
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
    public function findSlot(Request $request)
    {
        $type = $request->type ? : 'mini';
        $alias = $request->alias ? : 'null';

        $slot = VendingMachineSlot::where('alias', $alias)->first();
        if (!$slot) {
            if ($type == 'mini') {
                return '0:Data not found';
            }

            return response()->json([
                'status' => 0,
                'data' => 'Data not found'
            ]);
        }

        if ($type == 'mini') {
            return '1:'.$slot->food_name.':'.$slot->selling_price_vending_machine;
        }

        return response()->json([
            'status' => 1,
            'data' => $slot
        ]);
    }

    /** Find firmware */
    public function getFirmware($alias)
    {
        $vending_machine = VendingMachine::where('alias', $alias)->with(['firmware', 'ui'])->first();
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

    /** Get stock per vending */
    public function getStock($vending_alias)
    {
        $vending = VendingMachine::where('alias', $vending_alias)->first();
        if(!$vending){
            return response()->json([
                'status' => 0,
                'data' => "vending machine not found"
            ]);
        }
        $slots=$vending->slots; 
        $hasil=[];
        foreach($slots as $slot){
            $vendingSlot=VendingMachineSlot::find($slot->id);
            $food= $vendingSlot->food;
            $slotjson= json_decode($slot, true);
            $slotjson['photo2']=$food->photo;
            $hasil[]=$slotjson;
        }
        return response()->json([
            'status' => 1,
            'data' => $hasil
        ]);
    }

    /** Get flag transaction */
    public function getFlagTransaction($vending_alias)
    {
        $vending = VendingMachine::where('alias', $vending_alias)->first();
        return response()->json([
            'status' => 1,
            'code' => $vending ? $vending->flaging_transaction : null
        ]);
    }

    /**
     * STAND API
     */

    /** Get All stock in client */
    public function getStockAllVending($username)
    {
        return ApiStandHelper::getStockAllVending($username);
    }

    /** Stand transaction */
    public function standTransaction(Request $request)
    {
        return ApiStandHelper::transaction($request);
    }

    /** Get flag semua VM di client */
    public function getFlagTransactionClient($username)
    {
        return ApiStandHelper::getFlagClient($username);
    }
}
