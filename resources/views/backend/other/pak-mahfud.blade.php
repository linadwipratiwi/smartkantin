@extends('layouts.app')

@section('content')
<!-- Title -->
@include('backend._bread-crumb', [
    'title' => 'Pak Mahfud',
    'breadcrumbs' => [
        0 => [
            'link' => url('front'),
            'label' => 'dashboard'
        ],
        1 => [
            'link' => '#',
            'label' => 'Pak Mahfud'
        ],
    ]
])

<!-- /Title -->
@include('backend.other._filter-pak-mahfud')

@stop
