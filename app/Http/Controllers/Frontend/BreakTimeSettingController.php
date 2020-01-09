<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Models\BreakTimeSetting;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BreakTimeSettingController extends Controller
{
    public function index(Request $request)
    {
        $view = view('frontend.break-time-setting.index');
        $view->list_break_time_setting = BreakTimeSetting::paginate(25);
        return $view;
    }

    public function create()
    {
        return view('frontend.break-time-setting.create');
    }

    public function store(Request $request)
    {
        AdminHelper::createBreakTimeSetting($request);
        toaster_success('create form success');
        return redirect('front/break-time-setting');
    }

    public function edit($id)
    {
        $view = view('frontend.break-time-setting.edit');
        $view->break_time_setting = BreakTimeSetting::findOrFail($id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        AdminHelper::createBreakTimeSetting($request, $id);
        toaster_success('create form success');
        return redirect('front/break-time-setting');
    }

    public function destroy($id)
    {
        $client = BreakTimeSetting::findOrFail($id);
        $delete = AdminHelper::delete($client);
        
        toaster_success('delete form success');
        return redirect('front/break-time-setting');
    }
}
