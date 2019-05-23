@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Laporan Gerbang',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('report/gate'),
                'label' => 'Laporan Gerbang'
            ]
        ]
    ])
    
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
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
                                            <th>Gate</th>
                                            <th>Kartu Akses</th>
                                            <th>Jenis Kartu</th>
                                            <th>Jeni Kepemilikan Kartu</th>
                                            <th>Nama Pemilik</th>
                                            <th>Tanggal</th>
                                            <th>Foto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $row => $report)
                                        <tr>
                                            <td>{{$report->gate->name}}</td>
                                            <td>{{$report->card->card_number}}</td>
                                            <td>{{$report->card->type ? $report->card->type->name : '-'}}</td>
                                            <td>{!!$report->card->personType()!!}</td>
                                            <td>{!!$report->card->personName()!!}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($report->created_at, true)}}</td>
                                            <td>{{$report->photo}}</td>
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

    <div class="row">
        <div class="modal fade detail-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" id="modal-detail">

            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
    initDatatable('#datatable');

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