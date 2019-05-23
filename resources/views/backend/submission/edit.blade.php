@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Pengajuan Barang',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Pengajuan Barang'
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
                            <form method="post" action="{{url('submission/'.$submission->id)}}" enctype="multipart/form-data">
                                <input name="_method" type="hidden" value="PUT">
                                {!! csrf_field() !!}
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-list mr-10"></i>Pengajuan</h6>
                                <hr class="light-grey-hr" />
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Pemohon', 'applicant') !!} </label>
                                    <input type="text" class="form-control" readonly value="{{auth()->user()->name}}" name="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('kategori', 'category') !!} </label>
                                    <select name="category_id" class="form-control" id="category">
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}" @if($submission->category_id == $category->id) selected @endif >{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">{!! label('nama barang', 'item name') !!}</label>
                                    <input type="text" class="form-control" value="{{$submission->name}}" name="item_name">                                    
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('alasan pengajuan', 'notes') !!} </label>
                                    <textarea name="notes" class="form-control" cols="30" rows="5">{{$submission->notes}}</textarea>
                                </div>
                                <div class="seprator-block"></div>
                                {{-- Visit Transaction --}}
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-file mr-10"></i>File Pendukung</h6>
                                <hr class="light-grey-hr" />
                                <div class="alert alert-info alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="zmdi zmdi-notifications pr-15 pull-left"></i>
                                    <p class="pull-left">File pendukung hanya bisa diedit dimenu File.</p>
                                    <div class="clearfix"></div>
                                </div>

                                <h6 class="txt-dark capitalize-font"><i class="fa fa-user mr-10"></i>Approval</h6>
                                <hr class="light-grey-hr" />

                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Approval to Operation Head', 'OH') !!} </label>
                                    <input type="text" readonly class="form-control" id="" value="{{ user_approval(setting('spv_oh')) }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Approval to Spv. EPM', 'EPM') !!} </label>
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
    initDatetime('#date-start');
    initDatetime('#date-end');
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