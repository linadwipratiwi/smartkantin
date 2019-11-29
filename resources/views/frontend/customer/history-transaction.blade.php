@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Customer Transaction',
        'breadcrumbs' => [
            0 => [
                'link' => url('front'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Customer Transaction'
            ],
        ]
    ])
    
    <!-- /Title -->
    @include('frontend.customer._filter-history-transaction')
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <div class="dt-buttons">
                            {{-- <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('client/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a> --}}
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
                                            <th>Slot</th>
                                            <th>Food Nama</th>
                                            <th>Vending Machine</th>
                                            <th>Client</th>
                                            <th>Customer</th>
                                            <th>Selling Price VM</th>
                                            <th>Profit Client</th>
                                            <th>Date</th>
                                            <th>Status Transaction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_transaction as $row => $transaction)
                                        <tr id="tr-{{$transaction->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$transaction->vendingMachineSlot->convertToAsci()}}</td>
                                            <td>{{$transaction->food_name}}</td>
                                            <td>{{$transaction->vendingMachine->name}}</td>
                                            <td>{{$transaction->client->name}}</td>
                                            <td>{{$transaction->customer->name}}</td>
                                            <td>{{format_price($transaction->selling_price_vending_machine)}}</td>
                                            <td>{{format_price($transaction->profit_client)}}</td>
                                            <td>{{date_format_view($transaction->created_at)}}</td>
                                            <td>{!! $transaction->status() !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{$list_transaction->appends(['type' => \Input::get('type'), 'date' => \Input::get('date'), 'month' => \Input::get('month')])->links()}}
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