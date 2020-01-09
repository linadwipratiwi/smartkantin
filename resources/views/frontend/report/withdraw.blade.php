@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Saldo Topup',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Saldo Topup'
            ],
        ]
    ])
    
    <!-- /Title -->
    <!-- Row -->
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default card-view" style="background: #488e8b; color: #fff">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <label for="">Topup saldo sudah dicairkan</label>
                        <div class="form-wrap" style="font-size:20px">
                            Rp. {{format_price($total_sudah_diambil)}}
                        </div>
                    </div>
                </div>
            </div>	
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default card-view" style="background: #bf2c5b; color: #fff">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <label for="">Topup saldo belum dicairkan</label>
                        <div class="form-wrap" style="font-size:20px">
                            Rp. {{format_price($total_belum_diambil)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
@stop