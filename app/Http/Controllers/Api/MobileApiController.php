<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Inventory;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use App\Models\Multipayment;
use Illuminate\Http\Request;
use App\Models\TransferSaldo;
use App\Models\VendingMachine;
use App\Helpers\ApiStandHelper;
use App\Exceptions\AppException;
use App\Models\VendingMachineSlot;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VendingMachineTransaction;
use App\User;

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

    /** Transaction */
    public static function transaction(Request $request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $slot_alias = $request->input('slot_alias');
        $type = $request->input('type') ? : 'normal'; // normal, mini
        $payment_type = $request->input('payment_type') == 'gopay' ? 'gopay' : 'saldo'; // normal, mini

        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) {
            if ($type == 'mini') {
                return "0:Identity number customer not found";
            }

            // normal
            return json_encode([
                'status' => 0,
                'msg' => 'Identity number customer not found'
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
                'msg' => 'Vending Machine Slot not found'
            ]);
        }

        /** Cek saldo */
        $saldo = $customer->saldo;
        $saldo_pens = $customer->saldo_pens;
        $saldo_total = $saldo + $saldo_pens;

        $selling_price_vending_machine = $vending_machine_slot->selling_price_vending_machine;
        if ($payment_type == 'saldo') {
            /** jika saldo total kurang dari harga jual */
            if ($saldo_total < $selling_price_vending_machine) {
                return json_encode([
                    'status' => 0,
                    'msg' => 'Saldo Anda tidak mencukupi'
                ]);
            }
        }

        if ($vending_machine_slot->stock < 1) {
            if ($type == 'mini') {
                return '0:Stock '.$vending_machine_slot->food_name .' is empty';
            }

            return json_encode([
                'status' => 0,
                'msg' => 'Stock '.$vending_machine_slot->food_name .' is empty'
            ]);
        }

        \DB::beginTransaction();
        $client = $vending_machine_slot->vendingMachine->client;

        $transaction = new VendingMachineTransaction;
        $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
        $transaction->vending_machine_slot_id = $vending_machine_slot->id;
        $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
        $transaction->customer_id = $customer->id;
        $transaction->hpp = $vending_machine_slot->hpp;
        $transaction->food_name = $vending_machine_slot->food_name;
        $transaction->selling_price_client = $vending_machine_slot->selling_price_client;
        $transaction->profit_client = $vending_machine_slot->profit_client;
        $transaction->profit_platform_type = $client->profit_platform_type;
        $transaction->profit_platform_percent = $client->profit_platform_percent;
        $transaction->profit_platform_value = $client->profit_platform_value;
        
        // jumlah keutungan real untuk platform. Secara default ambil dari value, namun jika profit type percent, maka dijumlah ulang
        $transaction->profit_platform = $client->profit_platform_value;
        if ($transaction->profit_platform_type == 'percent') {
            $transaction->profit_platform = $vending_machine_slot->selling_price_vending_machine * $vending_machine_slot->profit_platform_percent / 100;
        }

        $transaction->selling_price_vending_machine = $vending_machine_slot->selling_price_vending_machine;
        $transaction->quantity = 1;
        $transaction->status_transaction = 1;

        /** Update flaging transaksi. Digunakan untuk Smansa */
        $vending_machine = $transaction->vendingMachine;
        $vending_machine->flaging_transaction = Str::random(10);
        ;
        $vending_machine->save();

        
        try {
            $transaction->save();
            /** Kurangi saldo customer */
            $customer = $transaction->customer;
            $saldo_pens_akhir = $saldo_pens - $selling_price_vending_machine;
            if ($saldo_pens_akhir < 0) {
                /** saldo pens kurang, maka saldo pens di set 0, dan diambilkan dari saldo utama */
                $customer->saldo_pens = 0;

                $biaya_kekurangan = $saldo_pens_akhir * -1; // untuk mepositifkan
                $customer->saldo = $saldo - $biaya_kekurangan;
            } else {
                /** saldo pens masih sisa */
                $customer->saldo_pens = $saldo_pens_akhir;
            }

            $customer->save();

            ApiHelper::updateStockTransaction($transaction);
            \DB::commit();

            $transaction = VendingMachineTransaction::where('id', $transaction->id)->first();
            $transaction->status = 1;
            $transaction->msg = 'success';
            return json_encode(
                $transaction
            );
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 0,
                'msg' => 'Transaction failed'
            ]);
        }
    }

    /** Transaction multipayment */
    public static function multipayment(Request $request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $amount = $request->input('amount');
        $payment_type = $request->input('payment_type'); // in : out
        $notes = $request->input('notes');

        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) {
            return json_encode([
                'status' => 0,
                'msg' => 'Identity number customer not found'
            ]);
        }

        /** Cek saldo */
        $saldo = $customer->saldo;
        $saldo_pens = $customer->saldo_pens;
        $saldo_total = $saldo + $saldo_pens;

        /** jika saldo total kurang dari harga jual */
        if ($saldo_total < $amount) {
            return json_encode([
                'status' => 0,
                'msg' => 'Saldo Anda tidak mencukupi'
            ]);
        }


        $multipayment = new Multipayment;
        $multipayment->customer_id = $customer->id;
        $multipayment->amount = $amount;
        $multipayment->payment_type = $payment_type;
        $multipayment->save();

        /** pengurangan saldo */
        $saldo_pens_akhir = $saldo_pens - $amount;
        if ($saldo_pens_akhir < 0) {
            /** saldo pens kurang, maka saldo pens di set 0, dan diambilkan dari saldo utama */
            $customer->saldo_pens = 0;

            $biaya_kekurangan = $saldo_pens_akhir * -1; // untuk mepositifkan
            $customer->saldo = $saldo - $biaya_kekurangan;
        } else {
            /** saldo pens masih sisa */
            $customer->saldo_pens = $saldo_pens_akhir;
        }

        $customer->save();

        $multipayment = Multipayment::where('id', $multipayment->id)->first();
        $multipayment->status = 1;
        $multipayment->msg = 'success';
        return json_encode(
            $multipayment
        );
    }

    /** Topup */
    public static function topup(Request $request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $saldo = $request->input('saldo');

        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) {
            return json_encode([
                'status' => 0,
                'msg' => 'Identity number customer not found'
            ]);
        }
        
        DB::beginTransaction();
        $transfer_saldo = new TransferSaldo;
        $transfer_saldo->from_type = get_class(new Client);
        $transfer_saldo->from_type_id = $customer->client->id;
        $transfer_saldo->saldo = $saldo;
        $transfer_saldo->to_type = get_class(new Customer);
        $transfer_saldo->to_type_id = $customer->id;
        $transfer_saldo->created_by = $customer->client->user->id;
        try {
            $transfer_saldo->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        // update saldo customer
        $customer = Customer::findOrFail($customer->id);
        $customer->saldo += $saldo;
        $customer->save();
        DB::commit();

        return json_encode(
            $customer
        );
    }

    /**Login */
    public static function login(Request $request)
    {
        $username= $request->input('username');
        $password=$request->input('password');
        
        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
        $hasher = app('hash');
        if ($hasher->check($password, $user->password)) {
            // Success

            $client=Client::where('user_id',$user->id)->first();
            $vending=VendingMachine::where('client_id',$client->id)->first();
            $vendingAlias=$vending->alias;
            return response()->json([
                'status' => 1,
                 'alias'=>$vendingAlias,
                'msg' => 'access granted'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
    }

    public static function billpaytransaction($request)
    {
        $transaction_id = $request;
        $transaction = VendingMachineTransaction::find($transaction_id);
        /**cek status transaction */
        $status_transaction = $transaction->status_transaction;
        if ($status_transaction == 1) {
            return response()->json([
                'status' => 0,
                'msg' => 'transaction already paid'
            ]);
        } elseif ($status_transaction == 3) {
            return response()->json([
                'status'=> 0,
                'msg' => 'transaction has expired'
            ]);
        }
          
        if (!$transaction) {
            return response()->json([
                'status' => 0,
                'msg' => "transaction not found"
            ]);
        }
        $harga = $transaction->selling_price_vending_machine;
        $quantity = $transaction->quantity;
        $hargaTotal = $harga* $quantity;
        $customer_id = $transaction-> customer_id;
        $customer = Customer::findOrFail($customer_id);
        $saldo = $customer->saldo;
        
        /**cek saldo apakahmencukupi */
        if ($saldo < $hargaTotal) {
            return response()->json([
                'status'=>0,
                'msg' => 'not enough saldo'
            ]);
        }
        $saldo = $saldo-$harga;
        $customer->saldo = $saldo;
        $customer->save();
        $transaction->status_transaction = 1;
        $transaction->save();
        DB::commit();
        return true;
    }

    public static function billpayment(Request $request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $vending_machine_alias = $request->input('vending_machine_alias');
      
        /*cek vending machine*/
        $vending_machine = VendingMachine::Where('alias', $vending_machine_alias)->first();
        if (!$vending_machine) {
            return response()->json([[
                'status' => 0,
                'msg' => 'vending machine not found'
            ]]);
        }         
        $vending_machine_id = $vending_machine->id;
        $vending_machine_client_id = $vending_machine->client_id;
      
        $customer = Customer::where(['identity_number'=> $customer_identity_number,
                                     'register_at_client_id'=> $vending_machine_client_id        
        ])->first();
        if (!$customer) {
            return response()->json([[
                'status' => 0,
                'msg' => 'customer identity not found'
            ]]);
        }

        $customer_id = $customer->id;
      
        $where = [
            'customer_id' => $customer_id,
            'vending_machine_id' => $vending_machine_id,
            'status_transaction' => '2'
        ];
        $transactions = VendingMachineTransaction::where($where)->get();
 
        $hasil=[];
        $hargaTotal=0;
        foreach ($transactions as $transaction) {
            $status_transaction = $transaction->status_transaction;
            $harga = $transaction->selling_price_vending_machine;
            $quantity = $transaction->quantity;
            $hargaTotal = $hargaTotal+($harga* $quantity);
            $hasil[] = $transaction;
        }

        if (!$hasil) {
            return response()->json([
                "status"=>0,
                "msg"=>"no bill found"
            ]);
        }

    
        $saldo = $customer->saldo;
        
        /**cek saldo apakahmencukupi */
        if ($saldo<$hargaTotal) {
            return response()->json([
                'status'=>0,
                'msg' => 'not enough saldo'
            ]);
        }
        $saldo = $saldo-$hargaTotal;
        $customer->saldo = $saldo;
        $customer->save();
        DB::commit();
        foreach ($transactions as $transaction) {
            $transaction->status_transaction=1;
            $transaction->save();
            DB::commit();
        }
        
        $customer_= json_decode($customer, true);
        $customer_['msg']="success";
        $customer_['status']=1;

        return response()->json(
            $customer_
        );
    }

    /**Bill check */
    public static function billcheck(Request $request)
    {
        $customer_identity_number= $request->input('customer_identity_number');
        $vending_machine_alias= $request->input('vending_machine_alias');
       
        /*cek vending machine*/
        $vending_machine=VendingMachine::Where('alias', $vending_machine_alias)->first();
        if (!$vending_machine) {
            return response()->json([[
                'status' => 0,
                'msg' => 'vending machine not found'
            ]]);
        }
        $vending_machine_id=$vending_machine->id;
        $vending_machine_client_id=$vending_machine->client_id;
        
        //    cek customer 
        $customer= Customer::where(['identity_number' => $customer_identity_number,
                                    'register_at_client_id'=>$vending_machine_client_id])->first();
        if (!$customer) {
            return response()->json([[
                'status' => 0,
                'msg' => 'customer identity not found'
            ]]);
        }
        $customer_id=$customer->id;

        $transactions=VendingMachineTransaction::Where(['customer_id'=>$customer_id,
                                    'vending_machine_id'=>$vending_machine_id,
                                    'status_transaction'=>'2'])->get();

        $hasil=[];
        foreach ($transactions as $data) {
            $text= json_decode($data, true);
            $text['customer_name']=$customer->name;
            $text['customer_identity_number']=$customer->identity_number;
            $text['msg']="success";
            $hasil[]=$text;
        }
        if (!$hasil) {
            return response()->json([[
                'status' => 0,
                'msg' => 'no bill found'
            ]]);
        }
        return response()->json(
            $hasil
        );
    }

    /**All history */
    public static function allHistory($request)
    {
        $stand_alias=$request;
        
        /* cek alias */
        $stand = VendingMachine::where('alias', $stand_alias)->first();
        if (!$stand) {
            return json_encode([
                'status' => 0,
                'msg' => 'stand not found'
            ]);
        }
        /*id stand*/
        $stand_id= $stand->id;
        /** Cek customer ada apa tidak */
        $transaction = VendingMachineTransaction::where('vending_machine_id', $stand->id)->get();
        
        $hasil=[];
        if ($transaction) {
            foreach ($transaction as $data) {
                $text= json_decode($data, true);
                $text['type_transaction']="buy";
                $customer_id=$data->customer_id;
                $customer=Customer::find($customer_id);
                $text['customer_name']=$customer->name;
                $text['customer_identity_number']=$customer->customer_identity_number;
                $hasil[]=($text);
            }
        }
        $topup_transaction= TransferSaldo::where('from_type_id', $stand_id)->get();
        if ($topup_transaction) {
            foreach ($topup_transaction as $data) {
                $text= json_decode($data, true);
                $text['type_transaction']="topup";
                $customer_id= $data->to_type_id;
                $customer=Customer::find($customer_id);
                $text['customer_name']=$customer->name;
                $text['customer_identity_number']=$customer->customer_identity_number;
                $hasil[]=($text);
            }
        }

        if (!$hasil) {
            return response()->json([
                'status'=> 0,
                'msg' => 'no transaction'
            ]);
        }
        return response()->json(
            $hasil
        );
    }

   
    /**History */
    public static function history($request)
    {
        $stand_alias=$request;
        
        /* cek alias */
        $stand = VendingMachine::where('alias', $stand_alias)->first();
        if (!$stand) {
            return json_encode([
                'status' => 0,
                'msg' => 'stand not found'
            ]);
        }
        /*id stand*/
        $stand_id= $stand->id;
        /** Cek customer ada apa tidak */
        $transaction = VendingMachineTransaction::where('vending_machine_id', $stand->id)->get();
        
        $hasil=[];
        if ($transaction) {
            foreach ($transaction as $data) {
                $text= json_decode($data, true);
                $text['type_transaction']="buy";
                $customer_id=$data->customer_id;
                $customer=Customer::find($customer_id);
                $text['customer_name']=$customer->name;
                $text['customer_identity_number']=$customer->identity_number;
                $text['msg']="success";
                $hasil[]=($text);
            }
        }
        // $topup_transaction= TransferSaldo::where('from_type_id',$stand_id)->get();
        // if($topup_transaction){
        //     foreach($topup_transaction as $data){
        //         $text= json_decode($data,true);
        //         $text['type_transaction']="topup";
        //         $customer_id= $data->to_type_id;
        //         $customer=Customer::find($customer_id);
        //         $text['customer_name']=$customer->name;
        //         $text['customer_identity_number']=$customer->customer_identity_number;
        //         $hasil[]=($text);
        //     }
        // }

        if (!$hasil) {
            return response()->json([[
                'status'=> 0,
                'msg' => 'no transaction'
            ]]);
        }
        return response()->json(
            $hasil
        );
    }
}

