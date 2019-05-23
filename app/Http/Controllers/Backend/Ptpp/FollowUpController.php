<?php

namespace App\Http\Controllers\Backend\Ptpp;

use Carbon\Carbon;
use App\Models\PTPP;
use App\Models\Category;
use App\Helpers\AdminHelper;
use App\Models\PTPPFollowUp;
use Illuminate\Http\Request;
use App\Exceptions\AppException;
use App\Models\PTPPFollowUpFile;
use App\Http\Controllers\Controller;

class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        access_is_allowed('read.ptpp.follow.up');

        $view = view('backend.ptpp.follow-up.index');
        $view->list_ptpp_follow_ups = PTPPFollowUp::paginate(25);

        return $view;
    }

    public function create(Request $request)
    {
        access_is_allowed('create.ptpp.follow.up');

        $view = view('backend.ptpp.follow-up.create');
        $view->list_ptpp = PTPP::search($request)->whereNotIn('id', function($query){
            $query->select('ptpp_id')
                ->from(with(new PTPPFollowUp)->getTable());
            })->paginate(25);
        $view->categories = Category::ptpp()->get();

        return $view;
    }

    public function createStep1($id)
    {
        access_is_allowed('create.ptpp.follow.up');

        $view = view('backend.ptpp.follow-up.create-step-1');
        $view->ptpp = PTPP::findOrFail($id);
        
        /** Check existing ptpp in follow up */
        $ptpp_follow = PTPPFollowUp::where('ptpp_id', $id)->first();
        if ($ptpp_follow) {
            throw new AppException("Form PTPP sudah pernah ditarik", 503);
        }

        return $view;
    }

    public function store(Request $request)
    {
        access_is_allowed('create.ptpp.follow.up');

        $validator = \Validator::make($request->all(), [
            'ptpp_id' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('ptpp/follow-up/create')->withErrors($validator)
                ->withInput();
        }

        $follow_up = AdminHelper::createFollowUp($request);
        AdminHelper::createFollowUpDetail($follow_up, $request);
        AdminHelper::createFollowUpFile($follow_up, $request);

        toaster_success('create form success');
        return redirect('ptpp/follow-up');
    }

    public function show($id)
    {
        // access_is_allowed('read.ptpp.follow.up');

        $view = view('backend.ptpp.follow-up._show');
        $view->follow_up = PTPPFollowUp::findOrFail($id);
        $view->ptpp = $view->follow_up->ptpp;
        
        return $view;
    }

    public function edit($id)
    {
        access_is_allowed('update.ptpp.follow.up');

        $view = view('backend.ptpp.follow-up.edit');
        $view->follow_up = PTPPFollowUp::findOrFail($id);
        $view->ptpp = $view->follow_up->ptpp;
        
        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.ptpp.follow.up');

        $validator = \Validator::make($request->all(), [
            'ptpp_id' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            toaster_error('update form failed');
            return redirect('ptpp/follow-up/'.$id.'/edit')->withErrors($validator)
                ->withInput();
        }

        $follow_up = AdminHelper::createFollowUp($request, $id);
        AdminHelper::createFollowUpDetail($follow_up, $request, $id);
        AdminHelper::createFollowUpFile($follow_up, $request, $id);
        toaster_success('update form success');
        return redirect('ptpp/follow-up');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.ptpp.follow.up');

        $type = PTPPFollowUp::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('ptpp/follow-up');
    }

    /** Page show pending approval EPM */
    public function pendingApprovalEpm(Request $request)
    {
        $view = view('backend.ptpp.follow-up.pending-approval');
        $view->list_ptpp_follow_ups = PTPPFollowUp::where('approval_to_spv_epm', auth()->user()->id)
            ->where('status_approval_to_spv_epm', 1)
            ->paginate(25);
        return $view;
    }

    /** Show form request EPM */
    public function _sendRequest($id)
    {
        $view = view('backend.ptpp.follow-up._send-request');
        $view->follow_up = PTPPFollowUp::findOrFail($id);
        return $view;
    }

    /** Send request to EPM */
    public function _sendRequestEpm($id)
    {
        $ptpp = PTPPFollowUp::findOrFail($id);
        $ptpp->status_approval_to_spv_epm =  1;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_spv_rsd) . ' ' . status_approval($ptpp->status_approval_to_spv_epm);
        return $data;
    }

    /** Cancel request to EPM */
    public function _cancelRequestEpm($id)
    {
        $ptpp = PTPPFollowUp::findOrFail($id);
        $ptpp->status_approval_to_spv_epm =  0;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_spv_epm) . ' ' . status_approval($ptpp->status_approval_to_spv_epm);
        return $data;
    }

    /** Approve EPM */
    public function approveEpm($id)
    {
        $ptpp = PTPPFollowUp::findOrFail($id);
        $ptpp->status_approval_to_spv_epm = 2;
        $ptpp->approval_at_spv_epm = Carbon::now();
        $ptpp->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }
}
