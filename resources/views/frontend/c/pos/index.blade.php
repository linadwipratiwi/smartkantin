@extends('layouts.app')

@section('content')
    <!-- Title -->
    
    
    <div class="row">
        <div class="col-lg-12">
            <form action="">
                <input type="text" class="form-control" style="border-radius:30px" placeholder="cari nama makanan..">
            </form>
        </div>
    </div>
    <hr style="border-style:dotted" />
    <!-- /Title -->
    <button data-toggle="modal" data-target=".bs-example-modal-sm" style="position:fixed; bottom:25px; z-index:1031" class="btn btn-primary btn-square shadow-2dp">
        <i class="zmdi zmdi-shopping-basket"></i>
    </button>
    <div id="setting_panel_btn" onclick="gotoCart()">
        @if ($cart['total_item'] > 0)
            <button  class="btn btn-success setting-panel-btn shadow-2dp">
                <div id="cart-item" style="float:right">
                    {{$cart['total_item']}} items | Rp. {{$cart['total_price']}}<br>
                    <?php $stand_name = \App\Models\VendingMachine::whereIn('id', $cart['stand_id'])->select('name')->get()->pluck('name')->toArray();
                    $str_stand_name = implode(', ', $stand_name);
                    ?>
                    {{$str_stand_name}}
                </div>
            </button>
        @endif
    </div>
        <!-- Right Sidebar Backdrop -->
    <div class="right-sidebar-backdrop"></div>
    <!-- /Right Sidebar Backdrop -->

    @include('frontend.c.pos._data-item')

    <!-- sample modal content -->
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h5 class="modal-title" id="mySmallModalLabel">Daftar Stand / Warung</h5>
                </div>
                <div class="modal-body"> 
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:10px">#</th>
                                    <th>Warung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_stand as $row => $stand_x)
                                    <tr onclick="switchStand({{$stand_x->id}})">
                                        <td>{{++$row}}</td>
                                        <td>{{$stand_x->name}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@stop

@section('scripts')
<script>
    initDatatable('#datatable');

    /** goto cart **/
    function gotoCart() {
        location.href='{{url("c/cart")}}';
    }
    
    /** switch stand **/
    function switchStand(id) {
        var top = document.getElementById('stand-'+id).offsetTop; //Getting Y of target element
        window.scrollTo(0, top);  
    }

    function addToCart(id, is_remove) {
        $.ajax({
            url: '{{url("c/add-to-cart/")}}/'+id+'?is_remove='+is_remove,
            success: function (res) {
                
                if (res.quantity > 0) {
                    var html = '<div class="row text-center" style="font-weight:bold; ">'+
                        '<div style="font-weight:bold;padding:9px; background:#f0f4f5;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+
                            '<div style="border-radius:0px; font-size:14px; font-weight:bold; color:#f2b700 !important" class="col-xs-4" onclick="addToCart('+id+', 1)"><i class="fa fa-minus"></i></div>'+
                            '<div style="border-radius:0px; font-size:14px; font-weight:bold;" class="col-xs-4">'+res.quantity+'</div>'+
                            '<div style="border-radius:0px; font-size:14px; font-weight:bold; color:#f2b700 !important" class="col-xs-4" onclick="addToCart('+id+', 0)"><i class="fa fa-plus"></i></div>'+
                        '</div>'+
                    '</div>';
                } else {
                    var html = '<button class="btn btn-warning btn-sm btn-block" style="font-size:14px; font-weight:bold;" onclick="addToCart('+id+', 0)">Tambah</button>';
                }

                var cart = '';
                if (res.total_item > 0) {
                    var cart = '<button  class="btn btn-success setting-panel-btn shadow-2dp">'+
                        '<div id="cart-item" style="float:right">'+
                            res.total_item+' items | Rp. '+res.total_price+ '<br>' +
                            res.stand_name
                        '</div>'+
                    '</button>';
                    // var cart = res.total_item+' items | Rp. '+res.total_price;
                }

                $('#btn-action-'+id).html(html);
                $('#setting_panel_btn').html(cart);
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