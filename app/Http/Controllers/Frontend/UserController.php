<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Helpers\FileHelper;
use App\Helpers\AdminHelper;
use Bican\Roles\Models\Role;
use Illuminate\Http\Request;
use App\Models\PermissionUser;
use App\Models\VendingMachine;
use App\Exceptions\AppException;
use App\Models\UserVendingMachine;
use Bican\Roles\Models\Permission;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $view = view('frontend.user.index');
        $view->users = UserVendingMachine::whereCreatedBy(auth()->user()->id)->get();
        return $view;
    }

    public function create()
    {
        $view = view('frontend.user.create');
        $view->vending_machines = VendingMachine::whereClientId(client()->id)->get();
        
        return $view;
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->username = $request->username;
        $file = $request->file('file');
        if (isset($file)) {
            $user->photo = FileHelper::upload($file, 'uploads/users/');
        }
        
        try {
            $user->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        $user->attachRole(4);
        $role = Role::find(4);

        $user_vending = new UserVendingMachine;
        $user_vending->user_id = $user->id;
        $user_vending->vending_machine_id = $request->vending_machine_id;
        $user_vending->created_by = auth()->user()->id;
        $user_vending->save();

        \DB::commit();
        toaster_success('create form success');
        return redirect('front/user');
    }

    public function edit($id)
    {
        $view = view('frontend.user.edit');
        $view->user_vending  = UserVendingMachine::findOrFail($id);
        $view->vending_machines = VendingMachine::whereClientId(client()->id)->get();
        return $view;
    }

    public function update(Request $request, $id)
    {
        $user_vending  = UserVendingMachine::findOrFail($id);
        
        \DB::beginTransaction();
        $user = User::findOrFail($user_vending->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $file = $request->file('file');
        if (isset($file)) {
            $user->photo = FileHelper::upload($file, 'uploads/users/');
        }

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        try {
            $user->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }

        $user_vending->user_id = $user->id;
        $user_vending->vending_machine_id = $request->vending_machine_id;
        $user_vending->created_by = auth()->user()->id;
        $user_vending->save();

        \DB::commit();
        toaster_success('create form success');
        return redirect('front/user');
    }

    public function destroy($id)
    {
        $user = UserVendingMachine::findOrFail($id);
        $delete = AdminHelper::delete($user);
        
        toaster_success('delete form success');
        return redirect('front/user');
    }
}
