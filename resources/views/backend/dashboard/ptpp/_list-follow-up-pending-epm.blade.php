<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <h6 class="panel-title txt-dark">Daftar Form PTPP Follow Up Butuh Approval</h6>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    @if($list_ptpp_follow_up_pending_epm->count())
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
                                    @foreach($list_ptpp_follow_up_pending_epm as $row => $follow_up)
                                    <tr id="tr-{{$follow_up->id}}">
                                        <td>{{++$row}}</td>
                                        <td>{{$follow_up->ptpp->number}}</td>
                                        <td>{{$follow_up->notes}}</td>
                                        <td>{{\App\Helpers\DateHelper::formatView($follow_up->date)}}</td>
                                        <td>{{$follow_up->createdBy->name}}</td>
                                        <td class="td-status-approval-epm-{{$follow_up->id}}">{{ user_approval($follow_up->approval_to_spv_epm) }} {!! status_approval($follow_up->status_approval_to_spv_epm) !!}</td>
                                        <td>
                                            {{-- Approval to OH --}}
                                            @if($follow_up->approval_to_spv_epm == auth()->user()->id && $follow_up->status_approval_to_spv_epm == 1)
                                            <a onclick="confirm('{{url('ptpp/follow-up/'.$follow_up->id.'/approve-epm')}}')"  data-toggle="tooltip" data-original-title="Close">
                                                <button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
                                            </a>

                                            <a onclick="reject('{{url('reject')}}', 'PTPPFollowUp', {{$follow_up->id}}, {{$follow_up->approval_to_spv_epm}}, 'status_approval_to_spv_epm', 'notes_approval_to_spv_epm', 'approval_at_spv_epm', '{{url('/')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
                                                <button class="btn btn-danger btn-icon-anim btn-square  btn-sm"><i class="icon-close"></i></button>                                                    
                                            </a>
                                            @endif
                                            
                                            <a onclick="showDetail('{{url("ptpp/follow-up/".$follow_up->id)}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    TIDAK ADA FORM PTPP FOLLOW UP YANG BUTUH PERSETUJUAN ANDA
                    @endif
                </div>
            </div>
        </div>	
    </div>
</div>