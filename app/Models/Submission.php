<?php

namespace App\Models;

use App\Helpers\NumberHelper;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';   
    public $timestamps = true;

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\SubmissionFile', 'submission_id');
    }

    /** Search */
    public static function search($request)
    {
        $category = \Input::get('category');
        $submission = Submission::where(function ($q) use ($category) {
            if ($category) $q->where('category_id', $category);
        })
        ->orderBy('created_at', 'desc');

        return $submission;
    }

    public static function number()
    {
        // format : SUB-IV-2019-01
        $month_roman = NumberHelper::numberToRoman(date('m'));
        $submission = self::whereMonth('created_at', date('m'))->orderBy('created_at', 'desc')->first();

        if (!$submission) {
            $number = sprintf("%03d", 1);
            return 'SUB-'.$month_roman.'-'.date('Y').'-'.$number;
        }

        $number = explode('-', $submission->number);
        $number = $number[3]+1;
        $number = sprintf("%03d", $number);

        return 'SUB-'.$month_roman.'-'.date('Y').'-'.$number;
    }
}
