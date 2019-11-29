@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Topup',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Topup'
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
    <!-- /Row -->

    @if ($customers)

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                
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
                                            <th>Identity Type</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Saldo</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customers as $row => $customer)
                                        <tr id="tr-{{$customer->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$customer->name}}</td>
                                            <td>{{$customer->card_number}}</td>
                                            <td>{{$customer->identity_type}}</td>
                                            <td>{{$customer->email}}</td>
                                            <td>{{$customer->phone}}</td>
                                            <td class="td-saldo-customer-{{$customer->id}}">{{format_price($customer->saldo + $customer->saldo_pens)}}</td>
                                            <td>
                                                <a onclick="showDetail('{{url('front/customer/'.$customer->id.'/topup')}}')" data-toggle="modal" data-target=".detail-modal"  data-original-title="Topup">
                                                    <button class="btn btn-success btn-icon-anim btn-sm"><i class="fa fa-dollar"></i> Topup</button>
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
    @endif
@stop