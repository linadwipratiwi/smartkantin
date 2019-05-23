<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Category;
use App\Models\Submission;
use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;
use Illuminate\Http\Request;
use App\Models\SubmissionFile;
use App\Http\Controllers\Controller;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        access_is_allowed('read.submission');

        $category = \Input::get('category');

        $view = view('backend.submission.index');
        $view->categories = Category::submission()->get();
        $view->submissions = Submission::search($request)->paginate(25);
        return $view;
    }

    public function create()
    {
        access_is_allowed('create.submission');

        $view = view('backend.submission.create');
        $view->users = User::all();
        $view->categories = Category::submission()->get();

        return $view;
    }

    public function store(Request $request)
    {
        access_is_allowed('create.submission');

        $validator = \Validator::make($request->all(), [
            'category_id' => 'required',
            'item_name' => 'required',
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('submission/create')->withErrors($validator)
                ->withInput();
        }

        $submission = AdminHelper::createSubmission($request);
        AdminHelper::createSubmissionFile($submission, $request);
        toaster_success('create form success');
        return redirect('submission');
    }

    public function edit($id)
    {
        access_is_allowed('update.submission');

        $view = view('backend.submission.edit');
        $view->submission = Submission::findOrFail($id);
        $view->users = User::all();
        $view->categories = Category::submission()->get();

        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.submission');

        $validator = \Validator::make($request->all(), [
            'category_id' => 'required',
            'item_name' => 'required',
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('update form failed');
            return redirect('submission/'.$id.'/edit')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createSubmission($request, $id);
        toaster_success('update form success');
        return redirect('submission');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.submission');

        $type = Submission::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('submission');
    }

    public function pendingApprovalOh(Request $request)
    {
        $category = \Input::get('category');

        $view = view('backend.submission.pending-approval');
        $view->categories = Category::submission()->get();
        $view->submissions = Submission::search($request)
            ->where('approval_to_oh', auth()->user()->id)
            ->where('status_approval_to_oh', 1)
            ->paginate(25);
        return $view;
    }

    public function pendingApprovalEpm(Request $request)
    {
        $category = \Input::get('category');

        $view = view('backend.submission.pending-approval');
        $view->categories = Category::submission()->get();
        $view->submissions = Submission::search($request)
            ->where('approval_to_spv_epm', auth()->user()->id)
            ->where('status_approval_to_spv_epm', 1)
            ->paginate(25);
        return $view;
    }

    /**
     * Submission File
     */
    public function _files($id)
    {
        $view = view('backend.submission._files');
        $view->submission = Submission::findOrFail($id);
        return $view;
    }

    public function _createFile($id)
    {
        $view = view('backend.submission._files-create');
        $view->submission = Submission::findOrFail($id);
        return $view;
    }

    public function _storeFile(Request $request)
    {
        $submission = Submission::findOrFail($request->submission_id);
        AdminHelper::createSubmissionFileSingle($submission, $request);
        $view = view('backend.submission._files');
        $view->submission = $submission;
        return $view;
    }

    public function _deleteFile($id)
    {
        $type = SubmissionFile::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('submission');
    }

    public function _editFile($id)
    {
        $view = view('backend.submission._files-edit');
        $view->submission_file = SubmissionFile::findOrFail($id);
        return $view;
    }

    public function _updateFile(Request $request, $id)
    {
        $submission = Submission::findOrFail($request->submission_id);
        AdminHelper::createSubmissionFileSingle($submission, $request, $id);
        $view = view('backend.submission._files');
        $view->submission = $submission;
        return $view;
    }

    /**
     * Submission Approval OH and EPM
     */
    
    /** Show form request OH and EPM */
    public function _sendRequest($id)
    {
        $view = view('backend.submission._send-request');
        $view->submission = Submission::findOrFail($id);
        return $view;
    }

    /** Send request to OH */
    public function _sendRequestOh($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status_approval_to_oh =  1;
        $submission->save();

        $data = user_approval($submission->approval_to_oh) . ' ' . status_approval($submission->status_approval_to_oh);
        return $data;
    }

    /** Cancel request to OH */
    public function _cancelRequestOh($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status_approval_to_oh =  0;
        $submission->save();

        $data = user_approval($submission->approval_to_oh) . ' ' . status_approval($submission->status_approval_to_oh);
        return $data;
    }

    public function approveOh($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status_approval_to_oh = 2;
        $submission->approval_at_oh = Carbon::now();
        $submission->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }

    /** Send request to EPM */
    public function _sendRequestEpm($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status_approval_to_spv_epm =  1;
        $submission->save();

        $data = user_approval($submission->approval_to_spv_epm) . ' ' . status_approval($submission->status_approval_to_spv_epm);
        return $data;
    }

    /** cancel request to EPM */
    public function _cancelRequestEpm($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status_approval_to_spv_epm =  0;
        $submission->save();

        $data = user_approval($submission->approval_to_spv_epm) . ' ' . status_approval($submission->status_approval_to_spv_epm);
        return $data;
    }

    public function approveEpm($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status_approval_to_spv_epm = 2;
        $submission->approval_at_spv_epm = Carbon::now();

        $submission->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }

    /** Report */
    public function report(Request $request) 
    {
        $submissions = Submission::search($request)->get();
        $content = array(array('PEMOHON', 'KATEGORI', 'NAMA BARANG', 'ALASAN PENGAJUAN', 'APPROVAL OH', 'STATUS APPROVAL OH', 'APPROVAL SPV EPM', 'STATUS APPROVAL SPV EPM', 'OPERATOR'));
        foreach ($submissions as $maintenance_activity) {
            array_push($content, [$maintenance_activity->item->name, $maintenance_activity->itemSubmission->category->name,
            $maintenance_activity->itemSubmission->name . ' - ' .$maintenance_activity->itemSubmission->periode->name,
            $maintenance_activity->notes . ' - ' .$maintenance_activity->status('excel'), \App\Helpers\DateHelper::formatView($maintenance_activity->date),
            $maintenance_activity->statusApproval('excel'), $maintenance_activity->approvalTo->name, $maintenance_activity->user->name]);
        }

        $file_name = 'CHEKLIST ' .date('YmdHis');
        $header = 'LAPORAN CHEKLIST ';
        ExcelHelper::excel($file_name, $content, $header);
    }

    /** Print */
    public function print($id)
    {
        $data = [
            'submission' => Submission::findOrFail($id)
        ];

        $pdf = \PDF::loadView('backend.submission.print', $data);
        return $pdf->stream();
    }
}
