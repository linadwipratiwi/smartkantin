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
            ],
        ]
    ])
    
    <!-- /Title -->
    {{-- Filter --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <form method="get" action="{{url('history')}}">
                        {!! csrf_field() !!}
                        <div class="row">   
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Item</label>
                                <select name="item_id" placeholder="" class="form-control" id="item-id">
                                    @if(\Input::get('item_id'))
                                        <?php $item = \App\Models\Item::find(\Input::get('item_id'));?>
                                        @if ($item)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Bulan</label>
                                
                                <select name="month" placeholder="" class="form-control" id="month">
                                    <option value="">Semua bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{$i}}" @if(\Input::get('month') == $i) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Tahun</label>
                                <select name="year" placeholder="" class="form-control" id="year">
                                    <option value="">Semua tahun</option>
                                    @for ($i = 2018; $i <= date('Y'); $i++)
                                        <option value="{{$i}}" @if(\Input::get('year') == $i) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Status Approval</label>
                                <select name="status" placeholder="" class="form-control" id="status">
                                    <option value="" @if(\Input::get('status') == null) selected @endif>Semua</option>
                                    @foreach (\App\Models\MaintenanceActivityHistory::getStatusApproval() as $index => $status)
                                        <option value="{{$index}}" @if(\Input::get('status') == $index && \Input::get('status') != null) selected @endif>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-10" >
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-sm btn-anim">Filter</button>
                                <?php $param = 'item_id='.\Input::get('item_id').'&month='.\Input::get('month').'&year='.\Input::get('year').'&status='.\Input::get('status');?>
                                <a href="{{url('history/report?'.$param)}}" data-toggle="tooltip" data-original-title="Edit">
                                    <button class="btn btn-info btn-anim btn-sm" type="button"> Download Excel</button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        @if(access_is_allowed_to_view('create.history'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('history/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
                        </div>
                        @endif
                        <h6 class="panel-title txt-dark"></h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Checklist Reference</th>
                                            <th>Nama Item</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Pelaksana</th>
                                            <th>Dibuat oleh</th>
                                            <th>Status Approval</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($maintenance_activity_histories as $row => $maintenance_activity_history)
                                        <tr id="tr-{{$maintenance_activity_history->id}}">
                                            <td>{{$maintenance_activity_history->number}}</td>
                                            <td>{{$maintenance_activity_history->maintenanceActivity ? $maintenance_activity_history->maintenanceActivity->linkToRef() : '-' }} </td>
                                            <td>{{$maintenance_activity_history->item->name}}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($maintenance_activity_history->date)}}</td>
                                            <td>{{$maintenance_activity_history->notes}}</td>
                                            <td>{!! $maintenance_activity_history->executor() !!} </td>
                                            <td>{{ $maintenance_activity_history->user->name }} </td>
                                            <td class="td-status-approval-history-{{$maintenance_activity_history->id}}">{{$maintenance_activity_history->approvalTo->name}} {!! $maintenance_activity_history->statusApproval() !!} </td>
                                            <td>
                                                @if($maintenance_activity_history->status_approval == 0)
                                                    @if(access_is_allowed_to_view('update.history'))
                                                    <a href="{{url('history/'.$maintenance_activity_history->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                        <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                    @endif
                                                    @if(access_is_allowed_to_view('delete.history'))
                                                    <a onclick="secureDelete('{{url('history/'.$maintenance_activity_history->id)}}', '#tr-{{$maintenance_activity_history->id}}')" data-toggle="tooltip" data-original-title="Close">
                                                        <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                    </a>
                                                    @endif
                                                @endif

                                                <a onclick="showDetail('{{url("history/".$maintenance_activity_history->id."/reference")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="zmdi zmdi-eye"></i></button>
                                                </a>

                                                @if(access_is_allowed_to_view('approval.history'))
                                                <a onclick="showDetail('{{url("history/".$maintenance_activity_history->id."/send-request")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Send request approval">
                                                    <button class="btn btn-warning btn-icon-anim btn-square btn-sm"><i class="fa fa-external-link"></i></button>
                                                </a>
                                                @endif

                                                @if($maintenance_activity_history->approval_to == auth()->user()->id && $maintenance_activity_history->status_approval == 1)
                                                <a onclick="confirm('{{url('history/'.$maintenance_activity_history->id.'/approve')}}')"  data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
                                                </a>

                                                <a onclick="reject('{{url('reject')}}', 'MaintenanceActivityHistory', {{$maintenance_activity_history->id}}, {{$maintenance_activity_history->approval_to}}, 'status_approval', 'notes_approval', 'approval_at', '{{url('history')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-danger btn-icon-anim btn-square  btn-sm"><i class="icon-close"></i></button>                                                    
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $maintenance_activity_histories->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>

    @include('backend.include._notes')
    <!-- /Row -->
@stop


@section('scripts')
    <script>
    initItemSelect2('#item-id', '{{url("api/items")}}');
    </script>
@endsection