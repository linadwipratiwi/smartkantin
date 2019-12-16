@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => 'Tambah Produk '. $vending_machine->name,
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Tambah Produk '. $vending_machine->name
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
                            <form method="post" action="{{url("front/stand/".$vending_machine->id."/product")}}">
                                {!! csrf_field() !!}
                                <input type="hidden" value="{{$vending_machine->id}}" name="vending_machine_id">
                                <table class="table color-table info-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 20px">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="check-all" type="checkbox">
                                                    <label for="check-all"></label>
                                                </div>
                                            </th>
                                            <th>Makanan</th>
                                            <th style="text-align:right">
                                                <button type="submit" class="btn btn-info btn-sm">Simpan</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    @foreach($list_food as $i => $food)
                                    <?php
                                    $cek = \App\Models\VendingMachineSlot::where('vending_machine_id', $vending_machine->id)
                                        ->where('food_id', $food->id)
                                        ->first();
                                    $status = '';
                                    if ($cek) {
                                        $status = '<span class="label label-primary">sudah ditambahkan</span>';
                                    }
                                    
                                    ?>
                                    <tr id="tr-slot-{{$food->id}}">
                                        <td>
                                            <div class="checkbox checkbox-primary">
                                                <input id="check-{{$food->id}}" value="{{$food->id}}" name="food_id[]" type="checkbox">
                                                <label for="check-{{$food->id}}"> </label>
                                            </div>    
                                        </td>
                                        <td colspan="2">{{$food->name}} {!! $status !!}</td>
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
    $("#check-all").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
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
                notification('Berhasil', 'Perubahan berhasil disimpan')
            }
        })
    }
</script>
@endsection