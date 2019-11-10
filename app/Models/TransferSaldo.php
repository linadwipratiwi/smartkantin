<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class TransferSaldo extends Model
{
    protected $table = 'transfer_saldo';   
    public $timestamps = true;

    public function scopeFromClient($q, $to_type_id="")
    {
        $q->where('from_type', get_class(new Client))
            ->where(function ($query) use ($to_type_id) {
                if ($to_type_id) {
                    $query->where('from_type_id', $to_type_id);
                }
            });
    }

    public function scopeToCustomer($q, $to_type_id="")
    {
        $q->where('to_type', get_class(new Customer))
            ->where(function ($query) use ($to_type_id) {
                if ($to_type_id) {
                    $query->where('to_type_id', $to_type_id);
                }
            });
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
