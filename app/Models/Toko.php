<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{

    protected $table = 'tokos';

    public function material()
    {
        // return $this->hasMany('App\Model\Material');
        return $this->belongsToMany('App\Models\Material');
    }

    public function produk()
    {
        // return $this->hasMany('App\Model\Produk');
        return $this->belongsToMany('App\Models\Produk');
    }
}
