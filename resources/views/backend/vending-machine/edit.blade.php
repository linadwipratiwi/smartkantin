@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Vending Machine',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Vending Machine'
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
                            <form method="post" action="{{url('vending-machine/'.$vending_machine->id)}}">
                                {!! csrf_field() !!}
                                <input type="hidden" class="form-control" value="1" name="type" required>
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="{{$vending_machine->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('alias', 'digunakan untuk request API') !!}</label>
                                    <input type="text" class="form-control" value="{{$vending_machine->alias}}" name="alias" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Client', 'Client') !!} </label>
                                    <select name="client_id" class="form-control" id="client-id">
                                        <option value="{{$vending_machine->client_id}}" selected>{{$vending_machine->client->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Tahun Produksi', 'Production Year') !!} </label>
                                    <input type="text" name="production_year" class="form-control" id="" value="{{$vending_machine->production_year}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Lokasi', 'Location') !!} </label>
                                    <input type="text" name="location" class="form-control" id="" value="{{$vending_machine->location}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Alamat IP', 'IP Address') !!} </label>
                                    <input type="text" name="ip" class="form-control" id="" value="{{$vending_machine->ip}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Versi Firmware', 'Firmware') !!} </label>
                                    <select name="version_firmware_id" id="" class="form-control">
                                        @foreach ($list_firmware as $firmware)
                                            <option value="{{$firmware->id}}" @if($firmware->id == $vending_machine->version_firmware_id) selected @endif>{{$firmware->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Versi UI', 'UI') !!} </label>
                                    <select name="version_ui_id" id="" class="form-control">
                                        @foreach ($list_ui as $ui)
                                            <option value="{{$ui->id}}" @if($ui->id == $vending_machine->version_ui_id) selected @endif>{{$ui->name}}</option>
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