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
                    <label class="control-label mb-10">{!! label('makanan', 'nama makananan') !!}</label>
                    <input type='text' name="food_name" id="food_name" value="{{$vending_machine_slot->food_name}}" placeholder="Cth. Nasi Goreng" required
                        class="form-control" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Kategori', 'Jenis makanan yang dijual') !!}</label>
                    <select name="category_id" class="form-control" id="">
                        @foreach ($categories as $item)
                        <option value="{{$item->id}}" @if($item->id == $vending_machine_slot->category_id) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('stok saat ini', 'stock') !!}</label>
                    <input type='number' min='0' name="stock" id="stock" value="{{$vending_machine_slot->stock}}" required class="form-control" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Harga Jual', 'selling price') !!}</label>
                    <input type='text' name="selling_price_client" value="{{$vending_machine_slot->selling_price_client}}" id="selling_price_client" required
                        class="form-control format-price" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('Gambar', 'Photo') !!}</label>
                    <input type='file' name="file" class="form-control" /> <br>
                    <img src="{{url($vending_machine_slot->photo)}}" width="50px" height="50px" alt="">
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
    
    $("#form-file").on('submit', (function(ev) {
        ev.preventDefault();
        store();
    }));

    function store() {
        var form = $('#form-file')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '{{url("front/stand/".$vending_machine->id."/product")}}',
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

</script>