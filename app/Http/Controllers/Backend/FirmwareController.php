<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\Firmware;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FirmwareController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.firmware.index');
        $view->firmwares = Firmware::paginate(25);
        return $view;
    }

    public function create()
    {
        return view('backend.firmware.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('firmware/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createFirmware($request);
        toaster_success('create form success');
        return redirect('firmware');
    }

    public function edit($id)
    {
        $view = view('backend.firmware.edit');
        $view->firmware = Firmware::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createFirmware($request, $id);
        toaster_success('create form success');
        return redirect('firmware');
    }

    public function destroy($id)
    {
        $client = Firmware::findOrFail($id);
        $delete = AdminHelper::delete($client);
        
        toaster_success('delete form success');
        return redirect('firmware');
    }
}
