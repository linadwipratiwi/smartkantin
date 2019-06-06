<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $type = \Input::get('type');
        $view = view('backend.category.index');
        $view->categories =  Category::where(function($q) use ($type) {
            if ($type) $q->whereType($type);
        })->paginate(25);
        return $view;
    }

    public function create()
    {
        return view('backend.category.create');
    }

    public function store(Request $request)
    {
        AdminHelper::createCategory($request);
        toaster_success('create form success');
        return redirect('category');
    }

    public function edit($id)
    {
        $view = view('backend.category.edit');
        $view->category = Category::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        AdminHelper::createCategory($request, $id);
        toaster_success('create form success');
        return redirect('category');
    }

    public function destroy($id)
    {
        $type = Category::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('category');
    }
}
