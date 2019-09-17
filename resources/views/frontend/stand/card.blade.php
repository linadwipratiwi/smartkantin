@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => 'Stand',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Stand'
            ],
        ]
    ])
    
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        @foreach($vending_machines as $row => $vending_machine)
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <article class="col-item">
                            <div class="photo">
                                <div class="options">
                                    {{-- <a href="add-products.html" class="font-18 txt-grey mr-10 pull-left"><i class="zmdi zmdi-edit"></i></a>
                                    <a href="javascript:void(0);" class="font-18 txt-grey pull-left sa-warning"><i class="zmdi zmdi-close"></i></a> --}}
                                </div>
                                
                                <a href="javascript:void(0);"> <img src="{{asset('dist/img/warung.png')}}" class="img-responsive" alt="Product Image"> </a>
                            </div>
                            <div class="info">
                                <h6>{{$vending_machine->name}}</h6>
                                <span class="head-font block text-warning font-16"><i class="fa fa-bar-chart-o"></i> {{format_price($vending_machine->totalTransactionToday(), 0)}}</span>
                                <br>
                                <a title="Grafik" onclick="showDetail('{{url("front/stand/".$vending_machine->id."/graph")}}')" data-toggle="modal"
                                    data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="zmdi zmdi-landscape"></i></button>
                                </a>
                                <a title="Produk dan Stock Opname" onclick="showDetail('{{url("front/stand/".$vending_machine->id."/product")}}')" data-toggle="modal"
                                    data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                    <button class="btn btn-success btn-icon-anim btn-square btn-sm"><i class="fa fa-cubes"></i></button>
                                </a>
                                <a title="Riwayat Transaksi" onclick="showDetail('{{url("front/stand/".$vending_machine->id."/stock")}}')" data-toggle="modal"
                                    data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                    <button class="btn btn-info btn-icon-anim btn-square btn-sm"><i class="fa fa-list"></i></button>
                                </a>
                            </div>
                        </article>
                    </div>
                </div>	
            </div>	
        </div>
        @endforeach
        
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <article class="col-item">
                            <div class="photo">
                                <div class="options">
                                    {{-- <a href="add-products.html" class="font-18 txt-grey mr-10 pull-left"><i class="zmdi zmdi-edit"></i></a>
                                    <a href="javascript:void(0);" class="font-18 txt-grey pull-left sa-warning"><i class="zmdi zmdi-close"></i></a> --}}
                                </div>
                                
                                <a href="{{url('front/stand/create')}}"> <img src="{{asset('dist/img/warung-abu.png')}}" class="img-responsive" alt="Product Image"> </a>
                            </div>
                            <div class="info">
                                <h6 style="font-weight:bold; padding-left:20px ">BUAT WARUNG BARU</h6>
                                <br><br><br><br>
                            </div>
                        </article>
                    </div>
                </div>	
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop
