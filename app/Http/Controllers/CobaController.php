<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CobaController extends Controller
{
    //
    public function index($role)
    {
        $view = view('frontend.cobacoba.coba', ['role' => $role]);
        return $view;
    }
}
