@include('backend.ptpp.follow-up._reference')

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="font-weight:bold">Form Tindak Lanjut</h6>
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
                                        <div class="col-sm-12">
                                            <label class="control-label mb-10" style="font-weight:bold">Perbaikan/tindakan segera </label>
                                            <textarea class="form-control filled-input" readonly> {{$follow_up->notes}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Tanggal</label>
                                            <input type="text" class="form-control filled-input" value="{{\App\Helpers\DateHelper::formatView($follow_up->date)}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Approval To Spv. RSD</label>
                                            <input type="text" class="form-control filled-input" value="{{ user_approval($follow_up->approval_to_spv_epm) }}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Operator/Enginer</label>
                                            <input type="text" class="form-control filled-input" value="{{$follow_up->createdBy->name}}" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label mb-10" style="font-weight:bold">Status Approval</label> <br> {!! status_approval($follow_up->status_approval_to_spv_epm) !!}
                                        </div>
                                    </div>
                                </div>

                                {{-- Detail --}}
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12 mt-10">
                                            <label class="control-label mb-10" style="font-weight:bold">Detail Masalah</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-hover display">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Penyebab Masalah</th>
                                                        <th>Pencegahan</th>
                                                        <th>PIC</th>
                                                        <th>Tanggal Selesai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($follow_up->details as $row => $detail)
                                                        <tr>
                                                            <td>{{++$row}}</td>
                                                            <td>{{$detail->problem}}</td>
                                                            <td>{{$detail->prevention}}</td>
                                                            <td>{{$detail->pic}}</td>
                                                            <td>{{\App\Helpers\DateHelper::formatView($detail->date_finish)}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dokumen yang direvisi --}}
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12 mt-10">
                                            <label class="control-label mb-10" style="font-weight:bold">Dokumen Yang Direvisi</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-hover display">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Dokumen</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($follow_up->files as $row => $file)
                                                    <tr>
                                                        <td>{{++$row}}</td>
                                                        <td>{{$file->name}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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