@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => 'Vending Machine',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Vending Machine'
            ],
        ]
    ])
    
    <!-- /Title -->
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="post" action="{{url("front/stand/".$vending_machine->id."/product/stock-opname")}}">
                                {!! csrf_field() !!}
                                <table class="table color-table info-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 50px">#</th>
                                            <th>Makanan</th>
                                            <th>Stok</th>
                                            <th>Harga Jual</th>
                                        </tr>
                                    </thead>
                                    @foreach($vending_machine->slots as $i => $slot)
                                    <tr id="tr-slot-{{$slot->id}}">
                                        <td>{!!$slot->photo ? '<img width="50px" height="50px" src="'.asset($slot->photo).'">' : '-'!!}</td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="food-name-{{$slot->id}}" class="form-control" value="{{$slot->food_name}}" name="food_name[{{$slot->id}}]"></td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="stock-{{$slot->id}}" class="form-control format-price" value="{{$slot->stock}}" name="stock[{{$slot->id}}]"></td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="price-{{$slot->id}}" class="form-control format-price" value="{{$slot->selling_price_vending_machine}}" name="selling_price_vending_machine[{{$slot->id}}]"></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </form>
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
    initFormatNumber();
    function save(id) {
        var food_name = $('#food-name-'+id).val();
        var price = $('#price-'+id).val();
        var stock = $('#stock-'+id).val();
        $.ajax({
            url: '{{url("front/stand/product/stock-opname/update")}}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { id: id, stock:stock, food_name: food_name, price: price },
            success: function(res) {
                console.log(res);
                notification('Berhasil', 'Perubahan berhasil disimpan')
            }
        })
    }
</script>
@endsection