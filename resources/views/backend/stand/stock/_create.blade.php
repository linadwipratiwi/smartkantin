<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
        </div>
        <div class="col-lg-12" id="form-item">
            <form id="form-item-maintenance-activity">
                <div class="alert alert-warning alert-dismissable mt-20" id="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Hi! Lasttwo days left form the trial.
                </div>
                {!! csrf_field() !!}
                <input type="hidden" id="vending_machine_stock_id" name="vending_machine_stock_id" value="">
                <input type="hidden" id="vending_machine_id" name="vending_machine_id" value="{{$vending_machine->id}}">
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Stand', 'name') !!}</label>
                    <input type="text" readonly class="form-control readonly" value="{{$vending_machine->name}}">
                </div>
                
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Slot', 'Slot') !!}</label>
                    <select name="slot_id" class="form-control" id="slot-id" onchange="getStock(this.value)">
                        <option value="">Pilih slot</option>
                        @foreach ($vending_machine->slots as $slot)
                            <option value="{{$slot->id}}">{{$slot->name}} - {{$slot->food_name}}</option>
                        @endforeach
                    </select>
                    <p id="info-stock"></p>
                </div>
                
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Stok', 'masukan jumlah stok yang akan ditambakan atau dikurangi') !!}</label>
                    <input type='number' name="stock" id="stock" required class="form-control" />
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
    initSelect2('#slot-id');
    var current_stock = 0;    
    function store() {
        var data = $( '#form-item-maintenance-activity' ).serialize();
        $.ajax({
            url: '{{url("stand/".$vending_machine->id."/stock")}}',
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

    /** Get Stok **/
    function getStock(id) {
        $.ajax({
            url: '{{url("stand/".$vending_machine->id."/stock")}}/'+id,
            method: 'get',
            success: function(res) {
                console.log(res)
                current_stock = res.stock;
                var info = 'Stok sekarang '+res.stock+' item';
                $('#info-stock').html(info);
            },
            error: function(res) {
                swal('Opps, something went wrong. Please try again');
            },
        })
    }

</script>