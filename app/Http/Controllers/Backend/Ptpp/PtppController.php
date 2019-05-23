<?php

namespace App\Http\Controllers\Backend\Ptpp;

use Carbon\Carbon;
use App\Models\PTPP;
use App\Models\Category;
use App\Models\PTPPFile;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PtppController extends Controller
{
    public function index(Request $request)
    {
        access_is_allowed('read.ptpp.form');

        $view = view('backend.ptpp.form.index');
        $view->list_ptpp = PTPP::search($request)->paginate(25);
        $view->categories = Category::ptpp()->get();

        return $view;
    }

    public function create()
    {
        access_is_allowed('create.ptpp.form');

        $view = view('backend.ptpp.form.create');
        $view->categories = Category::ptpp()->get();
        return $view;
    }

    public function store(Request $request)
    {
        access_is_allowed('create.ptpp.form');

        $validator = \Validator::make($request->all(), [
            'created_at' => 'required',
            'from' => 'required',
            'to_function' => 'required',
            'location' => 'required',
            'notes' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('ptpp/create')->withErrors($validator)
                ->withInput();
        }

        $ptpp = AdminHelper::createPtpp($request);
        AdminHelper::createPtppFile($ptpp, $request);

        toaster_success('create form success');
        return redirect('ptpp');
    }

    public function edit($id)
    {
        access_is_allowed('update.ptpp.form');

        $view = view('backend.ptpp.form.edit');
        $view->ptpp = PTPP::findOrFail($id);
        $view->categories = Category::ptpp()->get();


        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.certificate');

        $validator = \Validator::make($request->all(), [
            'created_at' => 'required',
            'from' => 'required',
            'to_function' => 'required',
            'location' => 'required',
            'notes' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('update form failed');
            return redirect('ptpp/'.$id.'/edit')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createPtpp($request, $id);
        toaster_success('update form success');
        return redirect('ptpp');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.ptpp.form');

        $type = PTPP::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('ptpp');
    }

    public function pendingApprovalOh(Request $request)
    {
        $category = \Input::get('category');

        $view = view('backend.ptpp.form.pending-approval');
        $view->categories = Category::ptpp()->get();
        $view->list_ptpp = PTPP::search($request)
            ->where('approval_to_oh', auth()->user()->id)
            ->where('status_approval_to_oh', 1)
            ->paginate(25);
        return $view;
    }

    public function pendingApprovalRsd(Request $request)
    {
        $category = \Input::get('category');

        $view = view('backend.ptpp.form.pending-approval');
        $view->categories = Category::ptpp()->get();
        $view->list_ptpp = PTPP::search($request)
            ->where('approval_to_spv_rsd', auth()->user()->id)
            ->where('status_approval_to_spv_rsd', 1)
            ->paginate(25);
        return $view;
    }

    /**
     * ptpp File
     */
    public function _files($id)
    {
        $view = view('backend.ptpp.form.file._index');
        $view->ptpp = PTPP::findOrFail($id);
        return $view;
    }

    public function _createFile($id)
    {
        $view = view('backend.ptpp.form.file._create');
        $view->ptpp = PTPP::findOrFail($id);
        return $view;
    }

    public function _storeFile(Request $request)
    {
        $ptpp = PTPP::findOrFail($request->ptpp_id);
        AdminHelper::createPtppFileSingle($ptpp, $request);
        $view = view('backend.ptpp.form.file._index');
        $view->ptpp = $ptpp;
        return $view;
    }

    public function _deleteFile($id)
    {
        $type = PTPPFile::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('ptpp');
    }

    public function _editFile($id)
    {
        $view = view('backend.ptpp.form.file._edit');
        $view->ptpp_file = PTPPFile::findOrFail($id);
        return $view;
    }

    public function _updateFile(Request $request, $id)
    {
        $ptpp = PTPP::findOrFail($request->ptpp_id);
        AdminHelper::createPtppFileSingle($ptpp, $request, $id);
        $view = view('backend.ptpp.form.file._index');
        $view->ptpp = $ptpp;
        return $view;
    }

    /**
     * ptpp Approval OH and RSD
     */
    
    /** Show form request OH and RSD */
    public function _sendRequest($id)
    {
        $view = view('backend.ptpp.form._send-request');
        $view->ptpp = PTPP::findOrFail($id);
        return $view;
    }

    /** Send request to OH */
    public function _sendRequestOh($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $ptpp->status_approval_to_oh =  1;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_oh) . ' ' . status_approval($ptpp->status_approval_to_oh);
        return $data;
    }

    /** Cancel request to OH */
    public function _cancelRequestOh($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $ptpp->status_approval_to_oh =  0;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_oh) . ' ' . status_approval($ptpp->status_approval_to_oh);
        return $data;
    }

    public function approveOh($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $ptpp->status_approval_to_oh = 2;
        $ptpp->approval_at_oh = Carbon::now();
        $ptpp->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }

    /** Send request to rsd */
    public function _sendRequestRsd($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $ptpp->status_approval_to_spv_rsd =  1;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_spv_rsd) . ' ' . status_approval($ptpp->status_approval_to_spv_rsd);
        return $data;
    }

    /** cancel request to rsd */
    public function _cancelRequestRsd($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $ptpp->status_approval_to_spv_rsd =  0;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_spv_rsd) . ' ' . status_approval($ptpp->status_approval_to_spv_rsd);
        return $data;
    }

    public function approveRsd($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $ptpp->status_approval_to_spv_rsd = 2;
        $ptpp->approval_at_spv_rsd = Carbon::now();

        $ptpp->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }
}
