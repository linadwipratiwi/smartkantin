<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\Inventory;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

    /** Store data transaction */
    public function transaction(Request $request)
    {
        return ApiHelper::transaction($request);
    }

    /** Store data customer */
    public function customer(Request $request)
    {
        return ApiHelper::createCustomer($request);
    }
}
