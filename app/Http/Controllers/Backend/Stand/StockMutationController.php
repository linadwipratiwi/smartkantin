<?php

namespace App\Http\Controllers\Backend\Stand;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StockMutation;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;

class StockMutationController extends Controller
{
    public function index($vending_machine_id)
    {
        $view = view('backend.stand.stock._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function create($vending_machine_id)
    {
        $view = view('backend.stand.stock._create');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function show($vending_machine_id, $id)
    {
        $vending_machine_slot = VendingMachineSlot::findOrFail($id);
        return $vending_machine_slot;
    }

    public function store(Request $request, $vending_machine_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('stand/create')->withErrors($validator)
                ->withInput();
        }

        // store
        AdminHelper::createVendingMachineStock($request);

        // show index
        $view = view('backend.stand.stock._index');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        return $view;
    }

    public function edit($vending_machine_id, $id)
    {
        $view = view('backend.stand.stock._edit');
        $view->vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $view->vending_machine_slot = VendingMachineSlot::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $vending_machine_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachineStock($request);
        toaster_success('create form success');
        return redirect('stand');
    }

    public function destroy($vending_machine_id, $id)
    {
        $vending_machine = VendingMachineSlot::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('stand');
    }

    public function export(Request $request, $vending_machine_id)
    {
        $vending_machine = VendingMachine::findOrFail($vending_machine_id);
        $list_stock = StockMutation::where('vending_machine_id', $vending_machine_id)->orderBy('created_at', 'desc')->get();
        $content = array(array('TOKO', 'NAMA MAKANAN', 'QUANTITY', 'HPP', 'HARGA JUAL CLIENT', 'TANGGAL', 'JENIS TRANSAKSI'));
        foreach ($list_stock as $stock) {
            array_push($content, [$stock->vendingMachine->name, $stock->food_name, $stock->stock, format_price($stock->hpp),
            format_price($stock->selling_price_client), $stock->created_at ? date_format_view($stock->created_at) : '-', $stock->typeTransaction('excel')]);
        }

        $file_name = 'REPORT TRANSACTION' .date('YmdHis');
        $header = $vending_machine->name;
        ExcelHelper::excel($file_name, $content, $header);
    }
}
