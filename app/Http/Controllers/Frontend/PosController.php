<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\VendingMachine;
use App\Models\VendingMachineSlot;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index()
    {
        $view = view('frontend.c.pos.index');
        $view->list_stand = VendingMachine::clientId(customer()->client->id)->stand()->get();
        $view->list_food = VendingMachineSlot::whereNotNull('category_id')->get();
        $view->categories = Category::food()->get();

        return $view;
    }
}
