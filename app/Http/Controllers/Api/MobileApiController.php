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
        $vending_machine->flaging_transaction = Str::random(10);;
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
        } catch (\Exception $e){
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
}
