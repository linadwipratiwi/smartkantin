<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    function index()
    {
        $produk = Produk::all();
        return $produk;
    }
}
