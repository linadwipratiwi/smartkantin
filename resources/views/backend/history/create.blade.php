@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Riwayat Alat',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Riwayat Alat'
            ]
        ]
    ])
    
    <!-- /Title -->
    
    @include('backend.history._reference')
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="post" action="{{url('history')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="maintenance_activity_id" id="" value="{{$reference ? $reference->id : null}}">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Nama Item', 'Item name') !!}</label>
                                    <select name="item_id" placeholder="" class="form-control" id="item-id" required>
                                        @if($reference)
                                        <option value="{{$reference->item->id}}" selected>{{$reference->item->name}}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label mb-10">{!! label('tanggal', 'Date') !!}</label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' name="date" value="{{date('Y-m-d H:i:s')}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Catatan', 'notes') !!}</label>
                                    <textarea name="notes" id="notes" cols="10" rows="5" class="form-control">{{old('notes')}}</textarea>
                                </div>
                                <div class="form-group mb-30">
                                    <div class="checkbox checkbox-primary">
                                        <input id="is-vendor" type="checkbox" name="is_executor_vendor" value="1" onchange="isVendor()">
                                        <label for="is-vendor">
                                            Centang jika pelakasan adalah vendor
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mb-30 vendor hidden">
                                    <label class="control-label mb-10 text-left">{!! label('vendor', 'vendor') !!}</label>
                                    <select name="vendor_id" placeholder="" class="form-control" id="vendor-id"></select>
                                </div>
                                <div class="form-group executor-name">
                                    <label class="control-label mb-10 text-left">{!! label('Nama Pelaksana', 'executor name') !!}</label>
                                    <input type="text" class="form-control" name="executor_name" id="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Disetujui oleh', 'approval to') !!}</label>
                                    <select name="approval_to" placeholder="" class="form-control" id="approval-to">
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-30">
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox2" type="checkbox" name="is_request_approval" value="1" checked="">
                                        <label for="checkbox2">
                                            Langsung kirim permintaan approve ke SPV
                                        </label>
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
    initItemSelect2('#item-id', '{{url("api/items")}}');
    initVendorSelect2('#vendor-id', '{{url("api/vendors")}}');
    function isVendor() {
        if ($('#is-vendor').is(':checked')) {
            $('.vendor').removeClass('hidden');
            $('.executor-name').addClass('hidden');
        } else {
            $('.vendor').addClass('hidden');
            $('.executor-name').removeClass('hidden');
        }
    }
    
    </script>
@endsection