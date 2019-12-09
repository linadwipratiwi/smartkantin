<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';   
    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function subName()
    {
        if (strlen($this->name) > 18) {
            return substr($this->name, 0, 15). ' ...';
        }

        return $this->name;
    }
}
