<?php

namespace App\Http\Controllers\Backend\VendingMachine;

use Illuminate\Http\Request;
use App\Helpers\AdminHelper;
use App\Models\VendingMachine;
use App\Http\Controllers\Controller;

class VendingMachineSlotController extends Controller
{
    
    public function index($id)
    {
        $view = view('backend.vending-machine.slot._index');
        $view->vending_machine = VendingMachine::findOrFail($id);
        return $view;
    }
}
