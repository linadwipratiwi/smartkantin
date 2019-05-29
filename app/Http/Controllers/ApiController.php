<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ItemMaintenanceActivity;

class ApiController extends Controller
{
    /** Get Client for Select2 */
    public function getClient(Request $request)
    {
        $page = \Input::get('page');
        $resultCount = 100;

        $offset = ($page - 1) * $resultCount;
        
        $client = Client::where('name', 'like', '%' . \Input::get('search') . '%')
            ->orderBy('name')
            ->skip($offset)
            ->take($resultCount)
            ->get(['id', \DB::raw('name AS text')]);
        
        $count = Client::get()->count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count;

        $results = [
            "results" => $client,
            "pagination" => [
                "more" => $morePages
            ]
        ];
        return response()->json($results);
    }

    public function transaction(Request $request)
    {
        $status = ApiHelper::transaction($request);
        if (!$status) {
            ApiHelper::failed($request);
            return 0;
        }

        // save to report gate transaction
        $card = CardAccess::where('card_number', $card_id)->first();
        $gate_transaction_report = GateTransportationReport::where('card_access_id', $card->id)
            ->where('gate_id', $gate_id)
            ->where('type', 'in')
            ->where('status', 0)
            ->first();
        

        $gate_transaction_report = $gate_transaction_report ? : new GateTransportationReport;
        $gate_transaction_report->gate_id = $gate_id;
        $gate_transaction_report->card_access_id = $card->id;
        $gate_transaction_report->photo = FileHelper::createImg($photo, 'public/uploads/parkir/');
        $gate_transaction_report->police_number = FileHelper::createImg($police_number, 'public/uploads/parkir/');
        $gate_transaction_report->type = 'in';
        $gate_transaction_report->status = 0;
        $gate_transaction_report->save();

        return $gate_transaction_report;
        return 1;
    }
}
