@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Submission Pending Approval',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Submission'
            ],
        ]
    ])
    
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
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
                                            <th>Pemohon</th>
                                            <th>Category</th>
                                            <th>Nama Barang</th>
                                            <th>Alasan Pengajuan</th>
                                            <th>Approval OH</th>
                                            <th>Approval Spv. EPM</th>
                                            <th>Tgl. Dibuat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $row => $submission)
                                        <tr id="tr-{{$submission->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$submission->createdBy->name}}</td>
                                            <td>{{$submission->category->name}}</td>
                                            <td>{{$submission->name}}</td>
                                            <td>{{$submission->notes}}</td>
                                            <td>{{ user_approval($submission->approval_to_oh) }} {!! status_approval($submission->status_approval_to_oh) !!}</td>
                                            <td>{{ user_approval($submission->approval_to_spv_epm) }} {!! status_approval($submission->status_approval_to_spv_epm) !!}</td>
                                            <td>{{App\Helpers\DateHelper::formatView($submission->created_at, true)}}</td>
                                            <td>
                                                <a onclick="showDetail('{{url("submission/".$submission->id."/file")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                                </a>
                                                {{-- Approval to OH --}}
                                                @if($submission->approval_to_oh == auth()->user()->id && $submission->status_approval_to_oh == 1)
                                                <a onclick="confirm('{{url('submission/'.$submission->id.'/approve-oh')}}')"  data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
                                                </a>

                                                <a onclick="reject('{{url('reject')}}', 'Submission', {{$submission->id}}, {{$submission->approval_to_oh}}, 'status_approval_to_oh', 'notes_approval_to_oh', 'approval_at_oh', '{{url('submission/pending-approval-oh')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-danger btn-icon-anim btn-square  btn-sm"><i class="icon-close"></i></button>                                                    
                                                </a>
                                                @endif

                                                {{-- Approval to OH --}}
                                                @if($submission->approval_to_spv_epm == auth()->user()->id && $submission->status_approval_to_spv_epm == 1)
                                                <a onclick="confirm('{{url('submission/'.$submission->id.'/approve-epm')}}')"  data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
                                                </a>

                                                <a onclick="reject('{{url('reject')}}', 'Submission', {{$submission->id}}, {{$submission->approval_to_spv_epm}}, 'status_approval_to_spv_epm', 'notes_approval_to_spv_epm', 'approval_at_spv_epm', '{{url('submission/pending-approval-epm')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
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
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->

    @include('backend.include._notes')
@stop