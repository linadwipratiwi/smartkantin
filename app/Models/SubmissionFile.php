<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    protected $table = 'submission_files';
    public $timestamps = false;

    public function link()
    {
        return '<a target="_blank" href="'.asset($this->file).'"><i class="fa fa-link"></i> Download file</a>';
    }

    public function submission()
    {
        return $this->belongsTo('App\Models\Submission', 'submission_id');
    }
}
