<?php

namespace App\Helpers;

use App\User;
use App\Models\Client;
use App\Models\StockMutation;
use App\Models\PermissionUser;
use App\Models\VendingMachine;
use App\Exceptions\AppException;
use Bican\Roles\Models\Permission;

class ApiStandHelper
{
    public static function getStockAllVending($username)
    {
        /** check user */
        $user = User::where('username', $username)->first();
        if (!$user) {
            return json_encode([
                'status' => 0,
                'data' => 'User not found'
            ]);
        }

        /** check client */
        $client = Client::where('user_id', $user->id)->first();
        if (!$user) {
            return json_encode([
                'status' => 0,
                'data' => 'Client not found'
            ]);
        }

        /** get all stand */
        $list_all_stand = VendingMachine::stand()->where('client_id', $client->id)->with(['slots'])->get();
        return json_encode([
            'status' => 1,
            'data' => $list_all_stand
        ]);
    }

}