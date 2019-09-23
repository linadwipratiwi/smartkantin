<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendingMachineSlot extends Model
{
    protected $table = 'vending_machine_slots';   
    public $timestamps = false;

    public function vendingMachine()
    {
        return $this->belongsTo('App\Models\VendingMachine', 'vending_machine_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function profitPlatform()
    {
        $type = $this->profit_platform_type;
        if ($type == 'value') {
            return '<span class="label label-info capitalize-font inline-block ml-10">'.format_price($this->profit_platform_value).'</span>';
        }

        return '<span class="label label-info capitalize-font inline-block ml-10">'.format_quantity($this->profit_platform_percent).' %</span>';

    }
}
