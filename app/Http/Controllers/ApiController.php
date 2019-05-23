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
    /** Get item maintenance by Item ID in Select2 */
    public function getItemMaintenanceActivity($item_id)
    {
        $json_output = [];
        $categories = ItemMaintenanceActivity::joinCategory()->where('item_id', $item_id)->groupBy('category_id')->select('categories.*')->get();
        foreach ($categories as $category) {
            $items = ItemMaintenanceActivity::where('category_id', $category->id)->where('item_id', $item_id)->get();
            $json_item = [];
            foreach ($items as $item) {
                array_push($json_item, [
                    'id' => $item->id,
                    'text' => $item->name
                ]);
            }

            array_push($json_output, [
                'id' => md5(Str::random(20)),
                'text' => $category->name,
                'children' => $json_item
            ]);
        }

        return $json_output;
    }

    /** Get Item for Select2 */
    public function getItem(Request $request)
    {
        $page = \Input::get('page');
        $resultCount = 100;

        $offset = ($page - 1) * $resultCount;
        
        $items = Item::where('name', 'like', '%' . \Input::get('search') . '%')
            ->orderBy('name')
            ->skip($offset)
            ->take($resultCount)
            ->get(['id', \DB::raw('name AS text')]);
        
        $count = Item::get()->count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count;

        $results = [
            "results" => $items,
            "pagination" => [
                "more" => $morePages
            ]
        ];
        return response()->json($results);
    }

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

    /** Get Item for Select2 */
    public function getInventory(Request $request)
    {
        $page = \Input::get('page');
        $resultCount = 100;

        $offset = ($page - 1) * $resultCount;
        
        $items = Inventory::where('name', 'like', '%' . \Input::get('search') . '%')
            ->orderBy('name')
            ->skip($offset)
            ->take($resultCount)
            ->get(['id', \DB::raw('name AS text')]);
        
        $count = Inventory::get()->count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count;

        $results = [
            "results" => $items,
            "pagination" => [
                "more" => $morePages
            ]
        ];
        return response()->json($results);
    }
}
