
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="font-weight:bold">PTPP Reference. {{$ptpp->number}}</h6>
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
                                            <label class="control-label mb-10" style="font-weight:bold">Tanggal</label>
                                            <input type="text" class="form-control filled-input" value="{{App\Helpers\DateHelper::formatView($ptpp->date_created)}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Kategori</label>
                                            <input type="text" class="form-control filled-input" value="{{$ptpp->category->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Kepada Fungsi</label>
                                            <input type="text" class="form-control filled-input" value="{{$ptpp->to_function}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Dari</label>
                                            <input type="text" class="form-control filled-input" value="{{$ptpp->from}}" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Lokasi</label>
                                            <input type="text" class="form-control filled-input" value="{{$ptpp->location}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Dibuat Oleh</label>
                                            <input type="text" class="form-control filled-input" value="{{$ptpp->createdBy->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Approval To RSD</label> <br>
                                            {{ user_approval($ptpp->approval_to_spv_rsd) }} {!! status_approval($ptpp->status_approval_to_spv_rsd) !!}
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Approval To OH</label> <br>
                                            {{ user_approval($ptpp->approval_to_oh) }} {!! status_approval($ptpp->status_approval_to_oh) !!}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label mb-10" style="font-weight:bold">Ketidaksesuaian / Potensi Yang Ditemukan</label>
                                            <textarea class="form-control filled-input" readonly> {{$ptpp->notes}}</textarea>
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