<?php

namespace App\Http\Controllers\Backend\Ptpp;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\PTPP;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use App\Models\PTPPVerificator;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $view = view('backend.ptpp.verificator.index');
        $view->list_verification = PTPPVerificator::whereIn('status_approval_to_oh', [2, 3])->paginate(25);
        return $view;
    }

    public function create($id)
    {
        $ptpp = PTPP::findOrFail($id);
        $view = view('backend.ptpp.verificator._create');
        if ($ptpp->verificator) $view = view('backend.ptpp.verificator._index');
        $view->ptpp = $ptpp;

        return $view;
    }

    public function store(Request $request)
    {
        $verificator = AdminHelper::createPtppVerificator($request);
        $view = view('backend.ptpp.verificator._index');
        $view->ptpp = $verificator->ptpp;

        return $view;
    }

    /** Send request to OH */
    public function _sendRequestOh($id)
    {
        $ptpp = PTPPVerificator::findOrFail($id);
        $ptpp->status_approval_to_oh =  1;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_oh) . ' ' . status_approval($ptpp->status_approval_to_oh);
        return $data;
    }

    /** Cancel request to Oh */
    public function _cancelRequestOh($id)
    {
        $ptpp = PTPPVerificator::findOrFail($id);
        $ptpp->status_approval_to_oh =  0;
        $ptpp->save();

        $data = user_approval($ptpp->approval_to_oh) . ' ' . status_approval($ptpp->status_approval_to_oh);
        return $data;
    }

    public function pendingApprovalOh(Request $request)
    {
        $view = view('backend.ptpp.verificator.pending-approval');
        $view->list_verification = PTPPVerificator::where('approval_to_oh', auth()->user()->id)
            ->where('status_approval_to_oh', 1)
            ->paginate(25);
        return $view;
    }

    /** Approve Oh */
    public function approveOh($id)
    {
        $ptpp = PTPPVerificator::findOrFail($id);
        $ptpp->status_approval_to_oh = 2;
        $ptpp->approval_at_oh = Carbon::now();
        $ptpp->save();

        toaster_success('approve form success');
        return redirect(url()->previous());
    }

    /** Print */
    public function print($id)
    {
        $verificator = PTPPVerificator::findOrFail($id);

        $data = [
            'verificator' => $verificator,
            'ptpp' => $verificator->ptpp,
            'categories' => Category::ptpp()
        ];

        $pdf = \PDF::loadView('backend.ptpp.verificator.print', $data);
        return $pdf->stream();
    }
}
