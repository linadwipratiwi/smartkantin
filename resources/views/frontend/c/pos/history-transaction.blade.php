@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Riwayat Transaksi',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'Pos'
            ],
            1 => [
                'link' => '#',
                'label' => 'Riwayat Transaksi'
            ],
        ]
    ])
    
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Riwayat Transaksi</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        @if($list_transaction->count())
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>detail</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <?php $total = 0; ?>
                                    <tbody>
                                        @foreach($list_transaction as $transaction)
                                        <?php
                                        $transaction_detail = \App\Models\VendingMachineTransaction::where('transaction_number', $transaction->transaction_number)->get();
                                        $total_detail_group = 0;

                                        ?>
                                        <tr>
                                            <td colspan="7">{{$transaction->transaction_number}}</td>
                                        </tr>
                                        @foreach ($transaction_detail as  $row => $detail)
                                        
                                        <?php $total_detail_group += $detail->total;?>
                                        <tr>
                                            <td>{{$row + 1}}</td>
                                            <td>{!!$detail->food->photo ? '<img width="50px" height="50px" src="'.asset($detail->food->photo).'">' : '-'!!}</td>
                                            <td>{{$detail->food->name}} <br> <i style="font-size:10px" class="text-warning">{{$detail->vendingMachine->name}}</i></td>
                                            <td>{{$detail->quantity}}</td>
                                            <td>{{format_price($detail->selling_price_vending_machine)}}</td>
                                            <td>{{format_price($detail->total)}}</td>
                                            <td>{!! $detail->status() !!}</td>
                                        </tr>
                                        @endforeach
                                        <tr style="background: #eee">
                                            <td colspan="5" class="text-right" style="font-weight:bold">Total Belanja</td>
                                            <td colspan="2" id="total-price">{{format_price($total_detail_group)}}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info">Riwayat transaksi Anda masih kosong. Silahkan berbelanja dulu. :-)</div>
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