<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\PTPP;
use App\Models\Vendor;
use App\Models\PTPPFollowUp;
use App\Models\Submission;
use App\Models\Certificate;
use App\Models\PTPPVerificator;
use Illuminate\Http\Request;
use App\Models\MaintenanceActivity;
use App\Http\Controllers\Controller;
use App\Models\MaintenanceActivityHistory;

class BackendController extends Controller
{
    public function index(Request $request)
    {
        $now = new Carbon;
        $view = view('backend.dashboard.index');
        $view->maintenance_activities = MaintenanceActivity::where('status_approval', 1)
            ->where('approval_to', auth()->user()->id)
            ->orderBy('date', 'desc')
            ->paginate(25);
        $view->reminder_maintenance_activities = MaintenanceActivity::orderBy('date', 'desc')
            ->paginate(25)
            ->unique('item_maintenance_activity_id');
        $view->maintenance_activity_histories = MaintenanceActivityHistory::where('status_approval', 1)
            ->where('approval_to', auth()->user()->id)
            ->orderBy('date', 'desc')
            ->paginate(25);
        $view->submissions = Submission::where('status_approval_to_oh', 1)
            ->where('approval_to_oh', auth()->user()->id)
            ->paginate(25);

        /** PTPP */
        $view->list_ptpp_form_pending_oh = PTPP::where('approval_to_oh', auth()->user()->id)
            ->where('status_approval_to_oh', 1)
            ->paginate(25);
        $view->list_ptpp_form_pending_rsd = PTPP::where('approval_to_spv_rsd', auth()->user()->id)
            ->where('status_approval_to_spv_rsd', 1)
            ->paginate(25);
        $view->list_ptpp_follow_up_pending_epm = PTPPFollowUp::where('approval_to_spv_epm', auth()->user()->id)
            ->where('status_approval_to_spv_epm', 1)
            ->paginate(25);
        $view->list_ptpp_verificator_oh = PTPPVerificator::where('approval_to_oh', auth()->user()->id)
            ->where('status_approval_to_oh', 1)
            ->paginate(25);
        /** Count all data */
        $view->total_checklist = MaintenanceActivity::count();
        $view->total_history = MaintenanceActivityHistory::count();
        $view->total_submission = Submission::count();
        $view->total_ptpp = PTPP::count();
        $view->total_vendor = Vendor::count();
        $view->total_certificate = Certificate::count();
        $view->total_user = User::count();
        $view->total_item = Item::count();
        return $view;

    }

    /**
     * Show form reject for all Form {Checklist, History, Sumission, etc}
     */
    public function rejectForm()
    {
        
        $data = [
            'model' => \Input::get('model'),
            'id' => \Input::get('id'),
            'approval_to_field' => \Input::get('approval_to_field'),
            'status_approval_field' => \Input::get('status_approval_field'),
            'notes_approval_field' => \Input::get('notes_approval_field'),
            'approval_at' => \Input::get('approval_at'),
            'url_callback' => \Input::get('url_callback'),
        ];

        $view = view('backend._reject-form');
        $view->data = $data;

        return $view;
    }

    /**
     * Process reject form
     */
    public function reject(Request $request)
    {
        $model = $request->model;
        $id = $request->id;
        $approval_to_field = $request->approval_to_field;
        $status_approval_field = $request->status_approval_field;
        $notes_approval_field = $request->notes_approval_field;
        $approval_at_field = $request->approval_at_field;
        
        $namespace = 'App\\Models\\'.$model;
        $model = $namespace::findOrFail($id);
        $model->$status_approval_field =  3; // reject
        $model->$notes_approval_field =  $request->approval_notes;
        $model->$approval_at_field =  Carbon::now();
        $model->save();

        return $request->url_callback;
    }
}
