<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
        </div>
        <div class="col-lg-12" id="form-item">
            <form id="form-item-maintenance-activity">
                {!! csrf_field() !!}
                <input type="hidden" id="vending_machine_slot_id" name="vending_machine_slot_id" value="{{$vending_machine_slot->id}}">
                <input type="hidden" id="vending_machine_id" name="vending_machine_id" value="{{$vending_machine->id}}">
                
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('makanan', 'food name') !!}</label>
                    <input type='text' name="food_name" id="food_name" required class="form-control" value="{{$vending_machine_slot->food_name}}"/>
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('HPP', 'Harga Pokok Penjualan') !!}</label>
                    <input type='text' name="hpp" id="hpp" required class="form-control format-price" value="{{$vending_machine_slot->hpp}}" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Harga Jual dari Anda', 'Laba penjualan Anda didapat dari <b style="font-weight:bold">harga jual - hpp</b>') !!}</label>
                    <input type='text' name="selling_price_client" id="selling_price_client" onchange="updateSellingPriceVM()" required class="form-control format-price" value="{{$vending_machine_slot->selling_price_client}}" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Keuntungan Platform', 'Keuntungan vendor dari setiap transaksi yang berhasil') !!}</label>
                    <input type='text' name="profit_platform" id="profit_platform" readonly required class="form-control format-price" value="{{$vending_machine_slot->profit_platform}}" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Harga Jual dari Vending Machine', 'Adalah harga yang akan dikenakan customer ketika membeli item ini') !!}</label>
                    <input type='text' name="selling_price_vending_machine" id="selling_price_vending_machine" readonly required class="form-control format-price" value="{{$vending_machine_slot->selling_price_vending_machine}}" />
                </div>
                <div class="form-group mt-20 ">
                    <div class="form-group">
                        <label class="control-label mb-10">{!! label('tanggal exp', 'expired date') !!}</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' name="expired_date" value="{{date('m-d-Y a', strtotime($vending_machine_slot->expired_date))}}" class="form-control" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="button-list">
                <button type="button" class="btn btn-success bt-store pull-right" onclick="store()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // init format number    
    initFormatNumber();
    // init datepicker
    initDatetime('#datetimepicker1');
    
    function store() {
        var data = $( '#form-item-maintenance-activity' ).serialize();
        $.ajax({
            url: '{{url("front/vending-machine/".$vending_machine->id."/slot")}}',
            method: 'POST',
            data: data,
            success: function(res) {
                $("#modal-detail").html(res);
                notification('Success');
            },
            error: function(res) {
                swal('Opps, something went wrong. Please try again');
            },
        })
    }

    function updateSellingPriceVM() {
        var selling_price_client = dbNum($('#selling_price_client').val());
        var profit_platform = dbNum($('#profit_platform').val());
        var selling_price_vending_machine = selling_price_client + profit_platform;

        $('#selling_price_vending_machine').val(appNum(selling_price_vending_machine));
    }

</script>