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
                                        </tr>
                                    </thead>
                                    @foreach($vending_machine->slots as $i => $slot)
                                    <tr id="tr-slot-{{$slot->id}}">
                                        <?php $client = $slot->vendingMachine->client;?>
                                        
                                        {{-- <td>{!!$slot->photo ? '<img width="50px" height="50px" src="'.asset($slot->photo).'">' : '-'!!}</td> --}}
                                        <td>{{$slot->convertToAsci()}}</td>
                                        <td>
                                            <select name="food_id" id="food-id-{{$slot->id}}" onchange="save({{$slot->id}})" class="form-control">
                                                <option value="">Pilih Makanan</option>
                                                @foreach ($list_food as $item)
                                                    <option value="{{$item->id}}" @if($item->id == $slot->food_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" onchange="save({{$slot->id}})" id="stock-{{$slot->id}}" class="form-control format-price" value="{{$slot->stock}}" name="stock[{{$slot->id}}]"></td>
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
        var food_id = $('#food-id-'+id+ ' :selected').val();
        var stock = dbNum($('#stock-'+id).val());
    }
    
    function save(id) {
        var food_id = $('#food-id-'+id+ ' :selected').val();
        var stock = dbNum($('#stock-'+id).val());

        var data = {
            id: id, stock: stock, food_id: food_id
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