@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Inventory',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('inventory'),
                'label' => 'Inventory'
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
                        @if(access_is_allowed_to_view('create.inventory'))
                        <div class="dt-buttons">
                            <a class="btn btn-info btn-sm btn-icon left-icon" tabindex="0" aria-controls="example" href="{{url('inventory/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
                        </div>
                        @endif
                        &nbsp;
                        <a href="{{url('inventory/download?id='.\Input::get("id"))}}" data-toggle="tooltip" data-original-title="Download">
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
                                    <select name="item_id" placeholder="" class="form-control" onchange="getInventory(this.value)" id="item-id">
                                        @if(\Input::get('item_id'))
                                            <?php $item = \App\Models\Inventory::find(\Input::get('item_id'));?>
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
                                            <th>Nama</th>
                                            <th>Stok</th>
                                            <th>Brand</th>
                                            <th>Tipe</th>
                                            <th>Tahun Produksi</th>
                                            <th>Lokasi</th>
                                            <th>Catatan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventories as $row => $item)
                                        <tr id="tr-{{$item->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{format_quantity($item->stock)}}</td>
                                            <td>{{$item->brand}}</td>
                                            <td>{{$item->type}}</td>
                                            <td>{{$item->production_year}}</td>
                                            <td>{{$item->location_of_use}}</td>
                                            <td>{{$item->notes}}</td>
                                            <td>
                                                @if(access_is_allowed_to_view('read.inventory.history'))
                                                <a href="{{url('inventory/history?id='.$item->id)}}" data-toggle="tooltip" data-original-title="Checklist">
                                                    <button class="btn btn-success btn-icon-anim btn-square btn-sm"><i class="zmdi zmdi-settings"></i></button>
                                                </a>
                                                @endif
                                                @if(access_is_allowed_to_view('update.inventory'))
                                                <a href="{{url('inventory/'.$item->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                @endif
                                                @if(access_is_allowed_to_view('delete.inventory'))
                                                <a onclick="secureDelete('{{url('inventory/'.$item->id)}}', '#tr-{{$item->id}}')" onclick="document.getElementById('form-delete-{{$item->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
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
                        {{ $inventories->links() }}
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
                                <tr>
                                    <td><button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="zmdi zmdi-settings"></i></button></td>
                                    <td style="padding-left:20px">Digunakan untuk melihat Stockopname dari item</td>
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
    initItemSelect2('#item-id', '{{url("api/inventories")}}');

    function getInventory(item) {
        location.href='{{url("/")}}/inventory?id='+item;
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