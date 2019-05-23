<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$follow_up->name}}</h4>
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h6 class="panel-title txt-dark">Status Request Approval ke Spv. EPM</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body panel-body-epm">
                                    @if($follow_up->status_approval_to_spv_epm == 0)
                                        <button class="btn btn-success btn-lable-wrap left-label" onclick="sendRequest('{{url("ptpp/follow-up/".$follow_up->id."/send-request-epm")}}', '{{url("ptpp/follow-up/".$follow_up->id."/cancel-request-epm ")}}', 'epm')"> 
                                            <span class="btn-label"><i class="fa fa-external-link"></i> </span>
                                            <span class="btn-text">Kirim Request Approval ke Spv. EPM</span>
                                        </button>
                                    @elseif($follow_up->status_approval_to_spv_epm == 1)
                                        <button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('{{url("ptpp/follow-up/".$follow_up->id."/cancel-request-epm")}}', '{{url("ptpp/follow-up/".$follow_up->id."/send-request-epm ")}}', 'epm')">
                                            <span class="btn-label btn-label-epm"><i class="fa fa-close"></i> </span>
                                            <span class="btn-text btn-text-epm">Batalkan Request Approval ke EPM</span>
                                        </button>
                                    @else
                                        <div class="text-left">
                                            <h3 class="mb-10 @if($follow_up->status_approval_to_spv_epm == 3) text-danger @endif">{{status_approval($follow_up->status_approval_to_spv_epm, 'excel')}}</h3>
                                            <i class="fa fa-calendar"></i> {{\App\Helpers\DateHelper::formatView($follow_up->approval_at_spv_epm, true)}}
                                            <br> 
                                            @if($follow_up->notes_approval_to_spv_epm)
                                            <div style="overflow-wrap:break-word;">
                                                <i class="fa fa-file"></i> {{ $follow_up->notes_approval_to_spv_epm}}
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<script>
function sendRequest(url, url_callback, type) {
    var icon = '<i class="fa fa-spinner fa-pulse"></i>';
    $('.btn-label-'+type).html(icon);
    $('.btn-text-'+type).html('Menunggu...');

    $.ajax({
        url: url,
        success: function(res) {
            label = 'Batalkan Request Approval ke epm';

            html = `<button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-close"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
            $('.td-status-approval-'+type+'-{{$follow_up->id}}').html(res);
        },
        error: function (res) {
            notification('Error', 'Please try again');
        }
    })
}

function cancelRequest(url, url_callback, type) {
    var icon = '<i class="fa fa-spinner fa-pulse"></i>';
    $('.btn-label-'+type).html(icon);
    $('.btn-text-'+type).html('Menunggu...');

    $.ajax({
        url: url,
        success: function(res) {
            color = 'info';
            label = 'Kirim Request Approval ke OH';
            if (type == 'epm') {
                color = 'success';
                label = 'Kirim Request Approval ke epm';
            }

            html = `<button class="btn btn-`+color+` btn-lable-wrap left-label" onclick="sendRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-external-link"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
            $('.td-status-approval-'+type+'-{{$follow_up->id}}').html(res);
        },
        error: function (res) {
            notification('Error', 'Please try again');
        }
    })
}
</script>
