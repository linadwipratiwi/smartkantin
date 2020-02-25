<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function checkUser()
    {
        if (auth()->user()->isRole('administrator')) {
            return redirect('/');
        }

        if (auth()->user()->isRole('customer')) {
            return redirect('c');
        }

        if (auth()->user()->isRole('user.vending')) {
            return redirect('v');
        }
        return redirect('front');

        // return redirect('coba/'.auth()->user()->roles->first()->name);
    }
}
