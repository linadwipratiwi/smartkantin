@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'User',
        'breadcrumbs' => [
            0 => [
                'link' => url('dashboard'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Update Profile'
            ],
        ]
    ])
    
    @role('client')
        @include('backend.user.client._index')
    @endrole
    @role('customer')
        @include('backend.user.customer._index')
    @endrole
@stop