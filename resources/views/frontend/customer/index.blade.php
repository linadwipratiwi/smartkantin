@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Customer',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Customer'
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
                            <a class="dt-button buttons-copy buttons-html5 btn btn-sm" tabindex="0" aria-controls="example" href="{{url('front/customer/download')}}"><i class="fa fa-download"></i> <span>Download Excel</span></a>
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
                                            <th>Identity Type</th>
                                            <th>Identity Number</th>
                                            <th>Register di Client</th>
                                            <th>Register di Vending Machine</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customers as $row => $customer)
                                        <tr id="tr-{{$customer->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$customer->name}}</td>
                                            <td>{{$customer->identity_type}}</td>
                                            <td>{{$customer->identity_number}}</td>
                                            <td>{{$customer->client ? $customer->client->name : 'SYSTEM'}}</td>
                                            <td>{{$customer->vendingMachine ? $customer->vendingMachine->name : 'SYSTEM'}}</td>
                                            <td>{{$customer->created_at ? date_format_view($customer->created_at) : '-'}}</td>
                                            <td>
                                                <a href="{{url('customer/'.$customer->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                <a onclick="secureDelete('{{url('customer/'.$customer->id)}}', '#tr-{{$customer->id}}')" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop

@section('scripts')
<script>
</script>
@stop