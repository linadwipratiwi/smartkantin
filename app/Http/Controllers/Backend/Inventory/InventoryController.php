<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Periode;
use App\Models\Category;
use App\Models\Inventory;
use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ItemMaintenanceActivity;

class InventoryController extends Controller
{
    public function index()
    {
        access_is_allowed('read.inventory');

        $id = \Input::get('id');
        $view = view('backend.inventory.index');
        $view->inventories = $id ? Inventory::whereId($id)->get() : Inventory::orderBy('id', 'desc')->paginate(100);
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.inventory');

        return view('backend.inventory.create');
    }

    public function store(Request $request)
    {
        access_is_allowed('create.inventory');

        AdminHelper::createInventory($request);

        toaster_success('create form success');
        return redirect('inventory');
    }

    public function edit($id)
    {
        access_is_allowed('update.inventory');

        $view = view('backend.inventory.edit');
        $view->item = Inventory::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.inventory');

        AdminHelper::createInventory($request, $id);

        toaster_success('create form success');
        return redirect('inventory');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.inventory');

        $type = Inventory::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('inventory');
    }

    public function download(Request $request) 
    {
        $id = \Input::get('id');
        $inventories = $id ? Inventory::whereId($id)->get() : Inventory::orderBy('id', 'desc')->get();
        $content = array(array('NAMA', 'STOK', 'BRAND', 'TIPE', 'TAHUN PRODUKSI', 'LOKASI', 'CATATAN'));
        foreach ($inventories as $inventory) {
            array_push($content, [$inventory->name, $inventory->stock,
            $inventory->brand, $inventory->type,
            $inventory->production_year, $inventory->notes]);
        }

        $file_name = 'INVENTORY ' .date('YmdHis');
        $header = 'LAPORAN INVENTORY ';
        ExcelHelper::excel($file_name, $content, $header);
    }
}
