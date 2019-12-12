<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GopayTransaction extends Model
{
    protected $table = 'gopay_transactions';   
    public $timestamps = true;

    public static function init($class, $refer_id, $gross_amount)
    {
        $new = new GopayTransaction;
        $new->refer_type = get_class($class);
        $new->refer_type_id = $refer_id;
        $new->gopay_gross_amount = $gross_amount;
        $new->save();
        return $new;
    }
}
