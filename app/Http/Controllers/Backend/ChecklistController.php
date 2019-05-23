<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Carbon\Carbon;
use App\Models\Item;
use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;
use Illuminate\Http\Request;
use App\Models\MaintenanceActivity;
use App\Http\Controllers\Controller;

class ChecklistController extends Controller
{
    public function index(Request $request)
    {
        access_is_allowed('read.checklist');

        $id = \Input::get('id');

        $view = view('backend.checklist.index');
        $view->maintenance_activities = MaintenanceActivity::search($request)->paginate(25);
        return $view;
    }

    public function create($ref_id=null)
    {
        access_is_allowed('create.checklist');

        $view = view('backend.checklist.create');
        $view->users = User::all();
        $view->reference = MaintenanceActivity::find($ref_id);

        return $view;
    }

    public function store(Request $request)
    {
        access_is_allowed('create.checklist');

        $validator = \Validator::make($request->all(), [
            'date' => 'required',
            'item_id' => 'required',
            'item_maintenance_activity_id' => 'required',
            'approval_to' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('checklist/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createMaintenanceActivity($request);
        toaster_success('create form success');
        return redirect('checklist');
    }

    public function edit($id)
    {
        access_is_allowed('update.checklist');

        $view = view('backend.checklist.edit');
        $view->maintanance_activity = MaintenanceActivity::findOrFail($id);
        $view->users = User::all();

        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.checklist');

        AdminHelper::createMaintenanceActivity($request, $id);
        toaster_success('create form success');
        return redirect('checklist');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.checklist');

        $type = MaintenanceActivity::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('checklist');
    }

    public function _sendRequest($id)
    {
        $view = view('backend.checklist._send-request');
        $view->maintenance_activity = MaintenanceActivity::findOrFail($id);
        return $view;
    }

    /** Send request  */
    public function _sendRequestChecklist($id)
    {
        $maintenance_activity = MaintenanceActivity::findOrFail($id);
        $maintenance_activity->status_approval =  1;
        $maintenance_activity->save();

        $data = user_approval($maintenance_activity->approval_to) . ' ' . status_approval($maintenance_activity->status_approval);
        return $data;
    }

    /** Cancel request */
    public function _cancelRequestChecklist($id)
    {
        $maintenance_activity = MaintenanceActivity::findOrFail($id);
        $maintenance_activity->status_approval =  0;
        $maintenance_activity->save();

        $data = user_approval($maintenance_activity->approval_to) . ' ' . status_approval($maintenance_activity->status_approval);
        return $data;
    }

    public function approve($id)
    {
        $maintenance_activity = MaintenanceActivity::findOrFail($id);
        $maintenance_activity->status_approval = 2;
        $maintenance_activity->approval_at = Carbon::now();
        $maintenance_activity->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }

    public function reminder()
    {
        $view = view('backend.checklist.reminder');
        $view->reminder_maintenance_activities = MaintenanceActivity::orderBy('date', 'desc')
            ->get()
            ->unique('item_maintenance_activity_id');
        return $view;
    }

    public function report(Request $request) 
    {
        $maintenance_activities = MaintenanceActivity::search($request)->get();
        $content = array(array('ITEM', 'CATEGORY', 'PENGECEKAN & PERIODE', 'KETERANGAN', 'TANGGAL', 'STATUS APPROVAL', 'PERSETUJUAN OLEH', 'OPERATOR'));
        foreach ($maintenance_activities as $maintenance_activity) {
            array_push($content, [$maintenance_activity->item->name, $maintenance_activity->itemMaintenanceActivity->category->name,
            $maintenance_activity->itemMaintenanceActivity->name . ' - ' .$maintenance_activity->itemMaintenanceActivity->periode->name,
            $maintenance_activity->notes . ' - ' .$maintenance_activity->status('excel'), \App\Helpers\DateHelper::formatView($maintenance_activity->date),
            $maintenance_activity->statusApproval('excel'), $maintenance_activity->approvalTo->name, $maintenance_activity->user->name]);
        }

        $file_name = 'CHEKLIST ' .date('YmdHis');
        $header = 'LAPORAN CHEKLIST ';
        ExcelHelper::excel($file_name, $content, $header);
    }

    public function sendEmail()
    {
        $data = [
            'link' => url('verify?token='),
            'username' => 'Pringgo'
        ];
        \Mail::send('layouts.email', $data,
            function ($message) {
                $message->to('odyinggo@gmail.com')->subject('Approval Checklist ');
            }
        );

        return redirect('checklist');
    }
}
