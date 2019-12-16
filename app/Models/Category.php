<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;

    public function scopeFood($q)
    {
        $q->whereType('food');
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
