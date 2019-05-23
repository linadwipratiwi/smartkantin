<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';   
    public $timestamps = false;

    public function scopeItem($q)
    {
        $q->whereType('item');
    }

    public function scopeSubmission($q)
    {
        $q->whereType('submission');
    }

    public function scopeCertificate($q)
    {
        $q->whereType('certificate');
    }

    public function scopePtpp($q)
    {
        $q->whereType('ptpp');
    }
}
