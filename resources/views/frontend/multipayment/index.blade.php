@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => 'Multipayment',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Multipayment'
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
                            {{-- <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('front/multipayment/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a> --}}
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
                                            <th>Customer</th>
                                            <th>Payment Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($multipayments as $row => $multipayment)
                                        <tr id="tr-{{$customer->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$multipayment->customer->name}}</td>
                                            <td>{{$multipayment->payment_type}}</td>
                                            <td>{{format_price($multipayment->amount)}}</td>
                                            <td>{{date_format_view($multipayment->creatd_at)}}</td>
                                            {{-- <td>
                                                <a href="{{url('multipayment/'.$multipayment->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                <a onclick="secureDelete('{{url('multipayment/'.$multipayment->id)}}', '#tr-{{$multipayment->id}}')" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                </a>
                                            </td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $multipayments->links() !!}
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
    // initDatatable('#datatable');
</script>
@stop