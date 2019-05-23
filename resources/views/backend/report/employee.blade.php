@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Laporan Pegawai',
        'breadcrumbs' => [
            0 => [
                'link' => url('dashboard'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('employee'),
                'label' => 'Laporan Pegawai'
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
                        @if(access_is_allowed_to_view('export.report.employee'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('report/employee/download')}}"><i class="fa a-file-excel-o"></i> <span>Export ke Excel</span></a>
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
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Kartu Akses</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Alamat</th>
                                            <th>Telepon</th>
                                            <th>Email</th>
                                            <th>Posisi / Jabatan</th>
                                            <th>Bagian</th>
                                            <th>Bidang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $row => $employee)
                                        <tr>
                                            <td>{{$employee->name}}</td>
                                            <td>{{$employee->nip}}</td>
                                            <td>{!! \App\Helpers\CardHelper::checkStatus($employee) !!}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($employee->date_of_birth)}}</td>
                                            <td>{{$employee->address}}</td>
                                            <td>{{$employee->phone}}</td>
                                            <td>{{$employee->email}}</td>
                                            <td>{{$employee->position_in_company}}</td>
                                            <td>{{$employee->district->name}}</td>
                                            <td>{{$employee->area->name}}</td>
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