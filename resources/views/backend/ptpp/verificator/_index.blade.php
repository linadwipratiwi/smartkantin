<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="font-weight:bold">Verifikasi PTPP #{{$ptpp->number}}</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="form-wrap">
                        <form class="form-horizontal">
                            <div class="form-group mb-0">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Status</label> <br>
                                            {!! $ptpp->verificator->status() !!}
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Tanggal</label>
                                            <input type="text" class="form-control filled-input" value="{{\App\Helpers\DateHelper::formatView($ptpp->verificator->created_at)}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">No. PTPP Baru</label>
                                            <input type="text" class="form-control filled-input" value="{{$ptpp->verificator->no_ptpp_new}}" readonly>
                                        </div>
                                    </div>
                                    
                                    <br>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="font-weight:bold">Approval PTPP Akhir</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="form-wrap">
                        <form class="form-horizontal">
                            <div class="form-group mb-0">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Approval To OH</label>
                                            <input type="text" class="form-control filled-input" value="{{user_approval($ptpp->verificator->approval_to_oh)}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Status Approval</label> <br> {!! status_approval($ptpp->verificator->status_approval_to_oh)!!}
                                        </div>
                                        <div class="col-sm-3 panel-body-oh">
                                            @if($ptpp->verificator->status_approval_to_oh == 0)
                                                <button type="button" class="btn btn-success btn-lable-wrap left-label" onclick="sendRequest('{{url("ptpp/verification/".$ptpp->verificator->id."/send-request-oh")}}', '{{url("ptpp/verification/".$ptpp->verificator->id."/cancel-request-oh ")}}', 'oh')"> 
                                                    <span class="btn-label"><i class="fa fa-external-link"></i> </span>
                                                    <span class="btn-text">Kirim Request Approval ke OH</span>
                                                </button>
                                            @elseif($ptpp->verificator->status_approval_to_oh == 1)
                                                <button type="button" class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('{{url("ptpp/verification/".$ptpp->verificator->id."/cancel-request-oh")}}', '{{url("ptpp/verification/".$ptpp->verificator->id."/send-request-oh ")}}', 'oh')">
                                                    <span class="btn-label btn-label-oh"><i class="fa fa-close"></i> </span>
                                                    <span class="btn-text btn-text-oh">Batalkan Request Approval ke OH</span>
                                                </button>
                                            @else
                                                <div class="text-left">
                                                    <h3 class="mb-10 @if($ptpp->verificator->status_approval_to_oh == 3) text-danger @endif">{{status_approval($ptpp->verificator->status_approval_to_oh, 'excel')}}</h3>
                                                    <i class="fa fa-calendar"></i> {{\App\Helpers\DateHelper::formatView($ptpp->verificator->approval_at_oh, true)}}
                                                    <br> 
                                                    @if($ptpp->verificator->notes_approval_to_oh)
                                                    <div style="overflow-wrap:break-word;">
                                                        <i class="fa fa-file"></i> {{ $ptpp->verificator->notes_approval_to_oh}}
                                                    </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

            html = `<button type="button" class="btn btn-warning btn-lable-wrap left-label" onclick="cancelRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-close"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
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

            html = `<button type="button" class="btn btn-`+color+` btn-lable-wrap left-label" onclick="sendRequest('`+url_callback+`', '`+url+`', '`+type+`')">
                <span class="btn-label btn-label-`+type+`"><i class="fa fa-external-link"></i> </span>
                <span class="btn-text btn-text-`+type+`">`+label+`</span>
            </button>`;
            $('.panel-body-'+type).html(html);
        },
        error: function (res) {
            notification('Error', 'Please try again');
        }
    })
}

</script>