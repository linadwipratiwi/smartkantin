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
                            <form method="post" action="{{url('client')}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!} </label>
                                    <input type="text" class="form-control" value="" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Telepon', 'Phone') !!} </label>
                                    <input type="text" name="phone" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Perusahaan', 'Company') !!} </label>
                                    <input type="text" name="company" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Alamat', 'Address') !!} </label>
                                    <textarea name="address" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Logo', 'Logo') !!} </label>
                                    <input type="file" name="file" class="form-control">
                                </div>
                                <div class="form-groups">
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

                                {{-- Fee Topup ManuaL --}}
                                <div class="form-group ">
                                    <label class="control-label mb-10">Biaya Topup Tambahan Manual</label>
                                    <select name="fee_topup_manual_type" id="fee_topup_manual_type" onchange="setTypeTopupManual(this.value)" class="form-control">
                                        <option value="value">Value</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                                <div class="form-group" id="">
                                    <label class="control-label mb-10">Presentase profit / dengan set value</label>
                                    <div class="input-group"> 
                                        <span class="input-group-addon" id="lb-type-topup-manual">Rp</span>
                                        <input type="text" id="fee_topup_manual_value" name="fee_topup_manual_value" class="form-control format-price" value="" placeholder="">
                                    </div>
                                </div>

                                {{-- Fee Topup Gopay --}}
                                <div class="form-group ">
                                    <label class="control-label mb-10">Biaya Topup Tambahan Gopay</label>
                                    <select name="fee_topup_gopay_type" id="fee_topup_gopay_type" onchange="setTypeTopupGopay(this.value)" class="form-control">
                                        <option value="value">Value</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                                <div class="form-group" id="">
                                    <label class="control-label mb-10">Presentase profit / dengan set value</label>
                                    <div class="input-group"> 
                                        <span class="input-group-addon" id="lb-type-topup-gopay">Rp</span>
                                        <input type="text" id="fee_topup_gopay_value" name="fee_topup_gopay_value" class="form-control format-price"  placeholder="">
                                    </div>
                                </div>
                                <div class="seprator-block"></div>
                                {{-- Visit Transaction --}}
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-user mr-10"></i>Akun Client</h6>
                                <hr class="light-grey-hr" />
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Username</label>
                                    <input type="text" name="username" required class="form-control" id="" value="" placeholder="username">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Password', 'Default: 12345') !!} </label>
                                    <input type="text" name="password" class="form-control" id="" value="12345">
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" onclick="return validation()" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">submit</span></button>
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