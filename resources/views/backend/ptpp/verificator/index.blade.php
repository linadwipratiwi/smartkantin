@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'PTPP Terverifikasi',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'PTPP Terverifikasi'
            ],
        ]
    ])
    
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        
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
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Kepada Fungsi</th>
                                            <th>Dari</th>
                                            <th>Lokasi/Area</th>
                                            <th>Catatan</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Approval to RSD</th>
                                            <th>Approval to OH</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_verification as $row => $verification)
                                        <tr id="tr-{{$verification->ptpp->id}}">
                                            <td>{{$verification->ptpp->number}}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($verification->ptpp->date_created)}}</td>
                                            <td>{{$verification->ptpp->category->name}}</td>
                                            <td>{{$verification->ptpp->to_function}}</td>
                                            <td>{{$verification->ptpp->from}}</td>
                                            <td>{{$verification->ptpp->location}}</td>
                                            <td>{{$verification->ptpp->notes}}</td>
                                            <td>{{$verification->ptpp->createdBy->name}}</td>
                                            <td class="td-status-approval-rsd-{{$verification->ptpp->id}}">{{ user_approval($verification->ptpp->approval_to_spv_rsd) }} {!! status_approval($verification->ptpp->status_approval_to_spv_rsd) !!}</td>
                                            <td class="td-status-approval-oh-{{$verification->ptpp->id}}">{{ user_approval($verification->ptpp->approval_to_oh) }} {!! status_approval($verification->ptpp->status_approval_to_oh) !!}</td>
                                            <td>{!! $verification->ptpp->statusFollowUp() !!}</td>
                                            <td>
                                                <a href="{{url('ptpp/verification/'.$verification->id.'/print')}}" target="_blank" data-toggle="tooltip" data-original-title="Print">
                                                    <button class="btn btn-flickr btn-icon-anim btn-square btn-sm"><i class="fa fa-print"></i></button>
                                                </a>
                                                {{-- Approval to OH --}}
                                                @if($verification->approval_to_oh == auth()->user()->id && $verification->status_approval_to_oh == 1)
                                                <a onclick="confirm('{{url('ptpp/verification/'.$verification->id.'/approve-oh')}}')"  data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
                                                </a>

                                                <a onclick="reject('{{url('reject')}}', 'PTPPVerificator', {{$verification->id}}, {{$verification->approval_to_oh}}, 'status_approval_to_oh', 'notes_approval_to_oh', 'approval_at_oh', '{{url('ptpp/verification/pending-oh')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-danger btn-icon-anim btn-square  btn-sm"><i class="icon-close"></i></button>                                                    
                                                </a>
                                                @endif
                                                
                                                <a onclick="showDetail('{{url("ptpp/".$verification->ptpp->id."/file")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                                </a>
                                                @if(access_is_allowed_to_view('approval.ptpp.form'))
                                                <a onclick="showDetail('{{url("ptpp/".$verification->ptpp->id."/send-request")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Send request approval">
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
                        {{ $list_verification->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->

    @include('backend.include._notes')
@stop