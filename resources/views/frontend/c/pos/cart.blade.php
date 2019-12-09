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
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"></h6>
                        <button onclick="location.href='{{url('c')}}'" class="btn btn-default btn-square btn-icon btn-icon-anim">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <?php $total = 0; ?>
                        @foreach($list_cart_group_by_stand as $row => $cart_group)
                        <?php
                            $temp_key = \App\Helpers\PosHelper::getTempKey();
                            $list_cart_by_stand = \App\Helpers\TempDataHelper::getAllRowHaveKeyValue($temp_key, auth()->user()->id, 'stand_id', $cart_group['stand_id']);
                        ?>

                        @foreach($list_cart_by_stand as $cart)
                        <?php
                            $item = \App\Models\VendingMachineSlot::findOrFail($cart['item_id']);
                            $t = $cart['quantity'] * $cart['selling_price_item'];
                            $total += $t;

                        ?>
                        <div class="panel panel-default contact-card card-view" style="border:none">
                            <div class="panel-heading" style="color: black">
                                <div class="pull-left">
                                    <div class="pull-left user-img-wrap mr-15">
                                        {!!$item->food->photo ? '<img width="50px" class="card-user-img pull-left" height="50px" src="'.asset($item->food->photo).'">' : '-'!!}
                                    </div>
                                    <div class="pull-left user-detail-wrap">
                                        <span class="block card-user-desn" style="color: black">
                                            {{$item->vendingMachine->name}}
                                        </span>
                                        <span class="block card-user-name" style="color: black">
                                            {{$item->food->name}}
                                        </span>
                                        <span class="block card-user-desn" style="color: black">
                                            Rp. {{format_price($cart['selling_price_item'])}}
                                        </span>
                                    </div>
                                </div>
                                <div class="pull-right bg-yellow pa-10" style="border-radius:5px" id="btn-action-add-to-cart-{{$item->id}}">
                                    <a class="pull-left inline-block mr-15" style="color:#000" onclick="addToCart({{$item->id}}, 1)">
                                        <i class="fa fa-minus"></i>
                                    </a>
                                    <a class="pull-left inline-block mr-15" href="#" id="quantity-item-{{$item->id}}">
                                        {{$cart['quantity']}}
                                    </a>
                                    <a class="pull-left inline-block" onclick="addToCart({{$item->id}}, 0)">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        @endforeach
                        @if((count($list_cart_group_by_stand) - $row) > 1)
                        <hr style="border-style:dotted; border-color: #000; border-width:1px" />
                        @endif
                        <?php ++$row;?>
                        @endforeach
                    </div>
                    
                </div>
            </div>	
            <div class="panel panel-default card-view" style="border:none">
                <div class="panel-wrapper pb-10" style="color: black">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <b>Total Pembayaran</b>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right" id="total-price">
                            <b>Rp. {{format_price($total)}}</b>
                        </div>

                    </div>
                </div>
            </div>
            <br><br>
            <div class="panel" style="border:none">
                <div class="panel-wrapper" style="color: black">
                    <a href="{{url('c/checkout')}}" style="border-radius:10px" class="btn btn-warning btn-block"> Pesan</a>
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

function addToCart(id, is_remove) {
    $.ajax({
        url: '{{url("c/add-to-cart/")}}/'+id+'?is_remove='+is_remove,
        success: function (res) {
            
            if (res.quantity > 0) {
                if (res.quantity == 0) {
                    $('#quantity-item-'+id).html(res.quantity);
                } else {
                    var html = '<a class="pull-left inline-block mr-15" onclick="addToCart('+id+', 1)">'+
                        '<i class="fa fa-minus"></i>'+
                    '</a>'+
                    '<a class="pull-left inline-block mr-15" href="#" id="quantity-item-'+id+'">'+res.quantity+'</a>'+
                    '<a class="pull-left inline-block" onclick="addToCart('+id+', 0)">'+
                        '<i class="fa fa-plus"></i>'+
                    '</a>';
                    $('#btn-action-add-to-cart-'+id).html(html);
                }
            } else {
                var html = '<a class="pull-left inline-block" style="font-size:14px; font-weight:bold;" onclick="addToCart('+id+', 0)">Tambah</a>';
                $('#btn-action-add-to-cart-'+id).html(html);
            }

            $('#total-price').html("Rp. " +res.total_price);
            if (is_remove) {
                notification('Berhasil', 'Jumlah berhasil dikurangi');
            } else {
                notification('Berhasil', 'Item telah ditambahkan ke keranjang Anda');
            }
        }
    })
}
</script>
@stop