<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Food;
use App\Models\Category;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.food.index');
        $view->foods = Food::where('client_id', client()->id)->paginate(50);
        return $view;
    }

    public function create()
    {
        $view = view('frontend.food.create');
        $view->categories = Category::food()->get();
        return $view;
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('front/food/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createFood($request);
        toaster_success('create form success');
        return redirect('front/food');
    }

    public function edit($id)
    {
        $view = view('frontend.food.edit');
        $view->food = Food::findOrFail($id);
        $view->categories = Category::food()->get();

        return $view;
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        AdminHelper::createFood($request, $id);
        toaster_success('create form success');
        return redirect('front/food');
    }

    public function destroy($id)
    {
        $customer = Food::findOrFail($id);
        $delete = AdminHelper::delete($customer);
        
        toaster_success('delete form success');
        return redirect('front/food');
    }
}
