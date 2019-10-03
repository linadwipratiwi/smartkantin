@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => $vending_machine->name,
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => $vending_machine->name
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
                            <form method="post" action="{{url("front/vending-machine/".$vending_machine->id."/product/stock-opname")}}">
                                {!! csrf_field() !!}
                                <table class="table color-table info-table">
                                    <thead>
                                        <tr>
                                            <th>Slot</th>
                                            <th>Makanan</th>
                                            <th>Stok</th>
                                            <th>HPP</th>
                                            <th>Harga Jual Penjual</th>
                                            <th>Harga Jual Platform</th>
                                        </tr>
                                    </thead>
                                    @foreach($vending_machine->slots as $i => $slot)
                                    <tr id="tr-slot-{{$slot->id}}">
                                        <input type="hidden" readonly id="profit-platform-type-{{$slot->id}}" class="form-control" value="{{$slot->profit_platform_type}}" name="profit_platform_type[{{$slot->id}}]">
                                        <input type="hidden" readonly id="profit-platform-value-{{$slot->id}}" class="form-control" value="{{$slot->profit_platform_value}}" name="profit_platform_value[{{$slot->id}}]">
                                        <input type="hidden" readonly id="profit-platform-percent-{{$slot->id}}" class="form-control" value="{{$slot->profit_platform_percent}}" name="profit_platform_percent[{{$slot->id}}]">
                                        {{-- <td>{!!$slot->photo ? '<img width="50px" height="50px" src="'.asset($slot->photo).'">' : '-'!!}</td> --}}
                                        <td>{{$slot->name}}</td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="food-name-{{$slot->id}}" class="form-control" value="{{$slot->food_name}}" name="food_name[{{$slot->id}}]"></td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="stock-{{$slot->id}}" class="form-control format-price" value="{{$slot->stock}}" name="stock[{{$slot->id}}]"></td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="hpp-{{$slot->id}}" class="form-control format-price" value="{{$slot->hpp}}" name="hpp[{{$slot->id}}]"></td>
                                        <td><input type="text" onkeyup="calculate({{$slot->id}})" onchange="save({{$slot->id}})" id="selling-price-client-{{$slot->id}}" class="form-control format-price" value="{{$slot->selling_price_client}}" name="selling_price_client[{{$slot->id}}]"></td>
                                        <td><input type="text" readonly onchange="save({{$slot->id}})" id="selling-price-vending-machine-{{$slot->id}}" class="form-control format-price" value="{{$slot->selling_price_vending_machine}}" name="selling_price_vending_machine[{{$slot->id}}]"></td>
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
    function calculate(id) {
        var food_name = $('#food-name-'+id).val();
        var price_platform = dbNum($('#selling-price-vending-machine-'+id).val());
        var price_client = dbNum($('#selling-price-client-'+id).val());
        var stock = dbNum($('#stock-'+id).val());
        var hpp = dbNum($('#hpp-'+id).val());
        
        var profit_type = $('#profit-platform-type-'+id).val();
        var profit_value = dbNum($('#profit-platform-value-'+id).val());
        var profit_percent = dbNum($('#profit-platform-percent-'+id).val());
        
        price_platform = profit_value + price_client;
        if (profit_type == 'percent') {
            price_platform = (price_client * profit_percent / 100) + price_client;
        }
        $('#selling-price-vending-machine-'+id).val(appNum(price_platform))
    }
    
    function save(id) {
        var food_name = $('#food-name-'+id).val();
        var price_platform = dbNum($('#selling-price-vending-machine-'+id).val());
        var price_client = dbNum($('#selling-price-client-'+id).val());
        var stock = dbNum($('#stock-'+id).val());
        var hpp = dbNum($('#hpp-'+id).val());

        var profit_type = $('#profit-platform-type-'+id).val();
        var profit_value = dbNum($('#profit-platform-value-'+id).val());
        var profit_percent = dbNum($('#profit-platform-percent-'+id).val());

        price_platform = profit_value + price_client;
        if (profit_type == 'percent') {
            price_platform = (price_client * profit_percent / 100) + price_client;
        }
        $('#selling-price-vending-machine-'+id).val(appNum(price_platform))

        var data = {
            id: id, stock: stock, food_name: food_name, price_client: price_client, hpp: hpp, price_platform: price_platform
        };
        $.ajax({
            url: '{{url("front/vending-machine/product/stock-opname/update")}}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            success: function(res) {
                notification('Berhasil', 'Perubahan berhasil disimpan')
            }
        })
    }
</script>
@endsection