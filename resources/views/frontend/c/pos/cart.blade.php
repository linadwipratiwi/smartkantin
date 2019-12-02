@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Cart',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'Pos'
            ],
            1 => [
                'link' => '#',
                'label' => 'Cart'
            ],
        ]
    ])
    
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-right">
                        <h6 class="panel-title txt-dark"></h6>
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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <?php $total = 0; ?>
                                    <tbody>
                                        @foreach($list_cart as $row => $cart)
                                        <?php
                                        $item = \App\Models\VendingMachineSlot::findOrFail($cart['item_id']);
                                        $t = $cart['quantity'] * $cart['selling_price_item'];
                                        $total += $t;
                                        ?>
                                        <tr id="tr-{{$cart['rowid']}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{!!$item->food->photo ? '<img width="50px" height="50px" src="'.asset($item->food->photo).'">' : '-'!!}</td>
                                            <td>{{$item->food->name}} <br> <i style="font-size:10px" class="text-warning">{{$item->vendingMachine->name}}</i></td>
                                            <td>{{$cart['quantity']}}</td>
                                            <td>{{format_price($cart['selling_price_item'])}}</td>
                                            <td>{{format_price($cart['quantity'] * $cart['selling_price_item'])}}</td>
                                            <td>
                                                <a onclick="secureDelete('{{url('front/cart/'.$cart['rowid'])}}', '#tr-{{$cart['rowid']}}')" data-toggle="tooltip" data-original-title="Close">
                                                    <a href="{{url('c/checkout')}}" class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></a>                                                    
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr style="background: #eee">
                                            <td colspan="5" class="text-right" style="font-weight:bold">Total Belanja</td>
                                            <td>{{format_price($total)}}</td>
                                            <td><button class="btn btn-primary">Masukkan ke tagihan saya</button></td>
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