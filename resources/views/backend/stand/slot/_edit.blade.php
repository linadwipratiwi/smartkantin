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
                    <label class="control-label mb-10">{!! label('nama', 'name') !!}</label>
                    <input type='text' name="name" id="name" required class="form-control" value="{{$vending_machine_slot->name}}" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('alias', 'alias') !!}</label>
                    <input type='text' name="alias" id="alias" required class="form-control" value="{{$vending_machine_slot->alias}}"/>
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
                    <label class="control-label mb-10">{!! label('keuntungan platform', 'profit platform') !!}</label>
                    <select name="profit_platform_type" id="profit_platform_type" onchange="setType(this.value)" class="form-control">
                        <option value="value" @if($vending_machine_slot->profit_platform_type == 'value') selected @endif>Value</option>
                        <option value="percent" @if($vending_machine_slot->profit_platform_type == 'percent') selected @endif>Percent</option>
                    </select>
                </div>
                <div class="form-group mt-20" id="">
                    <label class="control-label mb-10">{!! label('Presentase profit / dengan set value', 'Jika Anda memilih type percent, maka isi dengan percent (max: 100). Jika dengan value, maka isi dengan harga (3000)') !!}</label>
                    <div class="input-group"> 
                        <span class="input-group-addon" id="lb-type">@if($vending_machine_slot->profit_platform_type == 'value') Rp. @else % @endif</span>
                        <?php
                            $value_profit = $vending_machine_slot->profit_platform_value;
                            if ($vending_machine_slot->profit_platform_type == 'percent') {
                                $value_profit = $vending_machine_slot->profit_platform_percent;
                            }
                        ?>
                        <input type="text" id="profit_platform_value" name="profit_platform_value" class="form-control format-price" value="{{$value_profit}}" placeholder="">
                    </div>
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

    function setType(value) {
        type = 'Rp.'
        if (value == 'percent') {
            type = '%'    
        }

        $('#lb-type').html(type);
    }
    
    function store() {
        var type = $('#profit_platform_type :selected').val();
        var profit = $('#profit_platform_value').val();

        if (profit == '') {
            notification('Profit wajib diisi');
            return false;
        }

        profit = parseInt(dbNum(profit));
        if (type == 'percent') {
            if (profit > 100) {
                notification('Maksimal profit adalah 100%');
                return false;
            }
        }

        var data = $( '#form-item-maintenance-activity' ).serialize();
        $.ajax({
            url: '{{url("stand/".$vending_machine->id."/slot")}}',
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

</script>