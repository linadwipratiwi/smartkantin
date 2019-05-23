<?php

namespace App\Http\Controllers\Backend\Master;

use App\Models\Periode;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeriodeController extends Controller
{
    public function index()
    {
        access_is_allowed('read.master.periode');

        $view = view('backend.master.periode.index');
        $view->periodes =  Periode::all();
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.master.periode');

        return view('backend.master.periode.create');
    }

    public function store(Request $request)
    {
        access_is_allowed('create.master.periode');

        AdminHelper::createPeriode($request);
        toaster_success('create form success');
        return redirect('master/periode');
    }

    public function edit($id)
    {
        access_is_allowed('update.master.periode');

        $view = view('backend.master.periode.edit');
        $view->periode = Periode::findOrFail($id);
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.master.periode');

        AdminHelper::createPeriode($request, $id);
        toaster_success('create form success');
        return redirect('master/periode');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.master.periode');

        $type = Periode::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('master/periode');
    }
}
