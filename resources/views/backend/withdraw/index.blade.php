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
@include('backend.withdraw._filter')

@if($list_topup)
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
                    <form action="{{url('withdraw')}}" method="POST" id="form-wd">
                        {{ csrf_field() }}
                        <button type="button" onclick="confirmation()" class="btn btn-sm btn-primary">Withdraw</button>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30">
                                    <thead>
                                        <tr>
                                            <th style="width:20px">
                                                <div class="checkbox checkbox-success">
                                                    <input type="checkbox" id="selectAll">
                                                    <label for="selectAll">
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Topup by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_topup as $row => $topup)
                                        <tr id="tr-{{$topup->id}}">
                                            <td>
                                                <div class="checkbox checkbox-success">
                                                    <input id="checkbox-{{$topup->id}}" value="{{$topup->id}}" name="id[]" type="checkbox">
                                                    <label for="checkbox-{{$topup->id}}">
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{{date_format_view($topup->created_at)}}</td>
                                            <td>{{$topup->toType() ? $topup->toType()->name : '-'}}</td>
                                            <td>{{format_price($topup->saldo)}}</td>
                                            <td>{{$topup->createdBy ? $topup->createdBy->name : '-'}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('scripts')
<script>
    $("#selectAll").click(function(){
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
    });

    function confirmation() {
        swal({
            title: 'Anda yakin ingin melakukan WD?',
            text: '',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f2b701",
            confirmButtonText: "Ya, saya setuju!",
            closeOnConfirm: false
        }, function () {
            $('#form-wd').submit();
        });

        return false;
    }
</script>
@stop