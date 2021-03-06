<?php

namespace App\Http\Controllers\Frontend\VendingMachine;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Helpers\FileHelper;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class VendingMachineController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.vending-machine.card');
        $view->vending_machines = VendingMachine::vending()->clientId(client()->id)->paginate(25);
        return $view;
    }

    public function create()
    {
        return view('frontend.vending-machine.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('vending-machine/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createVendingMachine($request);
        toaster_success('create form success');
        return redirect('vending-machine');
    }

    public function edit($id)
    {
        $view = view('frontend.vending-machine.edit');
        $view->vending_machine = VendingMachine::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createVendingMachine($request, $id);
        toaster_success('create form success');
        return redirect('vending-machine');
    }

    public function destroy($id)
    {
        $vending_machine = VendingMachine::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('vending-machine');
    }

    public function _formVideo($id)
    {
        $view = view('frontend.vending-machine._form-video');
        $view->vending_machine = VendingMachine::findOrFail($id);

        return $view;
    }

    public function storeVideo(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required'
        ]);

        $file = $request->input('file');
        $vending_machine = VendingMachine::findOrFail($id);
        $vending_machine->video = $file;
        $vending_machine->save();

        toaster_success('upload success');
        return 1;
    }
}
