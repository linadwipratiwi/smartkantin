<?php

namespace App\Http\Controllers\Backend\Master;

use App\Models\Category;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        access_is_allowed('read.master.category');

        $type = \Input::get('type');
        $view = view('backend.master.category.index');
        $view->categories =  Category::where(function($q) use ($type) {
            if ($type) $q->whereType($type);
        })->paginate(25);
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.master.category');

        return view('backend.master.category.create');
    }

    public function store(Request $request)
    {
        access_is_allowed('create.master.category');

        AdminHelper::createCategory($request);
        toaster_success('create form success');
        return redirect('master/category');
    }

    public function edit($id)
    {
        access_is_allowed('update.master.category');

        $view = view('backend.master.category.edit');
        $view->category = Category::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.master.category');

        AdminHelper::createCategory($request, $id);
        toaster_success('create form success');
        return redirect('master/category');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.master.category');

        $type = Category::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('master/category');
    }
}
