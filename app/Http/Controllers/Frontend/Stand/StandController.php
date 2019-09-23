<?php

namespace App\Http\Controllers\Frontend\Stand;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Helpers\FileHelper;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class StandController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.stand.card');
        $view->vending_machines = VendingMachine::stand()->clientId(client()->id)->paginate(25);
        return $view;
    }

    public function create()
    {
        return view('frontend.stand.create');
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

        AdminHelper::createStand($request);
        toaster_success('create form success');
        return redirect('front/stand');
    }

    public function edit($id)
    {
        $view = view('frontend.stand.edit');
        $view->vending_machine = VendingMachine::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createStand($request, $id);
        toaster_success('create form success');
        return redirect('front/stand');
    }

    public function destroy($id)
    {
        $vending_machine = VendingMachine::findOrFail($id);
        // remove client
        $delete = AdminHelper::delete($vending_machine);
        
        toaster_success('delete form success');
        return redirect('front/stand');
    }

    public function _formVideo($id)
    {
        $view = view('frontend.stand._form-video');
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
