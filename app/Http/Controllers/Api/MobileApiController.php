<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Inventory;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use App\Models\Multipayment;
use Illuminate\Http\Request;
use App\Models\FirebaseToken;
use App\Models\TransferSaldo;
use App\Models\FoodSchedule;
use App\Models\Food;
use App\Models\VendingMachine;
use App\Models\Withdraw;
use App\Helpers\ApiStandHelper;
use App\Exceptions\AppException;
use App\Models\UserVendingMachine;
use App\Models\VendingMachineSlot;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\AuthApiControllerTrait;
use App\Models\VendingMachineTransaction;
use App\Helpers\FirebaseHelper;


class MobileApiController extends Controller
{
    use AuthApiControllerTrait;

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
        $type = $request->input('type') ?: 'normal'; // normal, mini
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
                return '0:Stock ' . $vending_machine_slot->food_name . ' is empty';
            }

            return json_encode([
                'status' => 0,
                'msg' => 'Stock ' . $vending_machine_slot->food_name . ' is empty'
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

    /**Login  untuk stand*/
    public static function loginStand(Request $request)
    {
        // user dan password client sebagai masukan
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
        $hasher = app('hash');
        if ($hasher->check($password, $user->password)) {
            // login Success

            // mencari type user
            $type = "vending/stand";
            $vending_id = UserVendingMachine::where('user_id', $user->id)->first()->vending_machine_id;
            $vending = VendingMachine::find($vending_id);
            if (!$vending) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'type user not found'
                ]);
            } else {
                $data = $vending;
            }
            $client = Client::find($vending->client_id);
            $client_name = "";
            if ($client) {
                $client_name = $client->name;



                return response()->json([
                    'status' => 1,
                    'user_id' => $user->id,
                    'id' => $data->id,
                    'type' => $type,
                    'name' => $data->name,
                    'user_name' => $user->name,
                    'client_name' => $client_name,
                    'client_logo' => $client->logo,
                    'stand_saldo' => $vending->saldo,
                    'msg' => 'access granted'

                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'msg' => 'acces denied'
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
    }


    /**Login  untuk stand*/
    public static function loginCustomer(Request $request)
    {
        // user dan password client sebagai masukan
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
        $hasher = app('hash');
        if ($hasher->check($password, $user->password)) {
            // login Success

            
            $customer = Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                return self::returnMessageError("not found customer");
            } else {
                return self::returnMessageSuccess($customer);
            }
         
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
    }
    public static function changeUsername(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $newUsername = $request->input('new_username') ?: "";
        $newName = $request->input('new_name') ?: "";

        $user = User::where('username', $username)->first();
        if ($user) {
            $hasher = app('hash');
            if ($hasher->check($password, $user->password)) {
                if ($newUsername) {
                    $allUsers = User::all();
                    foreach ($allUsers as $allUser) {
                        if ($newUsername == $allUser->username) {
                            return (self::returnMessageError("username already exist!"));
                        }
                    }
                    $user->username = ($newUsername);
                }
                if ($newName) {
                    $stand_id = UserVendingMachine::where('user_id', $user->id)->first()->vending_machine_id;
                    $stand = VendingMachine::find($stand_id);
                    $stand->name = $newName;
                    $stand->save();
                }

                try {
                    $user->save();
                    return response()->json([
                        'status' => 1,
                        'msg' => 'success',
                        'username' => $user->username,
                        'name' => $stand->name
                    ]);
                } catch (\Throwable $th) {
                }
            }
        }
        return response()->json(
            [
                'status' => 0,
                'msg' => 'permission denied'
            ]
        );
    }
    public static function changePassword(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $newPassword = $request->input('new_password');

        $user = User::where('username', $username)->first();

        $hasher = app('hash');
        if ($hasher->check($password, $user->password)) {
            $user->password = $hasher->make($newPassword);
            try {
                $user->save();
                return response()->json([
                    'status' => 1,
                    'msg' => 'success',
                    'username' => $user->username,
                    'name' => $user->name

                ]);
            } catch (\Throwable $th) {
            }
        }

        return response()->json(
            [
                'status' => 0,
                'msg' => 'permission denied'
            ]
        );
    }

    /**Login */
    public static function login(Request $request)
    {
        // user dan password client sebagai masukan
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
        $hasher = app('hash');
        if ($hasher->check($password, $user->password)) {
            // login Success

            // mencari type user
            $type = "client";
            $client = Client::where('user_id', $user->id)->first();
            if (!$client) {
                $type = "customer";
                $customer = Customer::where('user_id', $user->id)->first();
                if (!$customer) {
                    $type = "vending/stand";
                    $vending_id = UserVendingMachine::where('user_id', $user->id)->first()->vending_machine_id;
                    $vending = VendingMachine::find($vending_id);
                    if (!$vending) {
                        return response()->json([
                            'status' => 0,
                            'msg' => 'type user not found'
                        ]);
                    } else {
                        $data = $vending;
                    }
                } else {
                    $data = $customer;
                }
            } else {
                $data = $client;
            }


            return response()->json([
                'status' => 1,
                'id' => $data->id,
                'type' => $type,
                'name' => $data->name,
                'msg' => 'access granted'

            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'acces denied'
            ]);
        }
    }

    /**login untuk client
     */
    public static function loginClient(Request $request)
    {
        // user dan password client sebagai masukan
        $username = $request->input('username');
        $password = $request->input('password');

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
            // get client
            $client = Client::where('user_id', $user->id)->first();
            // get vending

            return response()->json([
                'status' => 1,
                'id' => $client->id,
                'name' => $client->name,
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
                'status' => 0,
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
        $hargaTotal = $harga * $quantity;
        $customer_id = $transaction->customer_id;
        $customer = Customer::findOrFail($customer_id);
        $saldo = $customer->saldo;

        /**cek saldo apakahmencukupi */
        if ($saldo < $hargaTotal) {
            return response()->json([
                'status' => 0,
                'msg' => 'not enough saldo'
            ]);
        }
        $saldo = $saldo - $harga;
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

        $customer = Customer::where([
            'identity_number' => $customer_identity_number,
            'register_at_client_id' => $vending_machine_client_id
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

        $hasil = [];
        $hargaTotal = 0;
        foreach ($transactions as $transaction) {
            $status_transaction = $transaction->status_transaction;
            $harga = $transaction->selling_price_vending_machine;
            $quantity = $transaction->quantity;
            $hargaTotal = $hargaTotal + ($harga * $quantity);
            $hasil[] = $transaction;
        }

        if (!$hasil) {
            return response()->json([
                "status" => 0,
                "msg" => "no bill found"
            ]);
        }


        $saldo = $customer->saldo;

        /**cek saldo apakahmencukupi */
        if ($saldo < $hargaTotal) {
            return response()->json([
                'status' => 0,
                'msg' => 'not enough saldo'
            ]);
        }
        $saldo = $saldo - $hargaTotal;
        $customer->saldo = $saldo;
        $customer->save();
        DB::commit();
        foreach ($transactions as $transaction) {
            $transaction->status_transaction = 1;
            $transaction->save();
            DB::commit();
        }

        $customer_ = json_decode($customer, true);
        $customer_['msg'] = "success";
        $customer_['status'] = 1;

        return response()->json(
            $customer_
        );
    }

    /**Bill check */
    public static function billcheck(Request $request)
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

        //    cek customer
        $customer = Customer::where([
            'identity_number' => $customer_identity_number,
            'register_at_client_id' => $vending_machine_client_id
        ])->first();
        if (!$customer) {
            return response()->json([[
                'status' => 0,
                'msg' => 'customer identity not found'
            ]]);
        }
        $customer_id = $customer->id;

        $transactions = VendingMachineTransaction::Where([
            'customer_id' => $customer_id,
            'vending_machine_id' => $vending_machine_id,
            'status_transaction' => '2'
        ])->get();

        $hasil = [];
        foreach ($transactions as $data) {
            $text = json_decode($data, true);
            $text['customer_name'] = $customer->name;
            $text['customer_identity_number'] = $customer->identity_number;
            $text['msg'] = "success";
            $hasil[] = $text;
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

    /**preorder check */
    public static function orderCheck(Request $request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $vending_machine_alias = $request->input('vending_machine_alias');
        $vending_machine_id = $request->input('vending_machine_id');
        $isPreorder = $request->input('is_preorder');
        /*cek vending machine*/
        if ($vending_machine_alias)
            $vending_machine = VendingMachine::Where('alias', $vending_machine_alias)->first();
        else if ($vending_machine_id) {
            $vending_machine = VendingMachine::find($vending_machine_id);
        }
        if (!$vending_machine) {
            return response()->json([[
                'status' => 0,
                'msg' => 'vending machine not found'
            ]]);
        }
        $vending_machine_id = $vending_machine->id;
        $vending_machine_client_id = $vending_machine->client_id;

        //    cek customer
        $customer = Customer::where([
            'identity_number' => $customer_identity_number,
            'register_at_client_id' => $vending_machine_client_id
        ])->first();
        if (!$customer) {
            return response()->json([[
                'status' => 0,
                'msg' => 'customer identity not found'
            ]]);
        }
        $customer_id = $customer->id;
        if ($isPreorder) {
            $where = [
                'customer_id' => $customer_id,
                'vending_machine_id' => $vending_machine_id,
                'status_transaction' => '3',
                'is_preorder' => ($isPreorder % 2)
            ];
        } else {
            $where = [
                'customer_id' => $customer_id,
                'vending_machine_id' => $vending_machine_id,
                'status_transaction' => '3'
            ];
        }

        $todayDate = Date('Y-m-d');
        $transactions = VendingMachineTransaction::where($where)->whereDate('preorder_date', $todayDate)->get();

        $hasil = [];
        foreach ($transactions as $data) {
            $text = json_decode($data, true);
            $text['customer_name'] = $customer->name;
            $text['customer_identity_number'] = $customer->identity_number;
            $text['msg'] = "success";
            $hasil[] = $text;
        }
        if (!$hasil) {
            return response()->json([[
                'status' => 0,
                'msg' => 'no preorder found'
            ]]);
        }
        return response()->json(
            $hasil
        );
    }



    /**preoder take*/
    public static function orderTake(Request $request)
    {
        $customer_identity_number = $request->input('customer_identity_number');
        $vending_machine_alias = $request->input('vending_machine_alias');
        $vending_machine_id = $request->input('vending_machine_id');
        $isPreorder = $request->input('is_preorder');
        /*cek vending machine*/
        if ($vending_machine_alias)
            $vending_machine = VendingMachine::Where('alias', $vending_machine_alias)->first();
        else if ($vending_machine_id) {
            $vending_machine = VendingMachine::find($vending_machine_id);
        }
        if (!$vending_machine) {
            return response()->json([
                'status' => 0,
                'msg' => 'vending machine not found'
            ]);
        }
        $vending_machine_id = $vending_machine->id;
        $vending_machine_client_id = $vending_machine->client_id;

        $customer = Customer::where([
            'identity_number' => $customer_identity_number,
            'register_at_client_id' => $vending_machine_client_id
        ])->first();
        if (!$customer) {
            return response()->json([[
                'status' => 0,
                'msg' => 'customer identity not found'
            ]]);
        }

        $customer_id = $customer->id;
        if ($isPreorder) {
            $where = [
                'customer_id' => $customer_id,
                'vending_machine_id' => $vending_machine_id,
                'status_transaction' => '3',
                'is_preorder' => ($isPreorder % 2)

            ];
        } else {
            $where = [
                'customer_id' => $customer_id,
                'vending_machine_id' => $vending_machine_id,
                'status_transaction' => '3',

            ];
        }
        $todayDate = Date('Y-m-d');
        $transactions = VendingMachineTransaction::where($where)->whereDate('preorder_date', $todayDate)->get();

        $hasil = [];
        $hargaTotal = 0;
        foreach ($transactions as $transaction) {
            $status_transaction = $transaction->status_transaction;
            $harga = $transaction->selling_price_vending_machine;
            $quantity = $transaction->quantity;
            $hargaTotal = $hargaTotal + ($harga * $quantity);
            $hasil[] = $transaction;
        }

        if (!$hasil) {
            return response()->json([
                "status" => 0,
                "msg" => "no preorder found"
            ]);
        }


        DB::commit();
        foreach ($transactions as $transaction) {
            $transaction->status_transaction = 1;
            $transaction->save();
            DB::commit();
        }

        $customer_ = json_decode($customer, true);
        $customer_['msg'] = "success";
        $customer_['status'] = 1;

        return response()->json(
            $customer_
        );
    }


    public static function getTransaction(Request $request)
    {
        $stand_id = $request->input('stand_id');
        $transactions_id = explode(';', $request->input('transactions_id'));
        $hasil = [];
        foreach ($transactions_id as $transaction_id) {
            if ($transaction_id) {
                $transaction_ = VendingMachineTransaction::find($transaction_id);
                $transaction = json_decode($transaction_, true);
                $transaction['msg'] = 'success';
                $transaction['status'] = '1';
                $transaction['customer_name'] = $transaction_->customer->name;
                $hasil[] = $transaction;
            }
        }
        if (!$hasil) {
            return response()->json([
                [
                    'status' => 0,
                    'msg' => 'transaction not found'
                ]
            ]);
        }
        return response()->json($hasil);
    }

    /**All history */
    public static function allHistory($request)
    {
        $stand_alias = $request;

        /* cek alias */
        $stand = VendingMachine::where('alias', $stand_alias)->first();
        if (!$stand) {
            return json_encode([
                'status' => 0,
                'msg' => 'stand not found'
            ]);
        }
        /*id stand*/
        $stand_id = $stand->id;
        /** Cek customer ada apa tidak */
        $transaction = VendingMachineTransaction::where('vending_machine_id', $stand->id)->get();

        $hasil = [];
        if ($transaction) {
            foreach ($transaction as $data) {
                $text = json_decode($data, true);
                $text['type_transaction'] = "buy";
                $customer_id = $data->customer_id;
                $customer = Customer::find($customer_id);
                $text['customer_name'] = $customer->name;
                $text['customer_identity_number'] = $customer->customer_identity_number;
                $hasil[] = ($text);
            }
        }
        $topup_transaction = TransferSaldo::where('from_type_id', $stand_id)->get();
        if ($topup_transaction) {
            foreach ($topup_transaction as $data) {
                $text = json_decode($data, true);
                $text['type_transaction'] = "topup";
                $customer_id = $data->to_type_id;
                $customer = Customer::find($customer_id);
                $text['customer_name'] = $customer->name;
                $text['customer_identity_number'] = $customer->customer_identity_number;
                $hasil[] = ($text);
            }
        }

        if (!$hasil) {
            return response()->json([
                'status' => 0,
                'msg' => 'no transaction'
            ]);
        }
        return response()->json(
            $hasil
        );
    }

    public static function listStand($request)
    {
        $id_client = $request;
        $stands = VendingMachine::where('client_id', $id_client)->get();
        if (!$stands) {
            return response()->json([[
                'status' => 0,
                'msg' => 'no stand found'
            ]]);
        }
        $hasil = [];
        $today_date = date("Y-m-d");

        foreach ($stands as $data) {
            //get Transaction today
            $transactions_total = VendingMachineTransaction::where('vending_machine_id', $data->id)
                ->whereDate('created_at', $today_date)->count();

            $transactions_success = VendingMachineTransaction::where('vending_machine_id', $data->id)
                ->where('status_transaction', 1)
                ->whereDate('created_at', $today_date)->count();

            $text = json_decode($data, true);
            $text['status'] = 1;
            $text['msg'] = "success";
            $text['transaction_success'] = $transactions_success;
            $text['transaction_total'] = $transactions_total;
            $hasil[] = ($text);
        }
        return response()->json($hasil);
    }


    /**History */
    public static function history(Request $request)
    {
        $stand_alias = $request->input('alias');
        $id = $request->input('id');
        $isPreorder = $request->input('is_preorder') ?: 0;
        $statusTransaction = $request->input('status_transaction');

        /* cek alias */
        if ($stand_alias) {
            $stand = VendingMachine::where('alias', $stand_alias)->first();
        } elseif ($id) {
            $stand = VendingMachine::find($id);
        }

        if (!$stand) {
            return json_encode([
                'status' => 0,
                'msg' => 'stand not found'
            ]);
        }
        /*id stand*/
        $stand_id = $stand->id;
        /** Cek customer ada apa tidak */
        if ($statusTransaction) {
            $where = [
                'vending_machine_id' => $stand->id,
                'status_transaction' => $statusTransaction,
                'is_preorder' => $isPreorder
            ];
        } else {
            $where = [
                'vending_machine_id' => $stand->id
            ];
        }
        $transaction = VendingMachineTransaction::where($where)
            ->orderBy('preorder_date', 'DESC')->get();
        $hasil = [];
        if ($transaction) {
            foreach ($transaction as $data) {
                $text = json_decode($data, true);
                $text['type_transaction'] = "buy";
                $customer_id = $data->customer_id;
                $customer = Customer::find($customer_id);
                if ($customer) {
                    $text['customer_name'] = $customer->name;
                    $text['customer_identity_number'] = $customer->identity_number;
                    $text['msg'] = "success";
                    // $text['food_id']= VendingMachineSlot::find($text['vending_machine_slot_id'])->food_id;
                    $hasil[] = ($text);
                }
            }
        }

        if (!$hasil) {
            return response()->json([[
                'status' => 0,
                'msg' => 'no transaction'
            ]]);
        }
        return response()->json(
            $hasil
        );
    }

    /** History customer transaction */
    public function customerHistoryTransaction($customer_id)
    {
        $status = \Input::get('status');
        $list_transaction = VendingMachineTransaction::where('customer_id', $customer_id)
            ->where(function ($q) use ($status) {
                if ($status) {
                    $q->where('status_transaction', $status);
                }
            })
            ->get();
        $respon = [];
        foreach ($list_transaction as $transaction) {
            $data = [
                'id' => $transaction->id,
                'quantity' => $transaction->quantity,
                'food_id' => $transaction->food_id,
                'food_name' => $transaction->food ? $transaction->food->name : $transaction->food_name,
                'transaction_number' => $transaction->transaction_number,
                'status_transaction' => $transaction->status('excel'),
                'selling_price_vm' => $transaction->selling_price_vending_machine
            ];
            array_push($respon, $data);
        }

        return response()->json($respon);
    }


    public function setJadwalFood(Request $request)
    {
        $food_id = $request->input('food_id');
        $enable_hari = $request->input('enable_hari');
        $hari = explode(';', $enable_hari);
        /**cek keberadaan food */
        $food = Food::find($food_id);
        if (!$food) {
            return self::returnMessageError("no food found");
        }
        $schedule = FoodSchedule::where('food_id', $food_id)->first();
        if (!$schedule) {
            /**buat schedule baru */
            $schedule = new FoodSchedule;
            $schedule->food_id = $food_id;
            $schedule->senin = $hari[0];
            $schedule->selasa = $hari[1];
            $schedule->rabu = $hari[2];
            $schedule->kamis = $hari[3];
            $schedule->jumat = $hari[4];
            $schedule->sabtu = $hari[5];
            $schedule->minggu = $hari[6];
            try {
                $schedule->save();
            } catch (\Throwable $th) {
                self::returnMessageError("save failed");
            }
            \DB::commit();
        } else {
            $schedule->food_id = $food_id;
            $schedule->senin = $hari[0];
            $schedule->selasa = $hari[1];
            $schedule->rabu = $hari[2];
            $schedule->kamis = $hari[3];
            $schedule->jumat = $hari[4];
            $schedule->sabtu = $hari[5];
            $schedule->minggu = $hari[6];
            $schedule->save();
        }
        return response()->json([
            'status' => 1,
            'msg' => 'success'
        ]);
    }

    public function getFood(Request $request)
    {
        // $stand=VendingMachine::find($stand_id);
        // if(!$stand){
        //     return response()->json(["msg"=>"not found stand"]);
        // }
        $stand_id = $request->stand_id;
        $slots = VendingMachineSlot::where('vending_machine_id', $stand_id)->get();
        $hasil = [];
        if ($slots) {
            foreach ($slots as $data) {

                $food_id = $data->food_id;
                $food = Food::find($food_id);
                $foodSchedule = FoodSchedule::where('food_id', $food_id)->first();

                if ($food) {
                    $text = json_decode($food, true);
                    $text["senin"] = ($foodSchedule) ? $foodSchedule->senin : 1;
                    $text["selasa"] = ($foodSchedule) ? $foodSchedule->selasa : 1;
                    $text["rabu"] = ($foodSchedule) ? $foodSchedule->rabu : 1;
                    $text["kamis"] = ($foodSchedule) ? $foodSchedule->kamis : 1;
                    $text["jumat"] = ($foodSchedule) ? $foodSchedule->jumat : 1;
                    $text["sabtu"] = ($foodSchedule) ? $foodSchedule->sabtu : 1;
                    $text["minggu"] = ($foodSchedule) ? $foodSchedule->minggu : 1;
                    $text["msg"] = "success";
                    $text["stock"] = $data->stock;
                    $text["slot_id"] = $data->id;
                    $text["status"] = 1;
                    $hasil[] = ($text);
                }
            }
        }

        if (!$hasil) {
            return response()->json([[
                'status' => 1,
                'msg' => 'not found slot'
            ]]);
        } else {
            return response()->json($hasil);
        }
    }


    /** Firebase Token Store */
    public function firebaseTokenStore(Request $request)
    {
        $token = $request->token;
        $user_id = $request->user_id;

        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['status' => 0, "msg" => 'not found user']);
        }
        $firebase_token = FirebaseToken::where('token', $token)->first();

        /** Store */
        $firebase_token = $firebase_token ?: new FirebaseToken;
        $firebase_token->token = $token;
        $firebase_token->user_id = $user_id;
        $firebase_token->save();

        return response()->json(['status' => 1, "msg" => 'success']);
    }

    public function setFoodStock(Request $request)
    {
        $slot_ids = explode(';', $request->input('slot_id'));
        $stocks = explode(';', $request->input('stock'));
        $i = 0;
        foreach ($slot_ids as $slot_id) {
            $slot = VendingMachineSlot::find($slot_id);
            if ($slot) {
                $slot->stock = $stocks[$i];
                $slot->save();
            } else {
                return response()->json([
                    "status" => 0,
                    "msg" => "not found slot"
                ]);
            }
            $i++;
        }
        return response()->json([
            "status" => 1,
            "msg" => "success"
        ]);
    }

    public function getSaldoStand($request)
    {
        $stand_id = $request;
        $stand = VendingMachine::find($stand_id);
        if ($stand) {
            return response()->json([
                'status' => 1,
                'data' => $stand->saldo,
                'msg' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'no stand found'
            ]);
        }
    }

    /** Search transaction by customer name */
    public function searchTransactionByCustomer($stand_id)
    {
        $select = [
            'vending_machine_transactions.id',
            'vending_machine_transactions.food_name',
            'vending_machine_transactions.quantity',
            'vending_machine_transactions.status_transaction',
            'vending_machine_transactions.created_at',
            'vending_machine_transactions.total',
            'customers.name',
        ];
        $result_search = VendingMachineTransaction::searchByCustomer($stand_id)
            ->select($select)
            ->get();

        $hasil = [];
        foreach ($result_search as $data) {

            $text = json_decode($data, true);
            $text["msg"] = "success";
            $text["status"] = 1;
            $text["customer_name"] = $data->name;
            $text["quantity"] = VendingMachineTransaction::find($data->id)->quantity;
            $hasil[] = ($text);
        }

        if ($hasil) {
            return response()->json($hasil);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => "no order found"
            ]);
        }
    }

    /** Generate qr code */
    public function generateQRCode()
    {
        $id = \Input::get('id');
        // $url = url('api/v1/mobile/scan-qr-code?id='.$id);
        return view('other.generate-qr-code', ['url' => $id]);
    }

    /** Handler QRCode scan */
    public function scanQRCode_lawas()
    {
        $param = \Input::get('id');
        $id = explode(";", $param);

        $vending_machine_transaction = VendingMachineTransaction::whereIn('id', $id)
            ->update(['status_transaction' => 1]);

        $view = view('other.success-scan-qr-code');
        $view->list_transaction = VendingMachineTransaction::whereIn('id', $id)->get();
        toaster_success('Makanan berhasil diambil');

        $transaction = VendingMachineTransaction::find($id[0]);
        FirebaseHelper::pushFirebaseNotification($transaction, "take_food");

        return $view;
    }

    /** Handler QRCode scan mode langsung */
    public function scanQRCode()
    {
        $param = explode(";", \Input::get('id'));
        $vendingId = $param[0];
        $customerId = $param[1];
        $where = [
            'customer_id' => $customerId,
            'vending_machine_id' => $vendingId,
            'status_transaction' => 3
        ];
        $todayDate = Date('Y-m-d');
        $transactions = VendingMachineTransaction::where($where)->whereDate('preorder_date', $todayDate)->get();
        // $transactionSampling = VendingMachineTransaction::where($where)->whereDate('preorder_date', $todayDate)->first();
        $hasil = [];
        $idstring = "";
        $i = 0;
        foreach ($transactions as $transaction) {
            $idstring = $idstring . $transaction->id . ";";
            if ($i == 0) {
                $i = $i + 1;
            }
            $hasil[] = $transaction;
        }
        if (!$hasil) {                                                                               
            $view = view('other.failed-scan-qr-code');
            toaster_success('Hari ini, kamu belum pesan makanan di stand ini');
            return $view;
        } else {
            $ids = explode(";", $idstring);
            $id = [];
            foreach ($ids as $data) {
                if ($data) {
                    $id[] = $data;
                }
            }
            $vending_machine_transaction = VendingMachineTransaction::whereIn('id', $id)
                ->update(['status_transaction' => 1]);

            $view = view('other.success-scan-qr-code');
            $view->list_transaction = VendingMachineTransaction::whereIn('id', $id)->get();
            toaster_success('Makanan berhasil diambil');


            FirebaseHelper::pushFirebaseNotification($transactions, "take_food");

            return $view;
        }
    }

    public function getWithdrawTransaction($stand_id)
    {
        $withdrawTransactions = Withdraw::where('vending_machine_id', $stand_id)->orderBy('created_at', 'DESC')->get();
        $hasil = [];
        foreach ($withdrawTransactions as $withdrawTransaction) {
            $w = json_decode($withdrawTransaction, true);
            $w['stand_name'] = $withdrawTransaction->vendingMachine->name;
            $w['status'] = 1;
            $w['msg'] = 'success';
            $hasil[] = $w;
        }
        if (!$hasil) {
            return response()->json([[
                "status" => 0,
                "msg" => 'transaction not found'
            ]]);
        } else {
            return response()->json($hasil);
        }
    }

    public function withdrawRequest(Request $request)
    {
        $stand_id = $request->input('stand_id');
        $bank = $request->input('bank');
        $jumlah = $request->input('jumlah');
        $no_rekening = $request->input('no_rekening');

        DB::beginTransaction();
        $withdrawTransaction = new Withdraw;
        $withdrawTransaction->vending_machine_id = $stand_id;
        $withdrawTransaction->jumlah = $jumlah;
        $withdrawTransaction->no_rekening = $no_rekening;
        $withdrawTransaction->bank = $bank;
        $withdrawTransaction->status_transaction = 0;

        try {
            $withdrawTransaction->save();
            \DB::commit();
            $tr = json_decode($withdrawTransaction, true);
            $tr['status'] = 1;
            $tr['msg'] = 'success';
            $tr['stand_name'] = $withdrawTransaction->vendingMachine->name;
            return response()->json($tr);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 0,
                "msg" => 'failed'
            ]);
        }
    }
    public function transactionByKodepos(Request $request){
        $identity= $request->input('identity_number');
        $kode= $request->input('kode_pos');
        $customer= Customer::where('identity_number',$identity)->first();
        $transaction= VendingMachineTransaction::where('transaction_number',$kode)->where('status_transaction','3')->get();
        $total=0;
        $hasil=[];
        foreach($transaction as $tr){
            $total+= $tr->selling_price_vending_machine;
            $hasil[]=$tr;
        }
        if(!$hasil)
            return self::returnMessageError("transaction not found");
        if(!$customer){
           return self::returnMessageError("customer not found");
        }
        if($customer->saldo<$total){
            return self::returnMessageError("saldo customer kurang");
        }
        $customer->saldo-= $total;
        DB::beginTransaction();

        try{
            $customer->save();
            foreach($transaction as $tr){
                $tr->status_transaction=1;
                $tr->customer_id= $customer->id;
                $tr->save();
            }
            DB::commit();
           // return redirect('v/checkout');
            return response()->json([
                'status'=>1,
                'msg'=>'success'
            ]);
        }
        catch(\Throwable $th){
            return self::returnMessageError("transaction failed");

        }
    }
    public function findTransactionUpdatedOnIP($ip)
    {
        $transaction = VendingMachineTransaction::whereDate('created_at', date('Y-m-d'))->where('status_transaction', '3')->orderBy('created_at', 'DESC')->get();
        $hasilKode=0;
        foreach($transaction as $tr) {
            $kodepos = $tr->transaction_number;
            $dataPos = explode('-', $kodepos);
            $tlgPos = $dataPos[1] . '-' . $dataPos[2] . '-' . $dataPos[3];
            if ($dataPos[5]) {
                if ($ip == $dataPos[5]) {
                    $hasilKode=$kodepos;
                break;
                }
            }
        }
        if($hasilKode){
            return response()->json(
                [
                    'status'=>1,
                    'kodepos'=> $hasilKode
                ]
            );
            // $transaction= VendingMachineTransaction::where('transaction_number',$hasilKode)->get();
            // $hasil = [];
            // foreach($transaction as $tr){
            //     $tr_=json_decode($tr,true);
            //     $tr_['status']=1;
            //     $tr_['msg']= 'success';
            //     $hasil[]=$tr_;
            // }
            // return response()->json($hasil);
        }
        return self::returnListMessageError("transaction not found");
    }

<<<<<<< HEAD
    public function getListStand($clientID){
        $vending= VendingMachine::where('client_id',$clientID)->where('type',2)->get();
        return self::returnListMessageSuccess($vending);        
    }

    public static function returnListMessageSuccess($hasil){
        $respon=[];
        foreach($hasil as $h){
            $h['status']=1;
            $h['msg']='success';
            $respon[]=$h;
        }
        return response()->json($respon);
    }
    public static function returnMessageSuccess($data){
        $data['status']=1;
        $data['msg']='success';
        return response()->json($data);
    }
=======

>>>>>>> iqom-dev
    public static function returnMessageError($string)
    {
        return response()->json([
            "status" => 0,
            "msg" => $string
        ]);
    }
    public static function returnListMessageError($string){
        return response()->json([[
            "status"=>0,
            "msg"=>$string
        ]]);
    }

    public static function returnListMessageError($string)
    {
        return response()->json([[
            "status" => 0,
            "msg" => $string
        ]]);
    }
}
