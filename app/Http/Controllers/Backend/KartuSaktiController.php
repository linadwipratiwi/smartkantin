<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\KartuSakti;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KartuSaktiController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.kartu-sakti.index');
        $view->list_kartu_sakti = KartuSakti::paginate(25);
        return $view;
    }

    public function create()
    {
        return view('backend.kartu-sakti.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'card_number' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('kartu-sakti/create')->withErrors($validator)->withInput();
        }

        AdminHelper::createKartuSakti($request);
        toaster_success('create form success');
        return redirect('kartu-sakti');
    }

    public function edit($id)
    {
        $view = view('backend.kartu-sakti.edit');
        $view->kartu_sakti = KartuSakti::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createKartuSakti($request, $id);
        toaster_success('create form success');
        return redirect('kartu-sakti');
    }

    public function destroy($id)
    {
        $client = KartuSakti::findOrFail($id);
        $delete = AdminHelper::delete($client);
        
        toaster_success('delete form success');
        return redirect('kartu-sakti');
    }
}
