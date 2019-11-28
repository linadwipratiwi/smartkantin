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
                        <span class="layout-title">PESANAN (20)</span>
                        {{-- <span class="layout-switcher">
                            <input type="checkbox" id="switch_3" class="js-switch"  data-color="#ea6c41" data-secondary-color="#878787" data-size="small"/>
                        </span>	 --}}
                        {{-- <h6 class="mt-30 mb-15">Theme colors</h6>
                        <ul class="theme-option-wrap">
                            <li id="theme-1" class="active-theme"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-2"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-3"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-4"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-5"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-6"><i class="zmdi zmdi-check"></i></li>
                        </ul> --}}
                        <!-- menu cekout -->
                        <!-- <div class="chatapp-nicescroll-bar" style="overflow-y: scroll; width: auto; height: 443px;">
                            <ul class="chat-list-wrap">
                                <li class="chat-list">
                                    <div class="chat-body">
                                        @for ($i = 0; $i < 100; $i++)
                                        <a href="javascript:void(0)">
                                            <div class="chat-data">
                                                <img class="user-img img-circle" src="https://doyanresep.com/wp-content/uploads/2018/12/cara-membuat-nasi-goreng-telur.jpg" alt="user">
                                                <div class="user-data">
                                                    <span class="name block capitalize-font">Clay Masse</span>
                                                    <span class="time block truncate txt-grey">3,000</span>
                                                    <span class="">
                                                        <label style="font-weight:bold" class="btn btn-xs btn-warning">-</label>
                                                        <label style="font-weight:bold" class="btn btn-xs btn-default">2</label>
                                                        <label style="font-weight:bold" class="btn btn-xs btn-warning">+</label>
                                                    </span>
                                                </div>
                                                <div class="status away"></div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                        @endfor

                                    </div>
                                </li>
                            </ul>
                        </div> -->
                        <div style="font-weight:bold" class="row">
                            <div class="pull-left col-lg-6 col-md-6 col-sm-6 col-xs-6">Total bayar</div>
                            <div class="pull-right col-lg-6 col-md-6 col-sm-6 col-xs-6"> Rp. 34,000</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button id="reset_setting" class="btn btn-info btn-block btn-sm btn-primary mb-10">bayar sekarang</button>
                            </div>
                        </div>
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
                                <a href="javascript:void(0);"> <img src="{{asset($item->photo)}}" id="food-photo-{{$item->id}}" width="304" height="130" alt="Product Image"/> </a>
                            </div>
                            <div class="info">
                                <div class="product-rating inline-block">
                                    {{$item->vendingMachine->name}}
                                </div>
                                <h6 id="food-name-{{$item->id}}">{{$item->food_name}}</h6>
                                <span class="head-font block text-warning font-16" id="food-price-{{$item->id}}">{{format_quantity($item->selling_price_vending_machine)}}</span>
                                <button class="btn btn-warning btn-sm" onclick="addToCart({{$item->id}})">Tambah</button>
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

    function addToCart(id) {
        $.ajax({
            url: '{{url("c/add-to-cart/")}}/'+id,
            success: function (res) {
                notification('Item telah ditambahkan ke keranjang Anda');
            }
        })
    }
</script>
@stop