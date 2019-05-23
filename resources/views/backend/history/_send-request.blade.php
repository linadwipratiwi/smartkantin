<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$maintenance_activity->number}}</h4>
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h6 class="panel-title txt-dark">Status Request Approval Ke {{ user_approval($maintenance_activity->approval_to)}}</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body panel-body-history">
                                    @if($maintenance_activity->status_approval == 0)
                                        <button class="btn btn-info btn-lable-wrap left-label" onclick="sendRequest('{{url("history/".$maintenance_activity->id."/send-request-history")}}', '{{url("history/".$maintenance_activity->id."/cancel-request-history")}}', 'history')">
                                            <span class="btn-label btn-label-history"><i class="fa fa-external-link"></i> </span>
                                            <span class="btn-text btn-text-history">Kirim Request Approval</span>
                                        </button>
                                    @elseif($maintenance_activity->status_approval == 1)
                                        <button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('{{url("history/".$maintenance_activity->id."/cancel-request-history")}}', '{{url("history/".$maintenance_activity->id."/send-request-history")}}', 'history')">
                                            <span class="btn-label btn-label-history"><i class="fa fa-close"></i> </span>
                                            <span class="btn-text btn-text-history">Batalkan Request Approval</span>
                                        </button>
                                    @else
                                    <div class="text-left">
                                        <h3 class="mb-10 @if($maintenance_activity->status_approval==4) text-danger @endif">{{status_approval($maintenance_activity->status_approval, 'excel')}}</h3>
                                        <i class="fa fa-calendar"></i> {{\App\Helpers\DateHelper::formatView($maintenance_activity->approval_at, true)}} <br>
                                        @if($maintenance_activity->notes_approval)
                                        <div style="overflow-wrap:break-word;">
                                            <i class="fa fa-file"></i> {{ $maintenance_activity->notes_approval}}
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
            label = 'Batalkan Request Approval';

            html = `<button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-close"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
            $('.td-status-approval-'+type+'-{{$maintenance_activity->id}}').html(res);
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
            label = 'Kirim Request Approval';

            html = `<button class="btn btn-`+color+` btn-lable-wrap left-label" onclick="sendRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-external-link"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
            $('.td-status-approval-'+type+'-{{$maintenance_activity->id}}').html(res);
        },
        error: function (res) {
            notification('Error', 'Please try again');
        }
    })
}
</script>
