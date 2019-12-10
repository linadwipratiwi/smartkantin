@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Orderan Anda',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'Pos'
            ],
            1 => [
                'link' => '#',
                'label' => 'Orderan Anda'
            ],
        ]
    ])
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success">
                Silahkan bayar dan ambil pesanan Anda di Warung yang ditempat Anda beli.
            </div>

            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-right">
                        <h6 class="panel-title txt-dark">NO# {{$list_transaction->first()->transaction_number}}</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>Item</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <?php $total = 0; ?>
                                    <tbody>
                                        @foreach($list_transaction as $row => $transaction)
                                        <?php $total += $transaction->quantity * $transaction->selling_price_vending_machine;?>
                                        <tr>
                                            <td>{{$row + 1}}</td>
                                            <td>{!!$transaction->food->photo ? '<img width="50px" height="50px" src="'.asset($transaction->food->photo).'">' : '-'!!}</td>
                                            <td>{{$transaction->food->name}} <br> <i style="font-size:10px" class="text-warning">{{$transaction->vendingMachine->name}}</i></td>
                                            <td>
                                                {{$transaction->quantity}}
                                            </td>
                                            <td>{{format_price($transaction->selling_price_vending_machine)}}</td>
                                            <td>{{format_price($transaction->quantity * $transaction->selling_price_vending_machine)}}</td>
                                        </tr>
                                        @endforeach
                                        <tr style="background: #eee">
                                            <td colspan="5" class="text-right" style="font-weight:bold">Total Belanja</td>
                                            <td rowspan="2">{{format_price($total)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop

@section('scripts')
<script>
</script>
@stop