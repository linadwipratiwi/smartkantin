@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Gopay',
        'breadcrumbs' => [
            0 => [
                'link' => url('dashboard'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('guest'),
                'label' => 'Gopay'
            ]
        ]
    ])
    
    <!-- /Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Sesuaikan kebutuhan Anda</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form class="form-inline" method="get" action="{{url('gopay-transaction')}}">
                                <div class="form-group mr-15">
                                    <label class="control-label mr-10" for="email_inline">Range tanggal</label>
                                    <input class="form-control input-daterange-datepicker" type="text" name="date"  value="{{\Input::get('date') ? : ''}}"/>
                                </div>
                                <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">filter</span></button>
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
                    <div class="pull-left">
                        <div class="dt-buttons">
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
                                            <th>Jenis Pembayaran Gopay</th>
                                            <th>Total Pembayaran</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Status Gopay</th>
                                            <th>Tanggal Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_transaction as $row => $transaction)
                                        <tr>
                                            <td>{{$row + 1}}</td>
                                            <td>{{$transaction->transactionType()}}</td>
                                            <td>{{format_price($transaction->gopay_gross_amount)}}</td>
                                            <td>{{$transaction->getCutomer()}}</td>
                                            <td>{{$transaction->getStatus()}}</td>
                                            <td>{{$transaction->gopay_transaction_status}}</td>
                                            <td>
                                                {{ $transaction->gopay_transaction_status ? \App\Helpers\DateHelper::formatView($transaction->gopay_transaction_status, true) : '-'}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $list_transaction->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>

    <div class="row">
        <div class="modal fade detail-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" id="modal-detail">
    
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
    initDateRangePicker('.input-daterange-datepicker');
    
    function showDetail(url) {
        $.ajax({
            url: url,
            success: function(result){
                $("#modal-detail").html(result);
            }, error: function(result){
                alert("Failed something went wrong");
            }
        });
    }
    
</script>
@stop