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

        return redirect('front');
    }
}
