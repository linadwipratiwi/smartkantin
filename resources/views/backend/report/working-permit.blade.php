@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Working Permits',
        'breadcrumbs' => [
            0 => [
                'link' => url('dashboard'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('working-permit'),
                'label' => 'Working Permits'
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
                            <form class="form-inline" method="get" action="{{url('report/working-permit')}}">
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
                        @if(access_is_allowed_to_view('export.report.working.permit'))
                        <div class="dt-buttons">
                            <?php $url = \Input::get('date') ? url('report/working-permit/download').'?date='.\Input::get('date'): url('report/working-permit/download'); ?>
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
                                            <th>No. Safety Permit</th>
                                            <th>Perusahaan</th>
                                            <th>No. Memo/PO/SPK</th>
                                            <th>Durasi</th>
                                            <th>Safety Man</th>
                                            <th>CP Safety Man</th>
                                            <th>Lokasi</th>
                                            <th>Pekerjaan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($working_permits as $row => $working_permit)
                                        <tr>
                                            <td>{{$working_permit->no_safety_permit}}</td>
                                            <td>{{$working_permit->company->name}}</td>
                                            <td>{{$working_permit->no_memo_spk}}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($working_permit->date_start_working)}} - {{\App\Helpers\DateHelper::formatView($working_permit->date_finish_working)}}</td>
                                            <td>{{$working_permit->safety_man}}</td>
                                            <td>{{$working_permit->contact_person}}</td>
                                            <td>{{$working_permit->location}}</td>
                                            <td>{{$working_permit->job_desc}}</td>
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
@stop

@section('scripts')
<script>
    initDatatable('#datatable');
    initDateRangePicker('.input-daterange-datepicker');
</script>
@stop