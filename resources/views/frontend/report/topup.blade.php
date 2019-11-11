@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'History Topup',
        'breadcrumbs' => [
            0 => [
                'link' => url('front'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'History Topup'
            ],
        ]
    ])
    
    <!-- /Title -->
    @include('frontend.report._filter-topup')
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
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Topup by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_topup as $row => $topup)
                                        <tr id="tr-{{$topup->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{date_format_view($topup->created_at)}}</td>
                                            <td>{{$topup->toType()->name}}</td>
                                            <td>{{format_price($topup->saldo)}}</td>
                                            <td>{{$topup->createdBy->name}}</td>
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