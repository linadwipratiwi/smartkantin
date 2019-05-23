@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Checklist',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Checklist'
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
                            <form method="post" action="{{url('checklist/'.$maintanance_activity->id)}}">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Nama Item', 'Item name') !!}</label>
                                    <select name="item_id" placeholder="" onchange="getItemMaintenanceActivity(this.value)" class="form-control" id="item-id" required>
                                        <option value="{{$maintanance_activity->item->id}}" selected>{{$maintanance_activity->item->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Pilih pekerjaan', 'choose type of work') !!}</label>
                                    <select name="item_maintenance_activity_id" placeholder="" class="form-control" id="item-maintenance-activity-id">
                                        <option value="{{$maintanance_activity->itemMaintenanceActivity->id}}" selected>{{$maintanance_activity->itemMaintenanceActivity->name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label mb-10">{!! label('tanggal', 'Date') !!}</label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' name="date" value="{{date('m-d-Y a', strtotime($maintanance_activity->date))}}" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Catatan', 'notes') !!}</label>
                                    <textarea name="notes" id="notes" cols="10" rows="5" class="form-control">{{$maintanance_activity->notes}}</textarea>
                                </div>
                                <div class="form-group mb-30">
                                    <label class="control-label mb-10 text-left">{!! label('Status', 'status') !!}</label>
                                    @foreach (\App\Models\MaintenanceActivity::getStatus() as $index => $status)
                                    <div class="radio radio-info">
                                        <input type="radio" name="status" id="radio-{{$index}}" value="{{$index}}" @if($maintanance_activity->status==$index) checked="checked" @endif>
                                        <label for="radio-{{$index}}">
                                            {{$status}}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Disetujui oleh', 'approval to') !!}</label>
                                    <select name="approval_to" placeholder="" class="form-control" id="approval-to">
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}" @if($maintanance_activity->approval_to == $user->id) selected @endif>{{$user->name}}</option>
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
    // getItemMaintenanceActivity({{$maintanance_activity->item->id}}, 0);

    function getItemMaintenanceActivity(item_id) {
        $.ajax({
            url: '{{url("api/item-maintenance-activity")}}/'+item_id,
            success: function (result) {
                $("#item-maintenance-activity-id").html('').select2({data: {id:null, text: null}});
                $("#item-maintenance-activity-id").select2({
                    data: result
                })
            },
            error: function (res) {
                notification('Terjadi kegagalan dalam mengambil data');
            }
        })    
    }
    
    </script>
@endsection