<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$ptpp->number}}</h4>
            @if(access_is_allowed_to_view('file.ptpp.form'))
                @if($ptpp->status_approval_to_oh == 0 && $ptpp->status_approval_to_spv_rsd == 0)
                    <button class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" onclick="showDetail('{{url("ptpp/file/create/".$ptpp->id)}}')"> <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">Buat baru</span></button>
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
                                @if(access_is_allowed_to_view('file.ptpp.form'))
                                <th class="text-left" style="min-width: 200px">Aksi</th>
                                @endif
                            </tr>
                            </thead>
                            @foreach($ptpp->files as $i => $file)
                                <tr id="tr-ptpp-file-{{$file->id}}">
                                    <td class="">{{++$i}}</td>
                                    <td>{{$file->name}}</td>
                                    <td>{!! $file->link() !!}</td>
                                    @if(access_is_allowed_to_view('file.ptpp.form'))
                                    <td>
                                        @if($ptpp->status_approval_to_oh == 0 && $ptpp->status_approval_to_spv_rsd == 0)
                                            <a  onclick="showDetail('{{url("ptpp/file/".$file->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                                <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                            </a>
                                            <a onclick="secureDelete('{{url('ptpp/file/'.$file->id)}}', '#tr-ptpp-file-{{$file->id}}')" data-toggle="tooltip" data-original-title="Delete"> 
                                                <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>
                                            </a>
                                        @else
                                        -
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
