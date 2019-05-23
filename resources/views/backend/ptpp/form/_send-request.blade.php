<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$ptpp->name}}</h4>
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h6 class="panel-title txt-dark">Status Request Approval ke Spv. RSD</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body panel-body-rsd">
                                    @if($ptpp->status_approval_to_spv_rsd == 0)
                                        <button class="btn btn-success btn-lable-wrap left-label" onclick="sendRequest('{{url("ptpp/ ".$ptpp->id."/send-request-rsd")}}', '{{url("ptpp/ ".$ptpp->id."/cancel-request-rsd ")}}', 'rsd')"> 
                                            <span class="btn-label"><i class="fa fa-external-link"></i> </span>
                                            <span class="btn-text">Kirim Request Approval ke Spv. RSD</span>
                                        </button>
                                    @elseif($ptpp->status_approval_to_spv_rsd == 1)
                                        <button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('{{url("ptpp/ ".$ptpp->id."/cancel-request-rsd")}}', '{{url("ptpp/ ".$ptpp->id."/send-request-rsd ")}}', 'rsd')">
                                            <span class="btn-label btn-label-rsd"><i class="fa fa-close"></i> </span>
                                            <span class="btn-text btn-text-rsd">Batalkan Request Approval ke RSD</span>
                                        </button>
                                    @else
                                        <div class="text-left">
                                            <h3 class="mb-10 @if($ptpp->status_approval_to_spv_rsd == 3) text-danger @endif">{{status_approval($ptpp->status_approval_to_spv_rsd, 'excel')}}</h3>
                                            <i class="fa fa-calendar"></i> {{\App\Helpers\DateHelper::formatView($ptpp->approval_at_spv_rsd, true)}}
                                            <br> 
                                            @if($ptpp->notes_approval_to_spv_rsd)
                                            <div style="overflow-wrap:break-word;">
                                                <i class="fa fa-file"></i> {{ $ptpp->notes_approval_to_spv_rsd}}
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h6 class="panel-title txt-dark">Status Request Approval ke OH</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body panel-body-oh">
                                    @if($ptpp->status_approval_to_oh == 0)
                                        <button class="btn btn-info btn-lable-wrap left-label" onclick="sendRequest('{{url("ptpp/".$ptpp->id."/send-request-oh")}}', '{{url("ptpp/".$ptpp->id."/cancel-request-oh")}}', 'oh')">
                                            <span class="btn-label btn-label-oh"><i class="fa fa-external-link"></i> </span>
                                            <span class="btn-text btn-text-oh">Kirim Request Approval ke OH</span>
                                        </button>
                                    @elseif($ptpp->status_approval_to_oh == 1)
                                        <button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('{{url("ptpp/".$ptpp->id."/cancel-request-oh")}}', '{{url("ptpp/".$ptpp->id."/send-request-oh")}}', 'oh')">
                                            <span class="btn-label btn-label-oh"><i class="fa fa-close"></i> </span>
                                            <span class="btn-text btn-text-oh">Batalkan Request Approval ke OH</span>
                                        </button>
                                    @else
                                        <div class="text-left">
                                            <h3 class="mb-10 @if($ptpp->status_approval_to_oh==4) text-danger @endif">{{status_approval($ptpp->status_approval_to_oh, 'excel')}}</h3>
                                            <i class="fa fa-calendar"></i> {{\App\Helpers\DateHelper::formatView($ptpp->approval_at_oh, true)}} <br>
                                            @if($ptpp->notes_approval_to_oh)
                                            <div style="overflow-wrap:break-word;">
                                                <i class="fa fa-file"></i> {{ $ptpp->notes_approval_to_oh}}
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
            label = 'Batalkan Request Approval ke OH';
            if (type == 'rsd') label = 'Batalkan Request Approval ke RSD';

            html = `<button class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-close"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
            $('.td-status-approval-'+type+'-{{$ptpp->id}}').html(res);
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
            if (type == 'rsd') {
                color = 'success';
                label = 'Kirim Request Approval ke RSD';
            }

            html = `<button class="btn btn-`+color+` btn-lable-wrap left-label" onclick="sendRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-external-link"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
            $('.td-status-approval-'+type+'-{{$ptpp->id}}').html(res);
        },
        error: function (res) {
            notification('Error', 'Please try again');
        }
    })
}
</script>
