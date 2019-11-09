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
                    <label class="control-label mb-10">{!! label('makanan', 'food') !!}</label>
                    <select name="food_id" class="form-control" id="">
                        @foreach ($list_food as $item)
                        <option value="{{$item->id}}" @if($item->id == $vending_machine_slot->food_id) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
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