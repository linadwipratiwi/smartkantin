@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
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
                <div class="panel-heading">
                    <div class="pull-left">
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('vending-machine/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
                        </div>
                        <h6 class="panel-title txt-dark"></h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Vending Machine</th>
                                            <th>Client</th>
                                            <th>Tahun Produksi</th>
                                            <th>Lokasi</th>
                                            <th>IP</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vending_machines as $row => $vending_machine)
                                        <tr id="tr-{{$vending_machine->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$vending_machine->name}}</td>
                                            <td>{{$vending_machine->client->name}}</td>
                                            <td>{{$vending_machine->production_year}}</td>
                                            <td>{{$vending_machine->location}}</td>
                                            <td>{{$vending_machine->ip}}</td>
                                            <td>
                                                <a href="{{url('vending-machine/'.$vending_machine->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                <a onclick="secureDelete('{{url('vending-machine/'.$vending_machine->id)}}', '#tr-{{$vending_machine->id}}')" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                </a>
                                                <a onclick="showDetail('{{url("vending-machine/".$vending_machine->id."/slot")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                                </a>
                                                <a onclick="showDetail('{{url("vending-machine/".$vending_machine->id."/stock")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-success btn-icon-anim btn-square btn-sm"><i class="fa fa-cubes"></i></button>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    initDatatable('#datatable');
</script>
@stop