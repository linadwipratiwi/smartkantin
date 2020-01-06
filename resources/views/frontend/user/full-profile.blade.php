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
    
    <div class="row">
        <div class="col-lg-3 col-xs-12">
            <div class="panel panel-default card-view  pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body  pa-0">
                        <div class="profile-box">
                            <div class="profile-cover-pic">
                                <div class="profile-image-overlay"></div>
                            </div>
                            <div class="profile-info text-center">
                                <div class="profile-img-wrap">
                                    <img class="inline-block mb-10" src="../img/mock1.jpg" alt="user">
                                </div>	
                                <h5 class="block mt-10 mb-5 weight-500 capitalize-font txt-danger">Madalyn Rascon</h5>
                                <h6 class="block capitalize-font pb-20">Developer Geek</h6>
                            </div>	
                            <div class="social-info">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <span class="counts block head-font"><span class="counter-anim">345</span></span>
                                        <span class="counts-text block">post</span>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <span class="counts block head-font"><span class="counter-anim">246</span></span>
                                        <span class="counts-text block">followers</span>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <span class="counts block head-font"><span class="counter-anim">898</span></span>
                                        <span class="counts-text block">tweets</span>
                                    </div>
                                </div>
                                <button class="btn btn-default btn-block btn-outline btn-anim mt-30" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i><span class="btn-text">edit profile</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-xs-12">
            @role('client')
            @include('backend.user._profile-client')
            @endrole
        </div>
    </div>
@stop