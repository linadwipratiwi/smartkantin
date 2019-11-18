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
<div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="get" action="{{url('front/topup')}}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Cari </label>
                                    <input type="text" class="form-control"  value="{{\Input::get('search')}}" name="search" placeholder="Nomer kartu / nama" required>
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

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="get" action="{{url('front/customer')}}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Cari </label>
                                    <input type="text" class="form-control" value="{{\Input::get('search')}}" name="search"
                                        placeholder="Nomer kartu / nama" required>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span
                                            class="btn-text">submit</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-right">
                        <a class="btn btn-sm btn-primary btn-icon" href="{{url('front/customer/download')}}"><span>Download Excel</span></a>
                        <a class="btn btn-sm btn-default " href="{{url('front/customer/import')}}">Import Data</a>
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
                                            <th>Nomer Kartu</th>
                                            <th>Default Account</th>
                                            <th>Identity Type</th>
                                            <th>Identity Number</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Saldo</th>
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
                                            <td>{{$customer->card_number}}</td>
                                            <td>{!! $customer->user ? $customer->showDefaultAccount() : 'belum diset ' . $customer->createRandomUser() !!}</td>
                                            <td>{{$customer->identity_type}}</td>
                                            <td>{{$customer->identity_number}}</td>
                                            <td>{{$customer->email}}</td>
                                            <td>{{$customer->phone}}</td>
                                            <td>{{$customer->address}}</td>
                                            <td class="td-saldo-customer-{{$customer->id}}">{{format_price($customer->saldo + $customer->saldo_pens)}}</td>
                                            <td>{{$customer->client ? $customer->client->name : 'SYSTEM'}}</td>
                                            <td>{{$customer->vendingMachine ? $customer->vendingMachine->name : 'SYSTEM'}}</td>
                                            <td>{{$customer->created_at ? date_format_view($customer->created_at) : '-'}}</td>
                                            <td>
                                                <a onclick="showDetail('{{url('front/customer/'.$customer->id.'/topup')}}')" data-toggle="modal" data-target=".detail-modal"  data-original-title="Topup">
                                                    <button class="btn btn-success btn-icon-anim btn-square btn-sm"><i class="fa fa-dollar"></i></button>
                                                </a>
                                                
                                                <a href="{{url('front/customer/'.$customer->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                <a onclick="secureDelete('{{url('front/customer/'.$customer->id)}}', '#tr-{{$customer->id}}')" data-toggle="tooltip" data-original-title="Close">
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