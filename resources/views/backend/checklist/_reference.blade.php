<?php if(!$reference) return ;?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="font-weight:bold">Pengecekan Terakhir Tgl. {{\App\Helpers\DateHelper::formatView($reference->date)}}</h6>
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
                                            <input type="text" class="form-control filled-input" value="{{$reference->item->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Kategori</label>
                                            <input type="text" class="form-control filled-input" value="{{$reference->itemMaintenanceActivity->category->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Pengecekan</label>
                                            <input type="text" class="form-control filled-input" value="{{$reference->itemMaintenanceActivity->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Periode</label>
                                            <input type="text" class="form-control filled-input" value="{{$reference->itemMaintenanceActivity->periode_value}} {{$reference->itemMaintenanceActivity->periode->name}}" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label mb-10" style="font-weight:bold">Keterangan</label>
                                            <textarea class="form-control filled-input" readonly> {{$reference->notes}}</textarea>
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