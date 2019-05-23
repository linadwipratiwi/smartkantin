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
        $view = view('backend.dashboard.index');
        
        return $view;
    }
}
