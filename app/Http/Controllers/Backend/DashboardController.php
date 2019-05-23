<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.dashboard.index');
        return $view;
    }
}
