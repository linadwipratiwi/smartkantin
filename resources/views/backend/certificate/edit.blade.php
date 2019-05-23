@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Sertifikat',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Sertifikat'
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
                            <form method="post" action="{{url('certificate/'.$certificate->id)}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('kategori', 'category') !!} </label>
                                    <select name="category" class="form-control" id="category">
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}" @if($category->id == $certificate->category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('item', 'item') !!}</label>
                                    <input type="text" class="form-control" value="{{$certificate->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('tahun', 'year') !!} </label>
                                    <select name="year" class="form-control" id="year">
                                        @for ($i = date('Y'); $i >= 2009; $i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">{!! label('Tanggal mulai berlaku', 'Date start') !!}</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class='input-group' id='date-start'>
                                                <input type='text' name="date_start" value="{{date('m-d-Y a', strtotime($certificate->date_start))}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class='input-group' id='date-end'>
                                                <input type='text' name="date_end" value="{{date('m-d-Y a', strtotime($certificate->date_end))}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Penerbit', 'Publisher') !!} </label>
                                    <input type="text" name="publisher" class="form-control" id="" value="{{$certificate->publisher}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('File', 'file') !!} </label>
                                    <input type="file" name="file" class="form-control" id="" value="">
                                    {!! $certificate->file() !!}
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
    $('#year').select2();
</script>
@endsection