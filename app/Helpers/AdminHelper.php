<?php

namespace App\Helpers;

use App\User;
use Carbon\Carbon;
use App\Models\Food;
use App\Models\Item;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Firmware;
use App\Helpers\FileHelper;
use App\Helpers\AdminHelper;
use Bican\Roles\Models\Role;
use App\Helpers\NumberHelper;
use App\Models\StockMutation;
use App\Models\TransferSaldo;
use App\Models\VendingMachine;
use App\Exceptions\AppException;
use App\Models\VendingMachineSlot;
use Illuminate\Support\Facades\DB;

class AdminHelper
{
    public static function delete($model)
    {
        try {
            $model->delete();
            return true;
        } catch (\Exception $e) {
            throw new AppException("Woops, data can't be delete because is used by another form", 503);
        }
    }

    public static function createFirmware($request, $id='')
    {
        DB::beginTransaction();
        $file = $request->file('file');
        $firmware = $id ? Firmware::findOrFail($id) : new Firmware;
        $firmware->name = $request->input('name');
        $firmware->code_version = $request->input('code_version');
        $firmware->type = $request->input('type');
        if (asset($file)) {
            $firmware->link = FileHelper::upload($file, 'uploads/firmware/');
        }
        
        try {
            $firmware->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }
        
        DB::commit();
        return $firmware;
    }

    public static function createClient($request, $id='')
    {
        DB::beginTransaction();

        if (!$id) {
            $user = User::where('username', $request->username)->first();
            if ($user) {
                throw new AppException("Username ini sudah dipakai, silahkan pakai yang lain", 503);
            }

            $user = self::createUserFromClient($request);
        }

        $file = $request->file('file');
        $client = $id ? Client::findOrFail($id) : new Client;
        $client->name = $request->input('name');
        $client->address = $request->input('address');
        $client->company = $request->input('company');
        $client->phone = $request->input('phone');
        if (asset($file)) {
            $client->logo = FileHelper::upload($file, 'uploads/client-logo/');
        }
        /** Profit Platform */
        $client->profit_platform_type = $request->input('profit_platform_type');
        if ($client->profit_platform_type == 'value') {
            $client->profit_platform_value = format_db($request->input('profit_platform_value'));
        }

        if ($client->profit_platform_type == 'percent') {
            $client->profit_platform_percent = format_db($request->input('profit_platform_value'));
        }

        /** Topup Manual */
        $client->fee_topup_manual_type = $request->input('fee_topup_manual_type');
        if ($client->fee_topup_manual_type == 'value') {
            $client->fee_topup_manual_value = format_db($request->input('fee_topup_manual_value'));
        }

        if ($client->fee_topup_manual_type == 'percent') {
            $client->fee_topup_manual_percent = format_db($request->input('fee_topup_manual_value'));
        }

        /** Topup Gopay */
        $client->fee_topup_gopay_type = $request->input('fee_topup_gopay_type');
        if ($client->fee_topup_gopay_type == 'value') {
            $client->fee_topup_gopay_value = format_db($request->input('fee_topup_gopay_value'));
        }

        if ($client->fee_topup_gopay_type == 'percent') {
            $client->fee_topup_gopay_percent = format_db($request->input('fee_topup_gopay_value'));
        }


        if (!$id) {
            $client->user_id = $user->id;
        }

        try {
            $client->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }
        
        DB::commit();
        return $client;
    }

    public static function createUserFromClient($request)
    {
        DB::beginTransaction();

        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->username = $request->username;
        try {
            $user->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        // set role client
        $user->attachRole(2);
        $role = Role::find(2);
        DB::commit();
        
        return $user;
    }

    public static function createCustomer($request, $id='')
    {
        DB::beginTransaction();
        $saldo = $request->input('saldo');
        $customer = $id ? Customer::findOrFail($id) : new Customer;
        $customer->name = $request->input('name');
        $customer->identity_type = $request->input('identity_type');
        $customer->card_number = $request->input('card_number');
        // if ($saldo) {
        //     $customer->saldo = format_db($saldo);
        // }
        
        try {
            $customer->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }
        
        DB::commit();
        return $customer;
    }

    public static function createVendingMachine($request, $id='')
    {
        DB::beginTransaction();
        $vending_machine = $id ? VendingMachine::findOrFail($id) : new VendingMachine;
        $vending_machine->type = $request->input('type'); // 1. vending 2. stand
        $vending_machine->name = $request->input('name');
        $vending_machine->production_year = $request->input('production_year') ? : date('Y');
        $vending_machine->location = $request->input('location');
        $vending_machine->ip = $request->input('ip');
        $vending_machine->client_id = $request->input('client_id');
        $vending_machine->alias = $request->input('alias');
        $vending_machine->version_firmware_id = $request->input('version_firmware_id');
        $vending_machine->version_ui_id = $request->input('version_ui_id');

        try {
            $vending_machine->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $vending_machine;
    }

    public static function createVendingMachineAndSlot($request)
    {
        $vending_machine = self::createVendingMachine($request);
        $row = $request->slot_rows;
        $column = $request->slot_column;
        if ($row && $column) {
            // $total = $row * $column;
            for ($i=0; $i<$row; $i++) {
                for ($y=0; $y<$column; $y++) {
                    $request['name'] = 'Makanan '. $i.$y;
                    $request['alias'] = $vending_machine->alias.'_baris_'.$i.'_kolom_'.$y;
                    $request['vending_machine_id'] = $vending_machine->id;
                    $request['capacity'] = $column;
                    self::createVendingMachineSlot($request);
                }
            }
        }

        return $vending_machine;
    }

    public static function createVendingMachineSlot($request)
    {
        DB::beginTransaction();
        $id = $request->vending_machine_slot_id;
        $vending_machine = $id ? VendingMachineSlot::findOrFail($id) : new VendingMachineSlot;
        $vending_machine->name = $request->input('name');
        $vending_machine->vending_machine_id = $request->input('vending_machine_id');
        $vending_machine->alias = $request->input('alias');
        $vending_machine->food_name = $request->input('food_name');
        $vending_machine->capacity = $request->input('capacity') ? : null;
        $vending_machine->expired_date = $request->input('expired_date') ? Carbon::createFromFormat('m/d/Y g:i A', $request->input('expired_date')) : null;
        
        try {
            $vending_machine->save();
        } catch (\Exception $e) {
            info($e);
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $vending_machine;
    }

    public static function createVendingMachineStock($request)
    {
        DB::beginTransaction();
        $vending_machine_slot = VendingMachineSlot::findOrFail($request->slot_id);
        $vending_machine = $id ? VendingMachineSlot::findOrFail($id) : new VendingMachineSlot;
        $vending_machine->name = $request->input('name');
        $vending_machine->vending_machine_id = $request->input('vending_machine_id');
        $vending_machine->alias = $request->input('alias');
        $vending_machine->food_name = $request->input('food_name');
        $vending_machine->profit_platform_type = $request->input('profit_platform_type');
        if ($vending_machine->profit_platform_type == 'value') {
            $vending_machine->profit_platform_value = format_db($request->input('profit_platform_value'));
        }

        if ($vending_machine->profit_platform_type == 'percent') {
            $vending_machine->profit_platform_percent = format_db($request->input('profit_platform_value'));
        }

        $vending_machine->expired_date = Carbon::createFromFormat('m/d/Y g:i A', $request->input('expired_date'));

        try {
            $vending_machine->save();
        } catch (\Exception $e) {
            info($e);
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $vending_machine;
    }

    /**
     * Client
     */
    public static function createVendingMachineSlotByClient($request)
    {
        $file = $request->file;

        DB::beginTransaction();
        $id = $request->vending_machine_slot_id;
        $vending_machine = $id ? VendingMachineSlot::findOrFail($id) : new VendingMachineSlot;
        $vending_machine->food_name = $request->input('food_name');
        $vending_machine->capacity = $request->input('capacity');
        $vending_machine->hpp = format_db($request->input('hpp'));
        $vending_machine->selling_price_client = format_db($request->input('selling_price_client'));
        $vending_machine->profit_client = $vending_machine->selling_price_client - $vending_machine->hpp;
        $vending_machine->selling_price_vending_machine = format_db($request->input('selling_price_vending_machine'));
        $vending_machine->expired_date = Carbon::createFromFormat('m/d/Y g:i A', $request->input('expired_date'));
        if ($file) {
            $vending_machine->photo = FileHelper::upload($file, 'uploads/product/');
            ;
        }
        try {
            $vending_machine->save();
        } catch (\Exception $e) {
            info($e);
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $vending_machine;
    }

    public static function createVendingMachineStockByClient($request)
    {
        $vending_machine_slot = VendingMachineSlot::findOrFail($request->input('slot_id'));

        DB::beginTransaction();
        $id = $request->vending_machine_stock_id;
        $stock_mutation = $id ? StockMutation::findOrFail($id) : new StockMutation;
        $stock_mutation->food_id = $vending_machine_slot->food ? $vending_machine_slot->food->id : null;
        $stock_mutation->vending_machine_id = $request->input('vending_machine_id');
        $stock_mutation->vending_machine_slot_id = $request->input('slot_id');
        $stock_mutation->stock = $request->input('stock');
        $stock_mutation->type = 'stock_mutation';
        $stock_mutation->food_name = $vending_machine_slot->food ? $vending_machine_slot->food->name : null;
        $stock_mutation->hpp = $vending_machine_slot->food ? $vending_machine_slot->food->hpp : 0;
        $stock_mutation->selling_price_client = $vending_machine_slot->food ? $vending_machine_slot->food->selling_price_client : 0;
        $stock_mutation->created_by = auth()->user()->id;

        $vending_machine_slot->stock = $vending_machine_slot->stock + $stock_mutation->stock;
        $vending_machine_slot->save();
        
        try {
            $stock_mutation->save();
        } catch (\Exception $e) {
            info($e);
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $stock_mutation;
    }

    /** Topup Customer */
    public static function createTopupCustomer($request)
    {
        $saldo = format_db($request->input('saldo'));
        $total_topup = format_db($request->input('total_topup'));
        
        DB::beginTransaction();
        $transfer_saldo = new TransferSaldo;
        $transfer_saldo->from_type = get_class(new Client);
        $transfer_saldo->from_type_id = client()->id;
        $transfer_saldo->saldo = $saldo;
        $transfer_saldo->to_type = get_class(new Customer);
        $transfer_saldo->to_type_id = $request->customer_id;
        $transfer_saldo->created_by = auth()->user()->id;
        $transfer_saldo->topup_type = $request->input('topup_type');
        $transfer_saldo->fee_topup_type = $request->input('fee_topup_type');
        if ($transfer_saldo->fee_topup_type == 'value') {
            $transfer_saldo->fee_topup_value = $request->input('fee_topup_value');
        }
        if ($transfer_saldo->fee_topup_type == 'percent') {
            $transfer_saldo->fee_topup_percent = $request->input('fee_topup_percent');
        }

        $transfer_saldo->total_topup = $total_topup;

        try {
            $transfer_saldo->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        // update saldo customer
        $customer = Customer::find($request->customer_id);
        $customer->saldo += $saldo;
        $customer->save();

        DB::commit();
        return $transfer_saldo;
    }

    /**
     * Create Stand
     */
    public static function createStand($request, $id='')
    {
        $client = Client::where('user_id', auth()->user()->id)->first();

        DB::beginTransaction();
        $stand = $id ? VendingMachine::findOrFail($id) : new VendingMachine;
        $stand->type = 2; // 1. vending 2. stand
        $stand->name = $request->input('name');
        $stand->client_id = $client->id;
        $stand->alias = str_random(20);

        try {
            $stand->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $stand;
    }

    /** Create master food */
    public static function createFood($request, $id='')
    {
        $type = client()->profit_platform_type;
        $profit_vm = 0;
        if ($type == 'value') {
            $profit_vm = client()->profit_platform_value + client()->selling_price_client;
        } elseif ($type == 'percent') {
            $profit_vm = (client()->profit_platform_percent * client()->selling_price_client) + client()->selling_price_client;
        }
        
        $file = $request->file;

        DB::beginTransaction();
        $food = $id ? Food::findOrFail($id) : new Food;
        $food->name = $request->name;
        $food->category_id = $request->category_id;
        $food->client_id = client()->id;
        $food->hpp = format_db($request->input('hpp'));
        $food->selling_price_client = format_db($request->input('selling_price_client'));
        $food->profit_client = $food->selling_price_client - $food->hpp;
        $food->profit_platform_type = client()->profit_platform_type;
        $food->profit_platform_percent = client()->profit_platform_percent;
        $food->profit_platform_value = client()->profit_platform_value;
        $food->selling_price_vending_machine = $food->selling_price_client + $profit_vm;

        if ($file) {
            $food->photo = FileHelper::upload($file, 'uploads/food/');
            ;
        }
        try {
            $food->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $food;
    }
}
