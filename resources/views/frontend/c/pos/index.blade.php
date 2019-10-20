@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => 'Pos',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'pos'
            ]
        ]
    ])
    
    <!-- /Title -->

    <!-- Right Setting Menu -->
    <div class="setting-panel">
        <ul class="right-sidebar nicescroll-bar pa-0">
            <li class="layout-switcher-wrap">
                <ul>
                    <li>
                        <span class="layout-title">Scrollable sidebar</span>
                        <span class="layout-switcher">
                            <input type="checkbox" id="switch_3" class="js-switch"  data-color="#ea6c41" data-secondary-color="#878787" data-size="small"/>
                        </span>	
                        <h6 class="mt-30 mb-15">Theme colors</h6>
                        <ul class="theme-option-wrap">
                            <li id="theme-1" class="active-theme"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-2"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-3"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-4"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-5"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-6"><i class="zmdi zmdi-check"></i></li>
                        </ul>
                        <h6 class="mt-30 mb-15">Primary colors</h6>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-red" checked value="pimary-color-red">
                            <label for="pimary-color-red"> Red </label>
                        </div>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-blue" value="pimary-color-blue">
                            <label for="pimary-color-blue"> Blue </label>
                        </div>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-green" value="pimary-color-green">
                            <label for="pimary-color-green"> Green </label>
                        </div>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-yellow" value="pimary-color-yellow">
                            <label for="pimary-color-yellow"> Yellow </label>
                        </div>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-pink" value="pimary-color-pink">
                            <label for="pimary-color-pink"> Pink </label>
                        </div>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-orange" value="pimary-color-orange">
                            <label for="pimary-color-orange"> Orange </label>
                        </div>
                        <div class="radio mb-5">
                            <input type="radio" name="radio-primary-color" id="pimary-color-gold" value="pimary-color-gold">
                            <label for="pimary-color-gold"> Gold </label>
                        </div>
                        <div class="radio mb-35">
                            <input type="radio" name="radio-primary-color" id="pimary-color-silver" value="pimary-color-silver">
                            <label for="pimary-color-silver"> Silver </label>
                        </div>
                        <button id="reset_setting" class="btn  btn-info btn-xs btn-outline btn-rounded mb-10">reset</button>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /Right setting menu -->
    
    <button id="setting_panel_btn" class="btn btn-success btn-circle setting-panel-btn shadow-2dp"><i class="zmdi zmdi-shopping-basket"></i></button>
    <!-- Right Sidebar Backdrop -->
    <div class="right-sidebar-backdrop"></div>
    <!-- /Right Sidebar Backdrop -->

    <!-- Row -->
    <div class="row">
        @foreach ($list_food as $item)
            
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <article class="col-item">
                            <div class="photo">
                                <a href="javascript:void(0);"> <img src="{{asset($item->photo)}}" class="img-responsive" alt="Product Image" /> </a>
                            </div>
                            <div class="info">
                                <div class="product-rating inline-block">
                                    {{$item->vendingMachine->name}}
                                </div>
                                <h6>{{$item->food_name}}</h6>
                                <span class="head-font block text-warning font-16">{{format_quantity($item->selling_price_vending_machine)}}</span>
                                <button class="btn btn-warning btn-sm">Tambah</button>
                                    
                            </div>
                        </article>
                    </div>
                </div>	
            </div>	
        </div>
        @endforeach

    </div>
    <!-- /Row -->
@stop

@section('scripts')
<script>
    initDatatable('#datatable');
</script>
@stop