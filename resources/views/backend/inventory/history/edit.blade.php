@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Inventory History',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('inventory'),
                'label' => 'Inventory History'
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
                            <form method="post" action="{{url('inventory/history/'.$history->id)}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Inventory</label>
                                    <select name="inventory_id" placeholder="" class="form-control" id="inventory-id">
                                        <option value="{{$history->inventory_id}}">{{$history->inventory->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Stock', 'Masukan stok yang diterima / stok yang berkurang')
                                        !!}</label>
                                    <input type="number" class="form-control" value="{{$history->stock}}" name="stock">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label mb-10">{!! label('tanggal', 'Date') !!}</label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' name="date" value="{{date('m-d-Y a', strtotime($history->date))}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">submit</span></button>
                                </div>
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
    initItemSelect2('#inventory-id', '{{url("api/inventories")}}');
</script>
@endsection