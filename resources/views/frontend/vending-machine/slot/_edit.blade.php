<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
        </div>
        <form id="form-file" enctype="multipart/form-data">
            <div class="col-lg-12" id="form-item">
                {!! csrf_field() !!}
                <input type="hidden" id="vending_machine_slot_id" name="vending_machine_slot_id" value="{{$vending_machine_slot->id}}">
                <input type="hidden" id="vending_machine_id" name="vending_machine_id" value="{{$vending_machine->id}}">
                
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">Slot</label>
                    <input type='text' readonly required class="form-control" value="{{$vending_machine_slot->convertToAsci()}}"/>
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('makanan', 'food name') !!}</label>
                    <input type='text' name="food_name" id="food_name" required class="form-control" value="{{$vending_machine_slot->food_name}}"/>
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Kapasitas Maksimal', 'Max Capacity') !!}</label>
                    <input type='number' min="1" name="capacity" id="capacity" required class="form-control" value="{{$vending_machine_slot->capacity}}"/>
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('HPP', 'Harga Pokok Penjualan') !!}</label>
                    <input type='text' name="hpp" id="hpp" required class="form-control format-price" value="{{$vending_machine_slot->hpp}}" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Harga Jual dari Anda', 'Laba penjualan Anda didapat dari <b style="font-weight:bold">harga jual - hpp</b>') !!}</label>
                    <input type='text' name="selling_price_client" id="selling_price_client" onchange="updateSellingPriceVM()" required class="form-control format-price" value="{{$vending_machine_slot->selling_price_client}}" />
                </div>
                <?php $client = $vending_machine_slot->vendingMachine->client;?>
                <div class="form-group mt-20 ">
                    <input type="hidden" id="profit-platform-type" value="{{$client->profit_platform_type}}">
                    <label class="control-label mb-10">{!! label('Profit Platform', 'keuntungan untuk platform / pengembang alat') !!}</label>
                    <div class="input-group"> 
                        <span class="input-group-addon" id="lb-type">@if($client->profit_platform_type == 'value') Rp. @else % @endif</span>
                        <?php
                            $value_profit = $client->profit_platform_value;
                            if ($client->profit_platform_type == 'percent') {
                                $value_profit = $client->profit_platform_percent;
                            }
                        ?>
                        <input type="text" id="profit_platform_value" readonly name="profit_platform_value" class="form-control format-price" value="{{$value_profit}}" placeholder="">
                    </div>
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
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Gambar', 'Photo') !!}</label>
                    <input type='file' name="file" class="form-control" /> <br>
                    @if($vending_machine_slot->photo)<img src="{{url($vending_machine_slot->photo)}}" width="50px" height="50px" alt="">@endif
                </div>
            </div>
            <div class="modal-footer">
                <div class="button-list">
                    <button type="submit" class="btn btn-success bt-store pull-right">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // init format number    
    initFormatNumber();
    // init datepicker
    initDatetime('#datetimepicker1');
    $("#form-file").on('submit', (function(ev) {
        ev.preventDefault();
        store();
    }));

    function store() {
        var form = $('#form-file')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '{{url("front/vending-machine/".$vending_machine->id."/slot")}}',
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,
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
        var profit_platform_type = $('#profit-platform-type').val();

        var selling_price_client = dbNum($('#selling_price_client').val());
        var profit_platform_value = dbNum($('#profit_platform_value').val());
        
        var selling_price_vending_machine = 0;
        if (profit_platform_type == 'value') {
            selling_price_vending_machine = selling_price_client + profit_platform_value;
        }

        if (profit_platform_type == 'percent') {
            selling_price_vending_machine = (selling_price_client * profit_platform_value / 100) + selling_price_client;
        }
        

        $('#selling_price_vending_machine').val(appNum(selling_price_vending_machine));
    }

</script>