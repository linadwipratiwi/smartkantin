@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Stand',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Stand'
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
                            <form method="post" action="{{url('stand')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" class="form-control" value="2" name="type" required>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('alias', 'digunakan untuk request API') !!}</label>
                                    <input type="text" class="form-control" value="" name="alias" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Client', 'Client') !!} </label>
                                    <select name="client_id" class="form-control" id="client-id">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Tahun Produksi', 'Production Year') !!} </label>
                                    <input type="text" name="production_year" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Lokasi', 'Location') !!} </label>
                                    <input type="text" name="location" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Alamat IP', 'IP Address') !!} </label>
                                    <input type="text" name="ip" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Versi Firmware', 'Firmware') !!} </label>
                                    <select name="version_firmware_id" id="" class="form-control">
                                        @foreach ($list_firmware as $firmware)
                                            <option value="{{$firmware->id}}">{{$firmware->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Versi UI', 'UI') !!} </label>
                                    <select name="version_ui_id" id="" class="form-control">
                                        @foreach ($list_ui as $ui)
                                            <option value="{{$ui->id}}">{{$ui->name}}</option>
                                        @endforeach
                                    </select>
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
    initItemSelect2('#client-id', '{{url("api/clients")}}')
</script>
@endsection