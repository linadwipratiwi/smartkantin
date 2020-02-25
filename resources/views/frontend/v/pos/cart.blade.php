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
                        <button onclick="location.href='{{url('v')}}'" class="btn btn-default btn-square btn-icon btn-icon-anim">
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
                            $temp_key = \App\Helpers\PosHelper::getTempAnonimKey();
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
                                        @if ($item->stock < $cart['quantity'])
                                        <p class="text-danger">Stok habis. Tapi bisa digunakan untuk transaksi <i>preorder</i></p>
                                        @endif
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
                            <b>Tambahkan catatan untuk penjual</b>
                        </div>
                        <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right" id="total-price">
                           <textarea name="content" placeholder="Tambahkan catatan"></textarea><br />	
                        </div> -->
                        <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="float:left;width:100%;">
                        <input type='text' placeholder="ketik catatanmu" id="customer-note" value=""  class="form-control" />
    
                        <!-- <textarea style="width:100%;" placeholder="ketik catatanmu" id="customer-note" value=""></textarea> -->
                        </div>

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
            {{-- <div class="panel" style="border:none"> --}}
            <div class="panel-wrapper" style="color: black">
                <a style="border-radius:10px" class="btn btn-primary btn-block" onclick="order()"> Order</a>
                <!-- <a href="{{url('c/checkout')}}" style="border-radius:10px" class="btn btn-primary btn-block"> Order</a> -->
                <?php 
                    $carbon = new \Carbon\Carbon(date('Y-m-d H:i:s'));
                    $carbon_limit = new \Carbon\Carbon(date('Y-m-d 08:00:00'));
                    $carbon_max = new \Carbon\Carbon(date('Y-m-d 16:00:00'));
                ?>

                <a data-toggle="modal" data-target=".preorder-modal" style="border-radius:10px" class="btn btn-warning btn-block"> Preorder</a>
                @if ($carbon <= $carbon_max && $carbon >= $carbon_limit)
                @else
                {{-- <button style="border-radius:10px" class="btn btn-warning btn-block" disabled> Pesan Nanti</button> --}}
                @endif
            </div>
            {{-- </div> --}}
            @if (!($carbon <= $carbon_max && $carbon>= $carbon_limit))
            <p class="text-info">Pesan nanti / preorder hanya tersedia pada jam 08:00 - 16:00.</p>
            @endif
        </div>
    </div>
    <!-- /Row -->

    {{-- modal --}}
    <div class="row">
        <div class="modal fade preorder-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" style="width:90% !important" id="modal-preorder">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" >Masukkan tanggal ingin diambil dan Jam istirahat</h4>
                        </div>
                        <div class="col-lg-12 mt-10" id="form-item">
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' placeholder="pilih tanggal diambil" id="preorder-date" value="{{date('m-d-Y a', strtotime($carbon->addDays(1)))}}"  class="form-control" />
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <select name="break_time_setting_id" id="" class="form-control">
                                    <option value="">Pilih jam istirahat</option>
                                    @foreach ($list_breaktime_setting as $setting)
                                        <option value="{{$setting->id}}">{{$setting->name}} - {{$setting->date_time}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="button-list">
                                <button type="button" class="btn btn-success bt-store pull-right" onclick="preorder()">Preorder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
var next_date = moment().add(1, 'day').format('YYYY-MM-DD');;
console.log(next_date);
// initDatetime('.date');
$('.date').datetimepicker({
    useCurrent: false,
    icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    },
    minDate: next_date,
}).on('dp.show', function () {
    if ($(this).data("DateTimePicker").date() === null)
        $(this).data("DateTimePicker").date(moment());
});

function preorder() {
    var preorder_date = $('#preorder-date').val();
    var note = $('#customer-note').val();
    location.href='{{url("v/checkout?preorder_date=")}}'+preorder_date+';'+note;
}


function order() {
    var note = $('#customer-note').val();
    location.href='{{url("v/checkout?preorder_date=")}};'+note;
}

/** add to cart **/
function addToCart(id, is_remove) {
    $.ajax({
        url: '{{url("v/add-to-cart/")}}/'+id+'?is_remove='+is_remove,
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