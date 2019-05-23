
@include('backend.history._reference')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="font-weight:bold">History {{$history->number}}</h6>
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
                                            <label class="control-label mb-10" style="font-weight:bold">Nama Item</label>
                                            <input type="text" class="form-control filled-input" value="{{$history->item->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Keterangan</label>
                                            <input type="text" class="form-control filled-input" value="{{$history->notes}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Pelaksana</label> <br>
                                            {!! $history->executor() !!}
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Dibuat Oleh</label>
                                            <input type="text" class="form-control filled-input" value="{{$history->user->name}}" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Approval To</label>
                                            <input type="text" class="form-control filled-input" value="{{$history->approvalTo->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Status Approval</label> <br>
                                            {!! $history->statusApproval() !!}
                                        </div>
                                        <div class="col-sm-3">
                                            {{-- request approval --}}
                                            @if($history->user->id == auth()->user()->id)
                                                @if($history->status_approval == 0)
                                                <a onclick="requestApproval('{{url("history/".$history->id."/request-approval")}}')" data-toggle="tooltip" data-original-title="Edit">
                                                    <button type="button" class="btn btn-primary btn-icon-anim btn-sm"><i class="fa fa-location-arrow"></i> Kirim request approval</button>
                                                </a>
                                                @endif
                                            @endif
                                        </div>
                                        @if($history->status_approval == 3)
                                        <div class="col-sm-4">
                                            <label class="control-label mb-10" style="font-weight:bold">Alasan Penelokan</label> <br>
                                            <textarea class="form-control filled-input" readonly>{{$history->notes_approval}} </textarea>
                                        </div>
                                        @endif
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