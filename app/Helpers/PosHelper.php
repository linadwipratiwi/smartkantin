<?php

namespace App\Helpers;
use App\Models\UserVendingMachine;
use App\Models\VendingMachine;
use App\Models\Customer;
class PosHelper
{
    public static function getTempKey()
    {
        return 'customer.basket.'.customer()->id;
    }

    public static function getTempAnonimKey(){
        $user_stand_id=auth()->user()->id;

        // $alias=((UserVendingMachine::where('user_id',$user_stand_id))->first())->VendingMachine->alias;
        // // ->VendingMachine->alias;
        // $customer= Customer::where('identity_number',$alias)->first();

        return 'customer.basket.'.$user_stand_id;
    }

    public static function getAnonimCustomer(){
        $user_stand_id=auth()->user()->id;

        $alias=((UserVendingMachine::where('user_id',$user_stand_id))->first())->VendingMachine->alias;
        // ->VendingMachine->alias;
        $customer= Customer::where('identity_number',$alias)->first();
        return $customer;

 

    }
}
