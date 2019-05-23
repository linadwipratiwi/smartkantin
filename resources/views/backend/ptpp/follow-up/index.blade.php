@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'PTPP Follow Up',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'PTPP Follow Up'
            ],
        ]
    ])
    
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        @if(access_is_allowed_to_view('create.ptpp.follow.up'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('ptpp/follow-up/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
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
                                            <th>Referensi</th>
                                            <th>Catatan</th>
                                            <th>Tanggal</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Approval to EPM</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_ptpp_follow_ups as $row => $follow_up)
                                        <tr id="tr-{{$follow_up->id}}">
                                            <td>{{++$row}}</td>
                                            <td>{{$follow_up->ptpp->number}}</td>
                                            <td>{{$follow_up->notes}}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($follow_up->date)}}</td>
                                            <td>{{$follow_up->createdBy->name}}</td>
                                            <td class="td-status-approval-epm-{{$follow_up->id}}">{{ user_approval($follow_up->approval_to_spv_epm) }} {!! status_approval($follow_up->status_approval_to_spv_epm) !!}</td>
                                            <td>
                                                @if($follow_up->status_approval_to_spv_epm == 0)
                                                    @if(access_is_allowed_to_view('update.ptpp.follow.up'))
                                                    <a href="{{url('ptpp/follow-up/'.$follow_up->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                        <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                    @endif
                                                    @if(access_is_allowed_to_view('delete.ptpp.follow.up'))
                                                    <a onclick="secureDelete('{{url('ptpp/follow-up/'.$follow_up->id)}}', '#tr-{{$follow_up->id}}')" onclick="document.getElementById('form-delete-{{$follow_up->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
                                                        <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                    </a>
                                                    @endif
                                                @endif
                                                
                                                <a onclick="showDetail('{{url("ptpp/follow-up/".$follow_up->id)}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                                </a>
                                                @if(access_is_allowed_to_view('approval.ptpp.follow.up'))
                                                <a onclick="showDetail('{{url("ptpp/follow-up/".$follow_up->id."/send-request")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Send request approval">
                                                    <button class="btn btn-warning btn-icon-anim btn-square btn-sm"><i class="fa fa-external-link"></i></button>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $list_ptpp_follow_ups->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->

    @include('backend.include._notes')
@stop