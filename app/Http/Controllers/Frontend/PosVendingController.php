<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
   //<?php

   namespace App\Http\Controllers\Frontend;

   use Carbon\Carbon;
   use App\Models\Temp;
   use App\Models\Category;
   use App\Models\Customer;
   use App\Helpers\ApiHelper;
   use App\Helpers\PosHelper;
   use Illuminate\Support\Str;
   use Illuminate\Http\Request;
   use App\Models\TransferSaldo;
   use App\Models\VendingMachine;
   use App\Models\UserVendingMachine;
   use App\Helpers\FirebaseHelper;
   use App\Helpers\TempDataHelper;
   use App\Models\BreakTimeSetting;
   use App\Models\VendingMachineSlot;
   use Illuminate\Support\Facades\DB;
   use App\Http\Controllers\Controller;
   use App\Models\VendingMachineTransaction;

class PosVendingController extends Controller
{
 
    public function index()
    {
        $search = \Input::get('search');
        $temp_key =PosHelper::getTempAnonimKey();
      
      
        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $total_item = count($data);
        $total_price = 0;
        $stand_active = null;

       

        // $cart = [];
        // $stand_id = [];
        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
            // $stand_active = VendingMachine::find($value['stand_id']);
            // if (!in_array($value['stand_id'], $stand_id)) {
            //     array_push($stand_id, $value['stand_id']);
            // }
        }
        
        $cart = [];
        $stand_id[] = UserVendingMachine::where('user_id',auth()->user()->id)->first()->vending_machine_id;
        $cart['stand_id'] = $stand_id;
        $cart['total_item'] = $total_item;
        $cart['total_price'] = format_price($total_price);

        $view = view('frontend.v.pos.index');
        $view->list_stand = VendingMachine::find($stand_id);
        $clientID=VendingMachine::find($stand_id[0])->client->id;
        $view->categories = Category::food()->get();
        $view->cart = $cart;
        // return $data;
        $view->search_result = $search ? VendingMachineSlot::joinVendingMachine()
            ->joinFood()
            ->where('vending_machines.client_id', $clientID)
            ->where('vending_machines.type', 2)
            ->where('foods.name', 'like', '%'.$search.'%')
            ->select(['vending_machine_slots.*'])
            ->get() : null;   
    
        
        return $view;
    }

    public function coba(){
        $view = view('frontend.v.pos.coba');
        return $view;
    }
    public function _addToCart($id)
    {
        $is_remove = \Input::get('is_remove');
        $temp_key = PosHelper::getTempAnonimKey();
        $customer=PosHelper::getAnonimCustomer();

        $search = TempDataHelper::searchKeyValue($temp_key, auth()->user()->id, ['item_id'], [$id]);
        $item = VendingMachineSlot::findOrFail($id);

        $quantity = 1;
        if ($search) {
            $quantity = $is_remove ? $search['quantity'] - 1 : $search['quantity'] + 1;
        }
        $data = [
            'item_id' => $id,
            'name' => $item->food->name,
            'selling_price_item' => $item->food->selling_price_vending_machine,
            'quantity' => $quantity,
            'photo' => asset($item->photo),
            'stand_id' => $item->vending_machine_id,
            'total' => $quantity * $item->food->selling_price_vending_machine,
        ];

        /** jika qty 0, hapus row */
        if ($data['quantity'] == 0) {
            $temp = Temp::findOrFail($search['rowid']);
            $temp->delete();
        } else {
            /** Jika tidak ada maka buat baru */
            $temp = $search ? Temp::findOrFail($search['rowid']) : new Temp;
            $temp->user_id = auth()->user()->id;
            $temp->name = $temp_key;
            $temp->keys = serialize($data);
            $temp->save();
        }
        
        $search = TempDataHelper::searchKeyValue($temp_key, auth()->user()->id, ['item_id'], [$id]);

        /** get semua item */
        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $total_item = count($data);
        $total_price = 0;
        $stand_id = [];
        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
            $stand_active = VendingMachine::find($value['stand_id']);
            if (!in_array($value['stand_id'], $stand_id)) {
                array_push($stand_id, $stand_active->id);
            }
        }
        $stand_name = VendingMachine::whereIn('id', $stand_id)->select('name')->get()->pluck('name')->toArray();
        $str_stand_name = implode(', ', $stand_name);
        $search['stand_name'] = $str_stand_name;
        $search['total_item'] = $total_item;
        $search['total_price'] = format_price($total_price);
        return $search;
    }

    /** cart */
    public function cart()
    {
        $temp_key =PosHelper::getTempAnonimKey();

        // $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $customer=PosHelper::getAnonimCustomer();
        $data = TempDataHelper::getAllRowGroupByKey($temp_key,auth()->user()->id, 'stand_id');

        if (count($data) < 1) {
            toaster_error('Anda belum menambahkan barang belanja Anda di cart. Silahkan berbelanja dulu.');
            return redirect('v');
        }

        $view = view('frontend.v.pos.cart');
        $view->list_cart_group_by_stand = $data;
        $clientID=$customer->client_id;
        $view->list_breaktime_setting = BreakTimeSetting::where('client_id',$clientID)->get();
        return $view;
    }

    /** destroy item */
    public function _destroyItem($id)
    {
        /** delete */
        TempDataHelper::remove($id);

        /** create respon */
        $temp_key = PosHelper::getTempAnonimKey();

        $data = TempDataHelper::get($temp_key, auth()->user()->id);
        $total_item = count($data);
        $total_price = 0;

        foreach ($data as $key => $value) {
            $quantity = $value['quantity'];
            $price = $quantity * $value['selling_price_item'];
            $total_price += $price;
        }

        return format_price($total_price);
    }

    /** proses checkout */
    public function payment()
    {
        $is_preorder = 0;
        // $data =explode(';', (\Input::get('preorder_date')));
        //                 $breaktime = \Input::get('break_time_setting_id');
        // $note=$data[1];
        $note=" ";
        $preorder_date = Carbon::now();
        
        $temp_key = PosHelper::getTempAnonimKey();
        $list_cart = TempDataHelper::get($temp_key, auth()->user()->id);
        DB::beginTransaction();

        /** cek jumalh */
        if (count($list_cart) < 1) {
            toaster_error('Anda belum menambahkan barang belanja Anda di cart. Silahkan berbelanja dulu.');
            return redirect('v');
        }
        /** create transaction */
        $customer = PosHelper::getAnonimCustomer();
        $clientIP = \Request::ip();

        $data=$customer->id.'-'.$clientIP;
      
        $transaction_number = VendingMachineTransaction::generateCustomNumber($data);
    
        $total_payment = 0;
        foreach ($list_cart as $cart) {
            $total_payment += $cart['total'];
        }

        // /** Cek saldo */
        // $saldo = $customer->saldo;
        // $saldo_pens = $customer->saldo_pens;
        // $saldo_total = $saldo + $saldo_pens;

        // /** jika saldo total kurang dari harga jual */
        // if ($saldo_total < $total_payment) {
        //     toaster_error('Saldo Anda tidak mencukupi');
        //     return redirect('v/cart');
        // }


        $total_belanja = 0;
        foreach ($list_cart as $cart) {
            $vending_machine_slot = VendingMachineSlot::findOrFail($cart['item_id']);
            $vending_machine = $vending_machine_slot->vendingMachine;
            $customer =PosHelper::getAnonimCustomer();

            
            if (($vending_machine_slot->stock < $cart['quantity']) && $is_preorder == 0) {
                toaster_error('Stok tidak mencukupi / kosong. Hapus barang dari daftar belanja Anda');
                return redirect('v/cart');
            }


            $client = $vending_machine_slot->vendingMachine->client;
            $transaction = new VendingMachineTransaction;
            $transaction->preorder_date = $preorder_date;
            $transaction->is_preorder = $is_preorder;
            $transaction->break_time_setting_id = $is_preorder ? $breaktime : null;
            $transaction->transaction_number = $transaction_number;
            $transaction->vending_machine_id = $vending_machine_slot->vendingMachine->id;
            $transaction->vending_machine_slot_id = $vending_machine_slot->id;
            $transaction->client_id = $vending_machine_slot->vendingMachine->client_id;
            $transaction->customer_id = $customer->id;
            $transaction->food_id = $vending_machine_slot->food->id;
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
            $transaction->quantity = $cart['quantity'];
            $transaction->total = $cart['quantity'] * $vending_machine_slot->food->selling_price_vending_machine;
            $transaction->status_transaction = 3; // success with not delivered
            $transaction->note= $note;
            $transaction->save();

            /** Update flaging transaksi. Digunakan untuk Smansa */
            $vending_machine = $transaction->vendingMachine;
            $vending_machine->flaging_transaction = Str::random(10);
            $vending_machine->saldo += $vending_machine_slot->food->selling_price_client;
            $vending_machine->save();

            /** Kurangi saldo customer */
            $total_belanja += $transaction->total;
            
            /** jika preorder stok tidak direcord */
            if ($is_preorder == 0) {
                ApiHelper::updateStockTransaction($transaction);
            }
        }

        /** clear temp */
        // $temp_key = PosHelper::getTempAnonimKey();
        // TempDataHelper::clear($temp_key, auth()->user()->id);

        // /** Kurangi saldo customer */
        // $customer = PosHelper::getAnonimCustomer();
        // $customer->saldo -= $total_belanja;
        // $customer->save();ƒ

        DB::commit();
        FirebaseHelper::pushFirebaseNotification($transaction,"checkout");
        return $transaction;
        // return redirect('v/cart');
    }

    /**checkout */
    public function checkout(){
        $customer = PosHelper::getAnonimCustomer();
        $clientIP = \Request::ip();

        $data=$customer->id.'-'.$clientIP;
      
        $transaction_number = VendingMachineTransaction::generateCustomNumber($data);
        $temp_key = PosHelper::getTempAnonimKey();
        TempDataHelper::clear($temp_key, auth()->user()->id);
        toaster_success('Pesanan Anda berhasil ditempatkan.');
        return redirect('v/success-order/'.$transaction_number);

    }
    /** success order */
    public function successOrder($transaction_number)
    {
        $view = view('frontend.v.pos.success-order');
        $view->list_transaction = VendingMachineTransaction::where('transaction_number', $transaction_number)->groupBy('vending_machine_id')->get();
        return $view;
    }

    /** history transaction */
    public function historyTransaction()
    {
        $status = \Input::get('status');
        $view = view('frontend.v.pos.history-transaction');
        $customer= PosHelper::getAnonimCustomer();
        $view->list_transaction = VendingMachineTransaction::where('customer_id', $customer->id)
            ->where(function ($q) use ($status) {
                if ($status) {
                    $q->where('status_transaction', $status);
                }
            })
            ->whereNotNull('transaction_number')
            ->groupBy('transaction_number')
            ->orderBy('transaction_number', 'desc')
            ->get();
        return $view;
    }

    


}
