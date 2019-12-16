@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Client',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Client'
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
                            <form method="post" action="{{url('client/'.$client->id)}}">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="{{$client->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Telepon', 'Phone') !!} </label>
                                    <input type="text" name="phone" class="form-control" id="" value="{{$client->phone}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Perusahaan', 'Company') !!} </label>
                                    <input type="text" name="company" class="form-control" id="" value="{{$client->company}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Alamat', 'Address') !!} </label>
                                    <textarea name="address" class="form-control" id="" cols="30" rows="10">{{$client->address}}</textarea>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label mb-10">{!! label('keuntungan platform', 'profit platform') !!}</label>
                                    <select name="profit_platform_type" id="profit_platform_type" onchange="setType(this.value)" class="form-control">
                                        <option value="value" @if($client->profit_platform_type == 'value') selected @endif>Value</option>
                                        <option value="percent" @if($client->profit_platform_type == 'percent') selected @endif>Percent</option>
                                    </select>
                                </div>
                                <div class="form-group" id="">
                                    <label class="control-label mb-10">{!! label('Presentase profit / dengan set value', 'Jika Anda memilih type percent, maka isi dengan percent (max: 100). Jika dengan value, maka isi dengan harga (3000)') !!}</label>
                                    <div class="input-group"> 
                                        <span class="input-group-addon" id="lb-type">@if($client->profit_platform_type == 'value') Rp. @else % @endif</span>
                                        <?php
                                            $value_profit = $client->profit_platform_value;
                                            if ($client->profit_platform_type == 'percent') {
                                                $value_profit = $client->profit_platform_percent;
                                            }
                                        ?>
                                        <input type="text" id="profit_platform_value" name="profit_platform_value" class="form-control format-price" value="{{$value_profit}}" placeholder="">
                                    </div>
                                </div>

                                {{-- Fee Topup ManuaL --}}
                                <div class="form-group ">
                                    <label class="control-label mb-10">Biaya Topup Tambahan Manual</label>
                                    <select name="fee_topup_manual_type" id="fee_topup_manual_type" onchange="setTypeTopupManual(this.value)" class="form-control">
                                        <option value="value" @if($client->fee_topup_manual_type == 'value') selected @endif>Value</option>
                                        <option value="percent" @if($client->fee_topup_manual_type == 'percent') selected @endif>Percent</option>
                                    </select>
                                </div>
                                <div class="form-group" id="">
                                    <label class="control-label mb-10">Presentase profit / dengan set value</label>
                                    <div class="input-group"> 
                                        <span class="input-group-addon" id="lb-type-topup-manual">@if($client->fee_topup_manual_type == 'value') Rp. @else % @endif</span>
                                        <?php
                                            $topup_manual = $client->fee_topup_manual_value;
                                            if ($client->fee_topup_manual_type == 'percent') {
                                                $topup_manual = $client->fee_topup_manual_percent;
                                            }
                                        ?>
                                        <input type="text" id="fee_topup_manual_value" name="fee_topup_manual_value" class="form-control format-price" value="{{$topup_manual}}" placeholder="">
                                    </div>
                                </div>

                                {{-- Fee Topup Gopay --}}
                                <div class="form-group ">
                                    <label class="control-label mb-10">Biaya Topup Tambahan Gopay</label>
                                    <select name="fee_topup_gopay_type" id="fee_topup_gopay_type" onchange="setTypeTopupGopay(this.value)" class="form-control">
                                        <option value="value" @if($client->fee_topup_gopay_type == 'value') selected @endif>Value</option>
                                        <option value="percent" @if($client->fee_topup_gopay_type == 'percent') selected @endif>Percent</option>
                                    </select>
                                </div>
                                <div class="form-group" id="">
                                    <label class="control-label mb-10">Presentase profit / dengan set value</label>
                                    <div class="input-group"> 
                                        <span class="input-group-addon" id="lb-type-topup-gopay">@if($client->fee_topup_gopay_type == 'value') Rp. @else % @endif</span>
                                        <?php
                                            $topup_gopay = $client->fee_topup_gopay_value;
                                            if ($client->fee_topup_gopay_type == 'percent') {
                                                $topup_gopay = $client->fee_topup_gopay_percent;
                                            }
                                        ?>
                                        <input type="text" id="fee_topup_gopay_value" name="fee_topup_gopay_value" class="form-control format-price" value="{{$topup_gopay}}" placeholder="">
                                    </div>
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
    initFormatNumber();

    function setType(value) {
        type = 'Rp.'
        if (value == 'percent') {
            type = '%'    
        }

        $('#lb-type').html(type);
    }

    function setTypeTopupManual(value) {
        type = 'Rp.'
        if (value == 'percent') {
            type = '%'    
        }

        $('#lb-type-topup-manual').html(type);
    }

    function setTypeTopupGopay(value) {
        type = 'Rp.'
        if (value == 'percent') {
            type = '%'    
        }

        $('#lb-type-topup-gopay').html(type);
    }

    </script>
@endsection