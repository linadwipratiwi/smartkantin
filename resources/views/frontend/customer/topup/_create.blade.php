<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$customer->name}}</h4>
            <p>Saldo saat ini : <b style="font-weight:bold" class="text-info">Rp. {{format_price($customer->saldo + $customer->saldo_pens)}}</b></p>
        </div>
        <div class="col-lg-12" id="form-item">
            <form id="form-topup">
                {!! csrf_field() !!}
                <input type="hidden" id="customer_id" name="customer_id" value="{{$customer->id}}">
                <input type="text" name="topup_type" id="topup_type" value="manual">
                <input type="text" name="fee_topup_type" id="fee_topup_type" value="{{client()->fee_topup_manual_type}}">
                <input type="text" name="fee_topup_percent" id="fee_topup_percent" value="{{client()->fee_topup_manual_percent}}">
                <input type="text" name="fee_topup_value" id="fee_topup_value" value="{{client()->fee_topup_manual_value}}">

                <div class="form-group mt-20" id="">
                    <label class="control-label mb-10">Nominal yang akan di topup</label>
                    <div class="input-group"> 
                        <span class="input-group-addon" id="lb-type">Rp.</span>
                        <input type="text" id="saldo" name="saldo" onkeyup="calculate()" class="form-control format-price" placeholder="">
                    </div>
                    <p class="txt-danger">Biaya Admin: {{client()->fee_topup_manual_type == 'value' ? 'Rp. '.format_quantity(client()->fee_topup_manual_value) : format_quantity(client()->fee_topup_manual_percent). ' % dari jumlah topup'}} </p>
                </div>
                <div class="form-group mt-20" id="">
                    <label class="control-label mb-10">Total Topup + Biaya Admin</label>
                    <div class="input-group"> 
                        <span class="input-group-addon" id="lb-biaya-admin">Rp.</span>
                        <input type="text" id="total_topup" name="total_topup" readonly value="0" class="form-control format-price" placeholder="">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="button-list">
                <button type="button" class="btn btn-success bt-store pull-right" onclick="swalConfirmation()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // init format number    
    initFormatNumber();

    function calculate() {
        var type = '{{client()->fee_topup_manual_type}}';
        var saldo = $('#saldo').val();
        if (type == 'value') {
            var fee = {{client()->fee_topup_manual_value}};

            var total_topup = dbNum(saldo) + fee;
            $('#total_topup').val(total_topup);
        }

        if (type == 'percent') {
            var fee = {{client()->fee_topup_manual_percent}};

            var total_topup = (dbNum(saldo) * fee / 100) + dbNum(saldo);
            $('#total_topup').val(total_topup);
        }
    }

    function store() {
        var data = $( '#form-topup' ).serialize();
        $.ajax({
            url: '{{url("front/customer/topup/store")}}',
            method: 'POST',
            data: data,
            success: function(res) {
                $("#modal-detail").html(res);
                notification('Success');
                var saldo = $('#saldo-customer').val(); // di file _index
                $(".td-saldo-customer-{{$customer->id}}").html(saldo);
            },
            error: function(res) {
                swal('Opps, something went wrong. Please try again');
            },
        });
    }

    function swalConfirmation() {
        swal({
            title: "Anda yakin ingin menambah saldo dari pelanggan ini?",
            text: "Saldo pelanggan akan berubah dan tidak bisa diedit.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f2b701",
            confirmButtonText: "Ya, tentu!",
            closeOnConfirm: true
        }, function () {
            store();
        });
    }     

</script>