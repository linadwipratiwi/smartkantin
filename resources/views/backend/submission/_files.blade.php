<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$submission->name}}</h4>
            @if(access_is_allowed_to_view('file.submission'))
                @if($submission->status_approval_to_oh == 0 && $submission->status_approval_to_spv_epm == 0)
                    <button class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" onclick="showDetail('{{url("submission/file/create/".$submission->id)}}')"> <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">Buat baru</span></button>
                @endif
            @endif
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-left">No</th>
                                <th class="text-left">Nama</th>
                                <th class="text-left">File</th>
                                @if(access_is_allowed_to_view('file.submission'))
                                <th class="text-center" style="min-width: 200px">Aksi</th>
                                @endif
                            </tr>
                            </thead>
                            @foreach($submission->files as $i => $file)
                                <tr id="tr-submission-file-{{$file->id}}">
                                    <td class="">{{++$i}}</td>
                                    <td>{{$file->name}}</td>
                                    <td>{!! $file->link() !!}</td>
                                    @if(access_is_allowed_to_view('file.submission'))
                                    <td>
                                        @if($submission->status_approval_to_oh == 0 && $submission->status_approval_to_spv_epm == 0)
                                            <a  onclick="showDetail('{{url("submission/file/".$file->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                                <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                            </a>
                                            <a onclick="secureDelete('{{url('submission/file/'.$file->id)}}', '#tr-submission-file-{{$file->id}}')" data-toggle="tooltip" data-original-title="Delete"> 
                                                <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>
                                            </a>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
