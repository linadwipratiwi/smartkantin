<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;
use Illuminate\Http\Request;
use App\Models\InventoryHistory;
use App\Http\Controllers\Controller;

class InventoryHistoryController extends Controller
{
    public function index()
    {
        access_is_allowed('read.inventory.history');

        $id = \Input::get('id');
        $view = view('backend.inventory.history.index');
        $view->list_inventory_stock_opname = $id ? InventoryHistory::where('inventory_id', $id)->orderBy('date', 'desc')->paginate(100) : InventoryHistory::orderBy('date', 'desc')->paginate(100);
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.inventory.history');

        return view('backend.inventory.history.create');
    }

    public function store(Request $request)
    {
        access_is_allowed('create.inventory.history');

        AdminHelper::createInventoryHistory($request);

        toaster_success('create form success');
        return redirect('inventory/history');
    }

    public function edit($id)
    {
        access_is_allowed('update.inventory.history');

        $view = view('backend.inventory.history.edit');
        $view->history = InventoryHistory::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.inventory.history');

        AdminHelper::createInventoryHistory($request, $id);

        toaster_success('create form success');
        return redirect('inventory/history');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.inventory.history');

        $type = InventoryHistory::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('inventory/history');
    }

    public function download(Request $request) 
    {
        $id = \Input::get('id');
        $inventory_histories = $id ? InventoryHistory::where('inventory_id', $id)->orderBy('date', 'desc')->get() : InventoryHistory::orderBy('date', 'desc')->get();
        $content = array(array('NAMA', 'STOK', 'TANGGAL'));
        foreach ($inventory_histories as $history) {
            array_push($content, [$history->inventory->name, $history->stock, $history->date]);
        }

        $file_name = 'INVENTORY HISTORY ' .date('YmdHis');
        $header = 'LAPORAN INVENTORY HISTORY ';
        ExcelHelper::excel($file_name, $content, $header);
    }
}
