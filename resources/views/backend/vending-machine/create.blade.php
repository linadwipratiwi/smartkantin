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
                            <form method="post" action="{{url('vending-machine')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" class="form-control" value="1" name="type" required>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('alias / prefix', 'digunakan untuk request API dan sebagai prefix') !!}</label>
                                    <input type="text" class="form-control" value="" name="alias" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Client', 'Client') !!} </label>
                                    <select name="client_id" class="form-control" id="client-id">
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('baris', 'digunakan untuk generate baris slot') !!} </label>
                                    <input type="number" min="1" name="slot_rows" placeholder="misl. 9" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('kolom', 'digunakan untuk generate kolom slot') !!} </label>
                                    <input type="number" min="1" name="slot_column" placeholder="misl. 4" class="form-control" id="" value="">
                                </div>
                                <div class="form-group ">
                                    <label class="control-label mb-10">{!! label('keuntungan platform', 'profit platform') !!}</label>
                                    <select name="profit_platform_type" id="" onchange="setType(this.value)" class="form-control">
                                        <option value="value" selected>Value</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                                <div class="form-group" id="">
                                    <label class="control-label mb-10">{!! label('Presentase profit / dengan set value', 'Jika Anda memilih type percent, maka isi dengan percent (max: 100). Jika dengan value, maka isi dengan harga (3000)') !!}</label>
                                    <div class="input-group"> 
                                        <span class="input-group-addon" id="lb-type">Rp.</span>
                                        <input type="text" id="profit_platform_value" name="profit_platform_value" class="form-control format-price" placeholder="">
                                    </div>
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
    initFormatNumber();
    function setType(value) {
        type = 'Rp.'
        if (value == 'percent') {
            type = '%'    
        }

        $('#lb-type').html(type);
    }    
</script>
@endsection