<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Carbon\Carbon;
use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\MaintenanceActivity;
use App\Http\Controllers\Controller;
use App\Models\MaintenanceActivityHistory;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        access_is_allowed('read.history');

        $view = view('backend.history.index');
        $view->maintenance_activity_histories = MaintenanceActivityHistory::search($request)->paginate(25);
        return $view;
    }

    public function create($ref_id=null)
    {
        access_is_allowed('create.history');

        $view = view('backend.history.create');
        $view->users = User::all();
        $view->reference = MaintenanceActivity::find($ref_id);

        return $view;
    }

    public function store(Request $request)
    {
        access_is_allowed('create.history');

        $validator = \Validator::make($request->all(), [
            'date' => 'required',
            'item_id' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('history/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createMaintenanceActivityHistory($request);
        toaster_success('create form success');
        return redirect('history');
    }

    public function edit($id)
    {
        access_is_allowed('update.history');

        $view = view('backend.history.edit');
        $view->maintanance_activity_history = MaintenanceActivityHistory::findOrFail($id);
        $view->users = User::all();
        $view->reference = MaintenanceActivity::find($view->maintanance_activity_history->maintenance_activity_id);

        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.history');

        AdminHelper::createMaintenanceActivityHistory($request, $id);
        toaster_success('create form success');
        return redirect('history');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.history');

        $type = MaintenanceActivityHistory::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('history');
    }

    public function _reference($id)
    {
        $view = view('backend.history._detail');
        $view->history = MaintenanceActivityHistory::find($id);
        $view->reference = $view->history->maintenanceActivity ? : null;
        return $view;
    }

    public function _sendRequest($id)
    {
        $view = view('backend.history._send-request');
        $view->maintenance_activity = MaintenanceActivityHistory::findOrFail($id);
        return $view;
    }

    /** Send request  */
    public function _sendRequestHistory($id)
    {
        $maintenance_activity = MaintenanceActivityHistory::findOrFail($id);
        $maintenance_activity->status_approval =  1;
        $maintenance_activity->save();

        $data = user_approval($maintenance_activity->approval_to) . ' ' . status_approval($maintenance_activity->status_approval);
        return $data;
    }

    /** Cancel request */
    public function _cancelRequestHistory($id)
    {
        $maintenance_activity = MaintenanceActivityHistory::findOrFail($id);
        $maintenance_activity->status_approval =  0;
        $maintenance_activity->save();

        $data = user_approval($maintenance_activity->approval_to) . ' ' . status_approval($maintenance_activity->status_approval);
        return $data;
    }

    public function approve($id)
    {
        $maintenance_activity = MaintenanceActivityHistory::findOrFail($id);
        $maintenance_activity->status_approval = 2;
        $maintenance_activity->approval_at = Carbon::now();
        $maintenance_activity->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }

    public function report(Request $request) 
    {
        $maintenance_activity_histories = MaintenanceActivityHistory::search($request)->get();
        $content = array(array('ITEM', 'TANGGAl', 'KETERANGAN', 'PELAKSANA', 'STATUS APPROVAL', 'PERSETUJUAN OLEH', 'OPERATOR'));
        foreach ($maintenance_activity_histories as $maintenance_activity_history) {
            array_push($content, [$maintenance_activity_history->item->name, \App\Helpers\DateHelper::formatView($maintenance_activity_history->date),
            $maintenance_activity_history->notes, $maintenance_activity_history->executor('excel'),
            $maintenance_activity_history->statusApproval('excel'), $maintenance_activity_history->approvalTo->name, $maintenance_activity_history->user->name]);
        }

        $file_name = 'HISTORY ' .date('YmdHis');
        $header = 'LAPORAN HISTORY ';
        ExcelHelper::excel($file_name, $content, $header);
    }

    /**
     * Print QR Code for History
     */
    public function reportItem($id)
    {
        $data = [
            'histories' => MaintenanceActivityHistory::where('item_id', $id)->get(),
            'item' => Item::find($id),
        ];

        $pdf = \PDF::loadView('backend.history.report-item-pdf', $data);
        return $pdf->download('History - '.$data['item']->name.'.pdf');
    }
}
