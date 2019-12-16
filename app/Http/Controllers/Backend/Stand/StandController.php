<?php

namespace App\Http\Controllers\Backend\Stand;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Helpers\FileHelper;
use App\Models\Firmware;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class StandController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.stand.index');
        $view->vending_machines = VendingMachine::stand()->paginate(25);
        return $view;
    }

    public function create()
    {
        $view = view('backend.stand.create');
        $view->list_firmware = Firmware::firmware()->get();
        $view->list_ui = Firmware::ui()->get();
        return $view;
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('stand/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createVendingMachine($request);
        toaster_success('create form success');
        return redirect('stand');
    }

    public function edit($id)
    {
        $view = view('backend.stand.edit');
        $view->vending_machine = VendingMachine::findOrFail($id);
        $view->list_firmware = Firmware::firmware()->get();
        $view->list_ui = Firmware::ui()->get();
        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachine($request, $id);
        toaster_success('create form success');
        return redirect('stand');
    }

    public function destroy($id)
    {
        $vending_machine = VendingMachine::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('stand');
    }
}
