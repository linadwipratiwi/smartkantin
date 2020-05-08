<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    function index()
    {
        $toko = Toko::find(100);
        return $toko->material;
    }
}
