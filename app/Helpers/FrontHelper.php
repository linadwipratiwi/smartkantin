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
use Illuminate\Support\Str;
use App\Helpers\AdminHelper;
use Bican\Roles\Models\Role;
use App\Models\StockMutation;
use App\Models\TransferSaldo;
use App\Models\VendingMachine;
use App\Exceptions\AppException;
use App\Models\VendingMachineSlot;
use Illuminate\Support\Facades\DB;

class FrontHelper
{
    // Produk di Stand
    public static function createProduct($request)
    {
        $food_id = $request->food_id;

        DB::beginTransaction();
        for ($i=0; $i < count($food_id); $i++) { 
            $food = Food::findOrFail($food_id[$i]);
            $cek = VendingMachineSlot::where('vending_machine_id', $request->input('vending_machine_id'))
                ->where('food_id', $food_id[$i])
                ->first();
            if ($cek) continue;
            $product = new VendingMachineSlot;
            $product->vending_machine_id = $request->input('vending_machine_id');
            $product->name = $food->name;
            $product->alias = str_random(40);
            $product->food_name = $food->name;
            $product->food_id = $food->id;
            $product->capacity = null;
            $product->category_id = null;
            $product->stock = 1000;
            $product->expired_date = null;
            $product->selling_price_client = format_db($food->selling_price_client);
            $product->selling_price_vending_machine = format_db($food->selling_price_vending_machine);
            $product->profit_platform_type = $food->profit_platform_type;
            $product->profit_platform_value = $food->profit_platform_value; // set static value
            
            try {
                $product->save();
                $stock_mutation = self::creteStockFromCreateProduct($product);
                $product->ref_stock_mutation_id = $stock_mutation->id;
                $product->save();
            } catch (\Exception $e) {
                dd($e);
                throw new AppException("Failed to save data", 503);
            }
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
        $stock_mutation->selling_price_client = $product->selling_price_client;
        $stock_mutation->created_by = auth()->user()->id;
        $stock_mutation->food_id = $product->food->id;

        $vending_machine = $product->vendingMachine;
        $vending_machine->flaging_transaction = Str::random(10);;
        $vending_machine->save();

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