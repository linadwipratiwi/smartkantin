<?php

namespace App\Helpers;

use App\Helpers;
use App\Models\Customer;
use App\Helpers\ApiHelper;
use App\Midtrans\Midtrans;
use App\Models\KartuSakti;
use Illuminate\Support\Str;
use App\Models\StockMutation;
use App\Models\TransferSaldo;
use App\Models\VendingMachine;
use App\Models\GopayTransaction;
use App\Models\VendingMachineSlot;
use App\Models\VendingMachineTransaction;

class ApiHelper
{
    public function __construct()
    {
        Midtrans::$serverKey = 'Mid-server-JB3rTclaX2JoBbV_2K4UACD0';
        Midtrans::$isProduction = true;
    }

    /** Vending Machine Transaction by Saldo */
    public static function transaction($request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $slot_alias = $request->input('slot_alias');
        $type = $request->input('type') ? : 'normal'; // normal, mini
        $payment_type = $request->input('payment_type') == 'gopay' ? 'gopay' : 'saldo'; // normal, mini
        
        /** Cek apakah kartu sakti */
        $kartu_sakti = KartuSakti::where('card_number', $customer_identity_number)->first();
        if ($kartu_sakti) {
            return json_encode([
                'status' => 2,
                'data' => 'maintenance'
            ]);
        }
        
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

        /** Cek saldo */
        $saldo = $customer->saldo;
        $saldo_pens = $customer->saldo_pens;
        $saldo_total = $saldo + $saldo_pens;

        $selling_price_vending_machine = $vending_machine_slot->food->selling_price_vending_machine;
        if ($payment_type == 'saldo') {
            /** jika saldo total kurang dari harga jual */
            if ($saldo_total < $selling_price_vending_machine) {
                return json_encode([
                    'status' => 0,
                    'data' => 'Saldo Anda tidak mencukupi'
                ]);
            }
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
        $client = $vending_machine_slot->vendingMachine->client;

        $transaction = new VendingMachineTransaction;
        $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
        $transaction->vending_machine_slot_id = $vending_machine_slot->id;
        $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
        $transaction->customer_id = $customer->id;
        $transaction->hpp = $vending_machine_slot->food ? $vending_machine_slot->food->hpp : 0;
        $transaction->food_name = $vending_machine_slot->food ? $vending_machine_slot->food->name : null;
        $transaction->selling_price_client = $vending_machine_slot->food ? $vending_machine_slot->food->selling_price_client : null;
        $transaction->profit_client = $vending_machine_slot->food ? $vending_machine_slot->food->profit_client : null;
        $transaction->profit_platform_type = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_type : null;
        $transaction->profit_platform_percent = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_percent : null;
        $transaction->profit_platform_value = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_value : null;
        
        // jumlah keutungan real untuk platform. Secara default ambil dari value, namun jika profit type percent, maka dijumlah ulang
        $transaction->profit_platform = $client->profit_platform_value;
        if ($transaction->profit_platform_type == 'percent') {
            $transaction->profit_platform = $selling_price_vending_machine * $vending_machine_slot->food->profit_platform_percent / 100;
        }

        $transaction->selling_price_vending_machine = $selling_price_vending_machine;
        $transaction->quantity = 1;
        $transaction->status_transaction = 1;

        /** Update flaging transaksi. Digunakan untuk Smansa */
        $vending_machine = $transaction->vendingMachine;
        $vending_machine->flaging_transaction = Str::random(10);
        $vending_machine->saldo += $vending_machine_slot->food->selling_price_client;
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

    /** Vending Machine Transaction Fail */
    public static function transactionFail($request)
    {
        $transaction_id = $request->input('transaction_id');
        $type = $request->input('type') ? : 'normal'; // normal, mini

        \DB::beginTransaction();
        /**
         * Ketika terjadi gagal
         * 1. Ubah status vending machine
         * 2. kembalikan stok dengan proses stock opname
         * 3. Update stok di vending mesin
         * 4. Kembalikan saldo pelanggan + keuntungan platform
         */
        $transaction = VendingMachineTransaction::find($transaction_id);
        if (!$transaction) {
            if ($type == 'mini') {
                return "0:Vending Machine Not not found";
            }

            return json_encode([
                'status' => 0,
                'data' => 'Vending Machine Not not found'
            ]);
        }

        $transaction->status_transaction = 0; // failed
        $transaction->save();

        /** Update record dengan stok opname */
        $stock_mutation = new StockMutation;
        $stock_mutation->vending_machine_id = $transaction->vending_machine_id;
        $stock_mutation->vending_machine_slot_id = $transaction->vending_machine_slot_id;
        $stock_mutation->stock = $transaction->quantity; // stok dikurang 1
        $stock_mutation->type = 'transaction_fail';
        $stock_mutation->food_name = $transaction->food_name;
        $stock_mutation->hpp = $transaction->hpp;
        $stock_mutation->selling_price_client = $transaction->selling_price_client;
        $stock_mutation->created_by = 1; // system
        $stock_mutation->save();

        /** update stock di vending machine */
        $vending_machine_slot = VendingMachineSlot::find($transaction->vending_machine_slot_id);
        $vending_machine_slot->stock = $vending_machine_slot->stock + $transaction->quantity; // stok di vending machine ditambah 1
        $vending_machine_slot->save();

        $customer = $transaction->customer;
        $customer->saldo += $transaction->selling_price_vending_machine;
        $customer->save();
        /** Kembalikan saldo pelanggan, saldo pens dikembalikan ke saldo */

        \DB::commit();
        if ($type == 'mini') {
            return '1:'.$transaction->customer->name;
        }

        return json_encode([
            'status' => 1,
            'data' => $transaction
        ]);
    }

    /** Update stock transaction */
    public static function updateStockTransaction($transaction)
    {
        // tambah record stock opname
        $stock_mutation = new StockMutation;
        $stock_mutation->vending_machine_id = $transaction->vending_machine_id;
        $stock_mutation->vending_machine_slot_id = $transaction->vending_machine_slot_id;
        $stock_mutation->stock = $transaction->quantity * -1; // stok dikurang 1
        $stock_mutation->type = 'transaction';
        $stock_mutation->food_name = $transaction->food_name;
        $stock_mutation->hpp = $transaction->hpp;
        $stock_mutation->selling_price_client = $transaction->selling_price_client;
        $stock_mutation->created_by = 1; // system
        $stock_mutation->save();

        // update stock di vending machine
        $vending_machine_slot = VendingMachineSlot::find($transaction->vending_machine_slot_id);
        $vending_machine_slot->stock = $vending_machine_slot->stock - $transaction->quantity; // stok di vending machine dikurang 1
        $vending_machine_slot->save();
    }

    /** Create Customer */
    public static function createCustomer($request)
    {
        $name = $request->input('name');
        $identity_type = $request->input('identity_type') ? : 'ktp';
        $identity_number = $request->input('identity_number');
        $email = $request->input('email') ? : null;
        $phone = $request->input('phone')  ? : null;
        $saldo = $request->input('saldo')  ? : null;
        $card_number = $request->input('card_number')  ? : null;
        $saldo_pens = $request->input('saldo_pens')  ? : null;
        $address = $request->input('address') ? : null;
        $vending_machine_alias = $request->input('vending_machine_alias');
        
        $type = $request->input('type') == 'mini' ? 'mini' : 'normal';
        
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
        $customer = $customer ? : new Customer;
        $customer->name = $name;
        $customer->identity_type = $identity_type;
        $customer->identity_number = $identity_number;
        $customer->email = $email;
        $customer->address = $address;
        $customer->phone = $phone;
        if ($card_number) {
            $customer->card_number = $card_number;
        }
        if ($saldo) {
            $customer->saldo = $saldo;
        }

        if ($saldo_pens) {
            $customer->saldo_pens = $saldo_pens;
        }
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

    /** Vending Machine Transaction by Gopay */
    public static function gopayTransaction($request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $slot_alias = $request->input('slot_alias');
        $type = $request->input('type') ? : 'normal'; // normal, mini
        $payment_type = $request->input('payment_type') == 'gopay' ? 'gopay' : 'saldo'; // normal, mini
        
        /** Cek apakah kartu sakti */
        $kartu_sakti = KartuSakti::where('card_number', $customer_identity_number)->first();
        if ($kartu_sakti) {
            return json_encode([
                'status' => 1,
                'data' => 'success'
            ]);
        }

        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) {
            return json_encode([
                'status' => 0,
                'data' => 'Identity number customer not found'
            ]);
        }

        /** Cek slot vending machine */
        $vending_machine_slot = VendingMachineSlot::where('alias', $slot_alias)->first();
        if (!$vending_machine_slot) {
            return json_encode([
                'status' => 0,
                'data' => 'Vending Machine Slot not found'
            ]);
        }

        /** cek stok */
        if ($vending_machine_slot->stock < 1) {
            return json_encode([
                'status' => 0,
                'data' => 'Stock '.$vending_machine_slot->food_name .' is empty'
            ]);
        }

        \DB::beginTransaction();
        $client = $vending_machine_slot->vendingMachine->client;

        $transaction = new VendingMachineTransaction;
        $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
        $transaction->vending_machine_slot_id = $vending_machine_slot->id;
        $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
        $transaction->customer_id = $customer->id;
        $transaction->hpp = $vending_machine_slot->food ? $vending_machine_slot->food->hpp : 0;
        $transaction->food_name = $vending_machine_slot->food ? $vending_machine_slot->food->name : null;
        $transaction->selling_price_client = $vending_machine_slot->food ? $vending_machine_slot->food->selling_price_client : null;
        $transaction->profit_client = $vending_machine_slot->food ? $vending_machine_slot->food->profit_client : null;
        $transaction->profit_platform_type = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_type : null;
        $transaction->profit_platform_percent = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_percent : null;
        $transaction->profit_platform_value = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_value : null;
        
        // jumlah keutungan real untuk platform. Secara default ambil dari value, namun jika profit type percent, maka dijumlah ulang
        $transaction->profit_platform = $client->profit_platform_value;
        if ($transaction->profit_platform_type == 'percent') {
            $transaction->profit_platform = $vending_machine_slot->selling_price_vending_machine * $vending_machine_slot->profit_platform_percent / 100;
        }

        $transaction->selling_price_vending_machine = $vending_machine_slot->food->selling_price_vending_machine;
        $transaction->quantity = 1;
        $transaction->status_transaction = 2; // pending

        /** Update flaging transaksi dan update saldo dari vending machine */
        $vending_machine = $transaction->vendingMachine;
        $vending_machine->flaging_transaction = Str::random(10);
        $vending_machine->save();

        try {
            $transaction->save();
            \DB::commit();
            return self::gopay($transaction->id);
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 0,
                'data' => 'Transaction failed'
                
            ]);
        }
    }

    /** Vending Machine Transaction by Gopay */
    public static function gopayTransactionAnonim($request)
    {
        $slot_alias = $request->input('slot_alias');
        $type = $request->input('type') ? : 'normal'; // normal, mini
        $payment_type = $request->input('payment_type') == 'gopay' ? 'gopay' : 'saldo'; // normal, mini

        /** Cek slot vending machine */
        $vending_machine_slot = VendingMachineSlot::where('alias', $slot_alias)->first();
        if (!$vending_machine_slot) {
            return json_encode([
                'status' => 0,
                'data' => 'Vending Machine Slot not found'
            ]);
        }

        /** cek stok */
        if ($vending_machine_slot->stock < 1) {
            return json_encode([
                'status' => 0,
                'data' => 'Stock '.$vending_machine_slot->food_name .' is empty'
            ]);
        }

        \DB::beginTransaction();
        $client = $vending_machine_slot->vendingMachine->client;

        $transaction = new VendingMachineTransaction;
        $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
        $transaction->vending_machine_slot_id = $vending_machine_slot->id;
        $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
        $transaction->customer_id = 1;
        $transaction->hpp = $vending_machine_slot->food ? $vending_machine_slot->food->hpp : 0;
        $transaction->food_name = $vending_machine_slot->food ? $vending_machine_slot->food->name : null;
        $transaction->selling_price_client = $vending_machine_slot->food ? $vending_machine_slot->food->selling_price_client : null;
        $transaction->profit_client = $vending_machine_slot->food ? $vending_machine_slot->food->profit_client : null;
        $transaction->profit_platform_type = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_type : null;
        $transaction->profit_platform_percent = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_percent : null;
        $transaction->profit_platform_value = $vending_machine_slot->food ? $vending_machine_slot->food->profit_platform_value : null;
        
        // jumlah keutungan real untuk platform. Secara default ambil dari value, namun jika profit type percent, maka dijumlah ulang
        $transaction->profit_platform = $client->profit_platform_value;
        if ($transaction->profit_platform_type == 'percent') {
            $transaction->profit_platform = $vending_machine_slot->selling_price_vending_machine * $vending_machine_slot->profit_platform_percent / 100;
        }
        $transaction->selling_price_vending_machine = (int)(($vending_machine_slot->food->selling_price_vending_machine)*1.1);
        $transaction->quantity = 1;
        $transaction->status_transaction = 2; // pending

        /** Update flaging transaksi. Digunakan untuk Smansa */
        $vending_machine = $transaction->vendingMachine;
        $vending_machine->flaging_transaction = Str::random(10);
        $vending_machine->saldo += $vending_machine_slot->food->selling_price_client;
        $vending_machine->save();
        
        try {
            $transaction->save();
            \DB::commit();
            
            $respon_= self::gopay($transaction->id);
            $respon= json_decode($respon_, true);
            echo "coba coba";
            echo $respon['actions'][0];
            $respon['id']=$transaction->id;

            return($respon);
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 0,
                'data' => 'Transaction failed'
            ]);
        }
    }
    
    /** Create QR Code Gopay */
    public static function gopay($id)
    {
        $transaction = VendingMachineTransaction::findOrFail($id);
        
        /** init gopay transaction id */
        $gopay_transaction = GopayTransaction::init($transaction, $id, $transaction->selling_price_vending_machine);
        $customer = $transaction->customer;
        $midtrans = new Midtrans;

        $transaction_details = array(
            'order_id'      => $gopay_transaction->id,
            'gross_amount'  => $transaction->selling_price_vending_machine
        );

        // Populate items
        $items = [
            array(
                'id'        => $transaction->vendingMachineSlot->id,
                'price'     => $transaction->selling_price_vending_machine,
                'quantity'  => $transaction->quantity,
                'name'      => $transaction->food_name,
            )
        ];

        // Populate customer's Info
        $customer_details = array(
            'first_name'      => $customer->name,
            'last_name'       => '',
            'email'           => $customer->email,
            'phone'           => $customer->phone,
        );

        $credit_card['secure'] = true;
        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O", $time),
            'unit'       => 'hour',
            'duration'   => 2
        );
        
        $transaction_data = array(
            'payment_type' => 'gopay',
            'transaction_details' => $transaction_details,
            'item_details' => $items,
            'customer_details' => $customer_details,
            'environment' => \App::environment()
        );

        try {
            $snap_token = $midtrans->gopayCharge($transaction_data);
            return $snap_token;
        } catch (Exception $e) {
            return $e->getMessage;
        }
    }

    /** Customer topup by Gopay */
    public static function topupTransaction($request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $saldo = $request->input('saldo');
        /** Cek customer ada apa tidak */
        $customer = Customer::where('identity_number', $customer_identity_number)->first();
        if (!$customer) {
            return json_encode([
                'status' => 0,
                'data' => 'Identity number customer not found'
            ]);
        }

        $client = $customer->client;
        $total_topup = 0;
        /** generate transfer saldo */
        $transfer_saldo = new TransferSaldo;
        $transfer_saldo->payment_status = 2; // pending
        $transfer_saldo->to_type = get_class($customer);
        $transfer_saldo->to_type_id = $customer->id;
        $transfer_saldo->saldo = $saldo;
        $transfer_saldo->created_by = 1; // system

        $transfer_saldo->topup_type = 'gopay';
        $transfer_saldo->fee_topup_type = $client->fee_topup_gopay_type;
        if ($transfer_saldo->fee_topup_type == 'value') {
            $transfer_saldo->fee_topup_value = $client->fee_topup_gopay_value;
            $total_topup = $transfer_saldo->fee_topup_value + $saldo;
        }
        if ($transfer_saldo->fee_topup_type == 'percent') {
            $transfer_saldo->fee_topup_percent = $client->fee_topup_gopay_percent;
            $total_topup = ($saldo * $client->fee_topup_gopay_percent / 100) + $saldo;
        }

        $transfer_saldo->total_topup = $total_topup;
        $transfer_saldo->save();
        /** init gopay transaction id */
        $gopay_transaction = GopayTransaction::init($transfer_saldo, $transfer_saldo->id, $total_topup);
        $midtrans = new Midtrans;

        /** update transfer saldo */
        $transfer_saldo->from_type = get_class($gopay_transaction);
        $transfer_saldo->from_type_id = $gopay_transaction->id;
        $transfer_saldo->save();


        $transaction_details = array(
            'order_id'      => $gopay_transaction->id,
            'gross_amount'  => $transfer_saldo->total_topup
        );

        // Populate items
        $items = [
            array(
                'id'        => $transfer_saldo->id,
                'price'     => $transfer_saldo->total_topup,
                'quantity'  => 1,
                'name'      => 'Topup by Gopay',
            )
        ];

        // Populate customer's Info
        $customer_details = array(
            'first_name'      => $customer->name,
            'last_name'       => '',
            'email'           => $customer->email,
            'phone'           => $customer->phone,
        );

        $credit_card['secure'] = true;
        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O", $time),
            'unit'       => 'hour',
            'duration'   => 2
        );
        
        $transaction_data = array(
            'payment_type' => 'gopay',
            'transaction_details' => $transaction_details,
            'item_details' => $items,
            'customer_details' => $customer_details
        );
    
        try {
            $respon_ = $midtrans->gopayCharge($transaction_data);
            $respon = json_decode($respon_, true);
            $respon['id'] = $transfer_saldo->id;

            return($respon);
        } catch (Exception $e) {
            return $e->getMessage;
        }
    }
}
