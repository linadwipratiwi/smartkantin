<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{

    protected $table = 'produks';

    // public function bahan()
    // {
    //     return $this->belongsToMany('App\Bahan')->withPivot('bahan_id', 'produk_id');
    // }

    // public function toko()
    // {
    //     return $this->belongsToMany('App\Toko');
    // }
}
