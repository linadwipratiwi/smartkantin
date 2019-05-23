<?php

namespace App\Http\Controllers\Backend\Master;

use App\Models\Vendor;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    public function index()
    {
        access_is_allowed('read.master.vendor');

        $view = view('backend.master.vendor.index');
        $view->vendors =  Vendor::paginate(100);
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.master.vendor');

        return view('backend.master.vendor.create');
    }

    public function store(Request $request)
    {
        access_is_allowed('create.master.vendor');

        $vendor = AdminHelper::createVendor($request);
        if ($request->ajax()) {
            return Vendor::all();
        }
        
        toaster_success('create form success');
        return redirect('master/vendor');
    }

    public function edit($id)
    {
        access_is_allowed('update.master.vendor');

        $view = view('backend.master.vendor.edit');
        $view->vendor = Vendor::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.master.vendor');

        AdminHelper::createVendor($request, $id);

        toaster_success('create form success');
        return redirect('master/vendor');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.master.vendor');

        $model = Vendor::findOrFail($id);
        $delete = AdminHelper::delete($model);
        
        toaster_success('delete form success');
        return redirect('master/vendor');
    }

}
