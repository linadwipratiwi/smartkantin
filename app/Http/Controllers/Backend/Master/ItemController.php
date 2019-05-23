<?php

namespace App\Http\Controllers\Backend\Master;

use App\Models\Item;
use App\Models\Periode;
use App\Models\Category;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ItemMaintenanceActivity;

class ItemController extends Controller
{
    public function index()
    {
        access_is_allowed('read.master.item');

        $id = \Input::get('id');
        $view = view('backend.master.item.index');
        $view->items =  $id ? Item::whereId($id)->get() : Item::orderBy('id', 'desc')->paginate(100);
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.master.item');

        return view('backend.master.item.create');
    }

    public function store(Request $request)
    {
        access_is_allowed('create.master.item');

        AdminHelper::createItem($request);

        toaster_success('create form success');
        return redirect('master/item');
    }

    public function edit($id)
    {
        access_is_allowed('update.master.item');

        $view = view('backend.master.item.edit');
        $view->item = Item::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.master.item');

        AdminHelper::createItem($request, $id);

        toaster_success('create form success');
        return redirect('master/item');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.master.item');

        $type = Item::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('master/item');
    }

    public function copy($id)
    {
        access_is_allowed('copy.master.item');

        $item = Item::findOrFail($id);
        $new_item = $item->replicate();
        $new_item->save();

        foreach ($item->itemMaintenanceActivities as $detail) {
            $item_maintenance_activity = new ItemMaintenanceActivity;
            $item_maintenance_activity->item_id = $new_item->id;
            $item_maintenance_activity->periode_id = $detail->periode_id;
            $item_maintenance_activity->category_id = $detail->category_id;
            $item_maintenance_activity->periode_value = $detail->periode_value;
            $item_maintenance_activity->name = $detail->name;
            $item_maintenance_activity->save();
        }
        
        toaster_success('copy form success');
        return redirect('master/item');

    }

    /**
     * Item Maintenance Activity
     */
    public function _list($id)
    {
        $view = view('backend.master.item._maintenance-activity');
        $view->item = Item::findOrFail($id);
        $view->categories = ItemMaintenanceActivity::where('item_id', $id)->groupBy('category_id')->get();

        return $view;
    }

    public function createItemMaintenanceActivity($item_id)
    {
        $view = view('backend.master.item._maintenance-activity-create');
        $view->item = Item::findOrFail($item_id);
        $view->categories = Category::item()->get();
        $view->periodes = Periode::all();

        return $view;
    }

    public function editItemMaintenanceActivity($id)
    {
        $view = view('backend.master.item._maintenance-activity-edit');
        $view->maintenance_activity = ItemMaintenanceActivity::findOrFail($id);
        $view->item = Item::findOrFail($view->maintenance_activity->item_id);
        $view->categories = Category::item()->get();
        $view->periodes = Periode::all();

        return $view;
    }

    public function storeItemMaintenanceActivity(Request $request)
    {
        $view = view('backend.master.item._maintenance-activity');
        $view->item = AdminHelper::createItemMaintenanceActivity($request);;
        $view->categories = ItemMaintenanceActivity::where('item_id', $view->item->id)->groupBy('category_id')->get();

        return $view;
    }
    
    public function destroyItemMaintenanceActivity($id)
    {
        $maintenance_activity = ItemMaintenanceActivity::findOrFail($id);
        $delete = AdminHelper::delete($maintenance_activity);

        return 1;
    }

    /**
     * Print QR Code for History
     */
    public function printQRcodeHistory()
    {
        $view = view('backend.master.item.print-qrcode-history');
        $view->items = Item::all();
        return $view;
        
        $data = [
            'items' => Item::all()
        ];

        $pdf = \PDF::loadView('backend.master.item.print-qrcode-history', $data);
        return $pdf->stream();
    }
}
