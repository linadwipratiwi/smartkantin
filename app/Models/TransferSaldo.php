<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Customer;
use App\Helpers\DateHelper;
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

    public function toType()
    {
        $model = $this->to_type::find($this->to_type_id);
        if (!$model) return "-";

        return $model;
    }

    public function scopeSearch($q)
    {
        $type = \Input::get('type');
        if ($type == 'today' || $type == null) {
            $q->whereDate('created_at', Carbon::today());
        }

        if ($type == 'yesterday') {
            $q->whereDate('created_at', Carbon::yesterday()->format('Y-m-d'));
        }

        if ($type == 'month') {
            $q->whereMonth('created_at', date('m'));
            $q->whereYear('created_at', date('Y'));
        }

        if ($type == 'select-month') {
            $month = \Input::get('month');
            
            $q->whereMonth('created_at', $month);
            $q->whereYear('created_at', date('Y'));
        }

        if ($type == 'custom') {
            $date = explode('-', \Input::get('date'));
            $date_start = DateHelper::formatDB(trim($date[0]), 'start');
            $date_end = DateHelper::formatDB(trim($date[1]), 'end');

            $q->whereBetween('created_at', [$date_start, $date_end]);
            
        }

        return $q;
    }
}
