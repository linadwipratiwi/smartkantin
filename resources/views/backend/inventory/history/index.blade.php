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
                <div class="panel-heading">
                    <div class="pull-left">
                        @if(access_is_allowed_to_view('create.inventory.history'))
                        <div class="dt-buttons">
                            <a class="btn btn-info btn-sm btn-icon left-icon" tabindex="0" aria-controls="example" href="{{url('inventory/history/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
                        </div>
                        @endif
                        &nbsp;
                        <a href="{{url('inventory/history/download?id='.\Input::get("id"))}}" data-toggle="tooltip" data-original-title="Download">
                            <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-download"></i> Download Excel</button>
                        </a>
                        <h6 class="panel-title txt-dark"></h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <form method="get" action="{{url('history')}}">
                            {!! csrf_field() !!}
                            <div class="row">   
                                <div class="col-md-3">
                                    <label class="control-label mb-10 text-left">Inventory</label>
                                    <select name="inventory_id" placeholder="" class="form-control" onchange="getInventory(this.value)" id="inventory-id">
                                        @if(\Input::get('inventory_id'))
                                            <?php $item = \App\Models\Inventory::find(\Input::get('inventory_id'));?>
                                            @if ($item)
                                                <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Item</th>
                                            <th>Stock</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_inventory_stock_opname as $row => $item)
                                        <tr id="tr-{{$item->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$item->inventory->name}}</td>
                                            <td>{{format_quantity($item->stock)}}</td>
                                            <td>{{date_format_view($item->date)}}</td>
                                            <td>
                                                @if(access_is_allowed_to_view('update.inventory.history'))
                                                <a href="{{url('inventory/history/'.$item->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                @endif
                                                @if(access_is_allowed_to_view('delete.inventory.history'))
                                                <a onclick="secureDelete('{{url('inventory/history/'.$item->id)}}', '#tr-{{$item->id}}')" onclick="document.getElementById('form-delete-{{$item->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if(!\Input::get('id'))
                        {{ $list_inventory_stock_opname->links() }}
                        @endif
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="panel-title txt-dark"><b style="font-weight:600">Keterangan Tombol</b></h6>
                            <table style="border-collapse:separate; border-spacing:0 5px;" style="width:50%">
                                <tr>
                                    <td><button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button></td>
                                    <td style="padding-left:20px">Digunakan untuk mengedit data</td>
                                </tr>
                                <tr>
                                    <td><button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button></td>
                                    <td style="padding-left:20px">Digunakan untuk menghapus data</td>
                                </tr>
                            </table>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
    initItemSelect2('#inventory-id', '{{url("api/inventories")}}');

    function getInventory(item) {
        location.href='{{url("/")}}/inventory/history?id='+item;
    }

    function copy(id) {
        swal({
            title: "Anda yakin ingin menggadakan item ini?",
            text: "Jangan kuatir, anda masih bisa mengubah item yang sudah digandakan",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#f2b701",
            confirmButtonText: "Copy",
            closeOnConfirm: false
        }, function () {
            location.href='{{url("/")}}/inventory/'+id+'/copy';
        });
    }
    </script>
@endsection