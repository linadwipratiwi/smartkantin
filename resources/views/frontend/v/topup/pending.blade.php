@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Topup',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Topup'
            ],
        ]
    ])
    
    <!-- /Title -->
    @if ($gopay_transaction->status == 0)
    <!-- Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning">Menunggu Pembayaran untuk topup saldo sejumlah Rp. {{format_price($gopay_transaction->gopay_gross_amount)}}</div>

            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Pilih salah satu metode pembayaran Gopay</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="panel-group accordion-struct" id="accordion_1" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading activestate" role="tab" id="heading_1">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapse_1" aria-expanded="true" class="">1. Scan QR Code</a> 
                                </div>
                                <div id="collapse_1" class="panel-collapse collapse in" role="tabpanel" aria-expanded="true" style="">
                                    <div class="panel-body pa-15">
                                        <iframe src="{{$gopay_transaction->url_qrcode}}" height="800" width="800"></iframe>    
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading_2">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapse_2" aria-expanded="false">2. Buka Aplikasi Gopay </a>
                                </div>
                                <div id="collapse_2" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body pa-15"> 
                                        <a href="{{$gopay_transaction->url_deeplink}}" class="btn btn-default btn-sm">Buka aplikasi Gopay</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-success">Pembayaran untuk topup saldo sejumlah Rp. {{format_price($gopay_transaction->gopay_gross_amount)}} telah lunas</div>

    @endif
    <!-- /Row -->
@stop

@section('scripts')
    <script>
    initFormatNumber();
    fetchQRCode();

    function fetchQRCode() {
        $.ajax({
            url: '{{$gopay_transaction->url_qrcode}}',
            success: function (data) {
                console.log(data);
                $('#qr-gopay').html(data);
            }
        })
    }
    </script>
@endsection