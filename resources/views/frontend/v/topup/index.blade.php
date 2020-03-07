@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Riwayat Topup',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'Pos'
            ],
            1 => [
                'link' => '#',
                'label' => 'Riwayat Topup'
            ],
        ]
    ])
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Riwayat Topup</h6>
                        <div class="pull-right">
                            <a class="btn btn-sm btn-primary " href="{{url('c/topup/create')}}">Topup Saldo via Aplikasi Gopay</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        @if($list_topup->count())
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table color-table info-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Tanggal</th>
                                        <th class="text-right">Jumlah Topup</th>
                                        <th class="text-right">Jenis Topup</th>
                                        <th class="text-right">Biaya Admin</th>
                                        <th class="text-right">Total</th>
                                        <th>Topup oleh</th>
                                    </tr>
                                    </thead>
                                    @foreach($list_topup as $i => $topup)
                                    <tr id="tr-slot-{{$topup->id}}">
                                        <td class="text-center">{{++$i}}</td>
                                        <td>{{date_format_view($topup->created_at)}}</td>
                                        <td class="text-right">{{format_price($topup->saldo)}}</td>
                                        <td>{{$topup->topup_type}}</td>
                                        <td>
                                            {{$topup->fee_topup_type == 'value' ? 'Rp. ' . format_quantity($topup->fee_topup_value): $topup->fee_topup_percent. ' %'}}
                                        </td>
                                        <td class="text-right">{{format_price($topup->total_topup)}}</td>
                                        <td>{{$topup->createdBy ? $topup->createdBy->name : 'SYSTEM'}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info">Riwayat topup Anda masih kosong. Silahkan topup dulu. :-)</div>
                        @endif
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop

@section('scripts')
<script>
function filter(status) {
    location.href="{{url('c/history-transaction?status=')}}"+status;
}
function secureDeleteCart(url, tr_callback) {
    swal({
        title: "Anda yakin ingin menghapus data?",
        text: "Data yang dihapus tidak bisa dikembalikan lagi",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#f2b701",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        deleteExecCart(url, tr_callback);
    });
}

function deleteExecCart(url, tr_callback) {
    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            $('#total-price').html(res);
            swal_success('Berhasil', 'data berhasil dihapus');
            if (tr_callback) {
                $(tr_callback).fadeOut();
            }
        },
        error: function (result) {
            swal("Failed something went wrong");
        }
    });
}
</script>
@stop