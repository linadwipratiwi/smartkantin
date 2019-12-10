@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Client',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Client'
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
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('client/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
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
                                            <th>Nama</th>
                                            <th>Perusahaan</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Tipe Share</th>
                                            <th>Value</th>
                                            <th>Jumlah Vending Machine</th>
                                            <th>Jumlah Stan</th>
                                            <th>Jumlah Customer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clients as $row => $client)
                                        <tr id="tr-{{$client->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$client->name}}</td>
                                            <td>{{$client->company}}</td>
                                            <td>{{$client->phone}}</td>
                                            <td>{{$client->address}}</td>
                                            <td>{{$client->profit_platform_type}}</td>
                                            <td>{{$client->profit_platform_type == 'value' ? format_quantity($client->profit_platform_value): $client->profit_platform_percent}}</td>
                                            <td>{{$client->vendingMachines->count()}} unit</td>
                                            <td>{{$client->stands->count()}} stand</td>
                                            <td>{{$client->customers->count()}} orang</td>
                                            <td>
                                                <a href="{{url('client/'.$client->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                <a onclick="secureDelete('{{url('client/'.$client->id)}}', '#tr-{{$client->id}}')" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
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