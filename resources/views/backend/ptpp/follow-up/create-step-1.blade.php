@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'PTPP Pengajuan',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'PTPP Pengajuan'
            ],
        ]
    ])
    
    <!-- /Title -->
    @include('backend.ptpp.follow-up._reference')
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="post" action="{{url('ptpp/follow-up')}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input type="hidden" name="ptpp_id" value="{{$ptpp->id}}" readonly>
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-file mr-10"></i><b style="font-weight:bold">Form Tindak Lanjut</b></h6>
                                <hr class="light-grey-hr" />

                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Perbaikan/Tindakan Segera (jika ada)', 'notes') !!} </label>
                                    <textarea class="form-control" name="notes">{{old('notes')}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">{!! label('Tanggal', 'date') !!}</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class='input-group date'>
                                                <input type='text' name="date" value="{{date('Y-m-d H:i:s')}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Penyebab Masalah --}}
                                <br>
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-puzzle-piece mr-10"></i><b style="font-weight:bold">Penyebab Masalah</b></h6>
                                <hr class="light-grey-hr" />
                                
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <table id="worker" class="table table-responsive table-border" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Penyebab Masalah</th>
                                                    <th>Tindakan Perbaikan / Pencegahan</th>
                                                    <th>PIC</th>
                                                    <th>Tanggal Selesai</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right pull-right">
                                                        <div class="btn btn-primary btn-lable-wrap left-label" onclick="addRow()" id="addRow">
                                                            <span class="btn-label"><i class="fa fa-plus"></i> </span>
                                                            <span class="btn-text">Add Row</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                {{-- File yang direvisi --}}
                                <br>
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-pencil mr-10"></i><b style="font-weight:bold">File Yang Direvisi</b></h6>
                                <hr class="light-grey-hr" />
                                
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <table id="file-revision" class="table table-responsive table-border" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td class="text-right pull-right">
                                                        <div class="btn btn-primary btn-lable-wrap left-label" onclick="addRowFileRevision()" id="addRow">
                                                            <span class="btn-label"><i class="fa fa-plus"></i> </span>
                                                            <span class="btn-text">Add Row</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                {{-- Approval --}}
                                
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-user mr-10"></i><b style="font-weight:bold">Approval</b></h6>
                                <hr class="light-grey-hr" />
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Persetujuan', 'Spv. EPM') !!} </label>
                                    <input type="text" readonly class="form-control" id="" value="{{ user_approval(setting('spv_epm')) }}">
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
@stop

@section('scripts')
    <script>
    initDatetime('.date');
    $('#category').select2();

    /* Add worker */
    var t = $('#worker').DataTable({
        bSort: false,
        bPaginate: false,
        bInfo: false,
        bFilter: false,
        bScrollCollapse: false
    });
    counter = 0;
    var date = `
    <div class="col-md-12">
        <div class='input-group date'>
            <input type='text' name="date_finish[]" class="form-control" />
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>`;

    function addRow() {
        t.row.add( [
            '<input type="text" name="problem[]" required class="form-control" />',
            '<input type="text" name="prevention[]" required class="form-control" />',
            '<input type="text" name="pic[]" required class="form-control" />',
            date,
            '<a href="javascript:void(0)" class="remove-row"> <button type="button" class="btn btn-info btn-icon-anim btn-square"><i class="icon-trash"></i></button></a>',
        ] ).draw( false );

        initDatetime('.date');
        counter++;
    }
    
    $('#worker tbody').on('click', '.remove-row', function () {
        t.row($(this).parents('tr')).remove().draw();
    });

    // File revision
    var x = $('#file-revision').DataTable({
        bSort: false,
        bPaginate: false,
        bInfo: false,
        bFilter: false,
        bScrollCollapse: false
    });
    counter_x = 0;

    function addRowFileRevision() {
        x.row.add( [
            '<input type="text" name="file_name[]" required class="form-control" />',
            '<a href="javascript:void(0)" class="remove-row"> <button type="button" class="btn btn-info btn-icon-anim btn-square"><i class="icon-trash"></i></button></a>',
        ] ).draw( false );
        counter_x++;
    }
    
    $('#file-revision tbody').on('click', '.remove-row', function () {
        x.row($(this).parents('tr')).remove().draw();
    });
    </script>
@endsection