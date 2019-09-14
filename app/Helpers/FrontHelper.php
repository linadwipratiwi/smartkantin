<?php

namespace App\Helpers;

use App\User;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Firmware;
use App\Models\Client;
use App\Models\Customer;
use App\Models\TransferSaldo;
use App\Helpers\FileHelper;
use App\Helpers\AdminHelper;
use Bican\Roles\Models\Role;
use App\Models\VendingMachine;
use App\Exceptions\AppException;
use App\Models\VendingMachineSlot;
use Illuminate\Support\Facades\DB;
use App\Models\StockMutation;

class FrontHelper
{
    // Produk di Stand
    public static function createProduct($request, $id='')
    {
        $file = $request->file;

        DB::beginTransaction();
        $id = $request->vending_machine_slot_id;
        $product = $id ? VendingMachineSlot::findOrFail($id) : new VendingMachineSlot;
        $product->name = str_random(40);
        $product->vending_machine_id = $request->input('vending_machine_id');
        $product->alias = str_random(40);
        $product->food_name = $request->input('food_name');
        $product->capacity = 1000;
        $product->stock = $request->input('stock');;
        $product->expired_date = null;
        $product->selling_price_client = format_db($request->selling_price_client);
        $product->selling_price_vending_machine = format_db($request->selling_price_client);
        $product->profit_platform_type = null;
        $product->profit_platform_value = 'value'; // set static value
        if ($file) {
            $product->photo = FileHelper::upload($file, 'uploads/product/');;
        }

        try {
            $product->save();
            $stock_mutation = self::creteStockFromCreateProduct($product, $id);
            $product->ref_stock_mutation_id = $stock_mutation->id;
            $product->save();
        } catch (\Exception $e) {
            info($e);
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $product;

    }

    public static function creteStockFromCreateProduct($product, $id='')
    {
        DB::beginTransaction();
        $stock_mutation = $id ? StockMutation::find($product->ref_stock_mutation_id) : new StockMutation;
        $stock_mutation->vending_machine_id = $product->vendingMachine->id;
        $stock_mutation->vending_machine_slot_id = $product->id;
        $stock_mutation->stock = $product->stock;
        $stock_mutation->type = 'stock_mutation';
        $stock_mutation->food_name = $product->food_name;
        $stock_mutation->hpp = $product->hpp;
        $stock_mutation->created_by = auth()->user()->id;

        try {
            $stock_mutation->save();
        } catch (\Exception $e){
            info($e);
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $stock_mutation;
    }
}