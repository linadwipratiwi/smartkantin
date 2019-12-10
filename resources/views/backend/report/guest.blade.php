@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Tamu',
        'breadcrumbs' => [
            0 => [
                'link' => url('dashboard'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('guest'),
                'label' => 'Tamu'
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
                            <form class="form-inline" method="get" action="{{url('report/guest')}}">
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
                        @if(access_is_allowed_to_view('export.report.guest'))
                        <div class="dt-buttons">
                            <?php $url = \Input::get('date') ? url('report/guest/download').'?date='.\Input::get('date'): url('report/guest/download'); ?>
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{$url}}"><i class="fa a-file-excel-o"></i> <span>Export ke Excel</span></a>
                        </div>
                        @endif
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
                                            <th>Tgl. Kunjungan</th>
                                            <th>Perusahaan</th>
                                            <th>Keperluan</th>
                                            <th>Karyawan Tujuan</th>
                                            <th>Jumlah Tamu</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guest_visit_transactions as $row => $visit)
                                        <tr>
                                            <td>{{$row + 1}}</td>
                                            <td>
                                                {{\App\Helpers\DateHelper::formatView($visit->date_entry, true)}}
                                            </td>
                                            <td style="max-width:100px">{{$visit->company->name}}</td>
                                            <td>{{$visit->purpose}}</td>
                                            <td>{{$visit->employee->name}}</td>
                                            <td>{{$visit->guests->count()}}</td>
                                            <td>
                                                <a onclick="showDetail('{{url("guest/transaction/".$visit->id)}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-list"></i></button>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $guest_visit_transactions->links() }}
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