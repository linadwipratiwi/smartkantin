<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$customer->name}}</h4>
            <p>Saldo saat ini : <b style="font-weight:bold" class="text-info">Rp. {{format_price($customer->saldo)}}</b></p>
        </div>
        <div class="col-lg-12" id="form-item">
            <form id="form-topup">
                {!! csrf_field() !!}
                <input type="hidden" id="customer_id" name="customer_id" value="{{$customer->id}}">
                
                <div class="form-group mt-20" id="">
                    <label class="control-label mb-10">Nominal yang akan di topup</label>
                    <div class="input-group"> 
                        <span class="input-group-addon" id="lb-type">Rp.</span>
                        <input type="text" id="saldo" name="saldo" class="form-control format-price" placeholder="">
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