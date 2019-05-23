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
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="post" action="{{url('ptpp')}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-file mr-10"></i>Sumber Ketidaksesuaian</h6>
                                <hr class="light-grey-hr" />

                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Untuk Fungsi', 'To Function') !!} </label>
                                    <input type="text" class="form-control" value="{{old('to_function')}}" name="to_function" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Dari', 'From') !!} </label>
                                    <input type="text" class="form-control" value="{{old('from')}}" name="from" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Lokasi', 'Location') !!} </label>
                                    <input type="text" class="form-control" value="{{old('location')}}" name="location" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('kategori', 'category') !!} </label>
                                    <select name="category" class="form-control" id="category">
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}" @if(old('category') == $category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Catatan', 'notes') !!} </label>
                                    <textarea class="form-control" name="notes">{{old('notes')}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">{!! label('Tanggal dibuat', 'created at') !!}</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class='input-group' id='created-at'>
                                                <input type='text' name="created_at" value="{{date('Y-m-d H:i:s')}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-file mr-10"></i>File Pendukung</h6>
                                <hr class="light-grey-hr" />
                                
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <table id="worker" class="table table-responsive table-border" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>File</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
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
                                
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-user mr-10"></i>Approval</h6>
                                <hr class="light-grey-hr" />
                                
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Persetujuan', 'Spv. RSD') !!} </label>
                                    <input type="text" readonly class="form-control" id="" value="{{ user_approval(setting('spv_rsd')) }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Persetujuan', 'OH') !!} </label>
                                    <input type="text" readonly class="form-control" id="" value="{{ user_approval(setting('spv_oh')) }}">
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
    initDatetime('#created-at');
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
    function addRow() {
        t.row.add( [
            '<input type="text" name="file_name[]" required class="form-control" />',
            '<input type="file" name="file[]" class="form-control" />',
            '<a href="javascript:void(0)" class="remove-row"> <button type="button" class="btn btn-info btn-icon-anim btn-square"><i class="icon-trash"></i></button></a>',
        ] ).draw( false );
        counter++;
    }
    
    $('#worker tbody').on('click', '.remove-row', function () {
        t.row($(this).parents('tr')).remove().draw();
    });
    </script>
@endsection