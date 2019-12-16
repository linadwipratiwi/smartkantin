<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$customer->name}}</h4>
            <p>Saldo pelanggan sekarang</p>
            <div class="row">
                <div class="col-md-6">
                    <font style="font-weight:bold; font-size:24px" class="text-info">Rp. {{format_price($customer->saldo + $customer->saldo_pens)}}</font>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary btn-sm btn-lable-wrap left-label pull-right ml-5" onclick="showDetail('{{url("front/customer/".$customer->id."/topup/create")}}')">
                        <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">TOPUP</span>
                    </button>
                    <a class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" href="{{url("front/customer/".$customer->id."/export")}}">
                        <span class="btn-label"><i class="fa fa-download"></i> </span><span class="btn-text">Download Report</span>
                    </a>
                </div>
            </div>
            <input type="hidden" id="saldo-customer" value="{{format_price($customer->saldo + $customer->saldo_pens)}}">
        </div>
        <div class="col-lg-12 pt-5">
            <h4>Riwayat Topup</h4>
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal</th>
                                <th class="text-right">Jumlah Topup</th>
                                <th class="text-right">Jenis Topup</th>
                                <th class="text-right">Biaya Admin</th>
                                <th class="text-right">Total</th>
                                <th>Topup oleh</th>
                            </tr>
                            </thead>
                            @foreach($list_topup as $i => $topup)
                            <tr id="tr-slot-{{$topup->id}}">
                                <td class="text-center">{{++$i}}</td>
                                <td>{{date_format_view($topup->created_at)}}</td>
                                <td class="text-right">{{format_price($topup->saldo)}}</td>
                                <td>{{$topup->topup_type}}</td>
                                <td>
                                    {{$topup->fee_topup_type == 'value' ? 'Rp. ' . format_quantity($topup->fee_topup_value): $topup->fee_topup_percent. ' %'}}
                                </td>
                                <td class="text-right">{{format_price($topup->total_topup)}}</td>
                                <td>{{$topup->createdBy ? $topup->createdBy->name : 'SYSTEM'}}</td>
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