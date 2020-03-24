<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\Client;
use App\Models\ClientOwner;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.client.index');
        $view->clients = Client::paginate(25);
        return $view;
    }

    public function create()
    {
        return view('backend.client.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('client/create')->withErrors($validator)
                ->withInput();
        }
        AdminHelper::createClient($request);
        toaster_success('create form success');
        return redirect('client');
    }

    public function edit($id)
    {
        $view = view('backend.client.edit');
        $view->client = Client::findOrFail($id);
        return $view;
    }
    public function editShareOwner($id)
    {
        $view = view('backend.client.edit_share_owner');
        $view->client = Client::findOrFail($id);
        $view->clients = ClientOwner::where('client_id',$id)->get();
        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);
        AdminHelper::createClient($request, $id);
        toaster_success('create form success');
        return redirect('client');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        // remove client user
        $user = User::findOrFail($client->user_id);
        $user->delete();
        // remove client
        $delete = AdminHelper::delete($client);
        toaster_success('delete form success');
        return redirect('client');
    }

    public function grid()
    {
        return view('backend.client.grid');
    }
}
