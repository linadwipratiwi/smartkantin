@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Cart',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'Pos'
            ],
            1 => [
                'link' => '#',
                'label' => 'Cart'
            ],
        ]
    ])
    
    Halloo indonesiaa ini frontend file