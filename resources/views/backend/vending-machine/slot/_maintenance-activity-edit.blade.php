
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$item->name}}</h4>
        </div>
        <div class="col-lg-12" id="form-item">
            <form id="form-item-maintenance-activity">
                {!! csrf_field() !!}
                <input type="hidden" id="item_id" name="item_id" value="{{$item->id}}">
                <input type="hidden" id="maintenance_activity_id" name="maintenance_activity_id" value="{{$maintenance_activity->id}}">
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('jenis perawatan', 'name') !!}</label>
                    <input type='text' name="name" id="name" value="{{$maintenance_activity->name}}" required class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label mb-10 text-left">{!! label('Kategori', 'Category') !!}</label>
                    <select name="category_id" id="" required class="form-control">
                        <option disabled selected>Pilih satu</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}" @if($maintenance_activity->category_id == $category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label mb-10 text-left">{!! label('periode', 'period') !!}</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="number" max="12" min="1" name="periode_value" value="{{$maintenance_activity->periode_value}}" class="form-control" placeholder="Misal. 1">
                        </div>
                        <span class="col-md-6">
                            <select name="periode_id" id="" required class="form-control">
                                <option disabled selected>Pilih satu</option>
                                @foreach ($periodes as $periode)
                                    <option value="{{$periode->id}}" @if($maintenance_activity->periode_id == $periode->id) selected @endif>{{$periode->name}}</option>
                                @endforeach
                            </select>
                        </span>
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

    function store() {
        var data = $( '#form-item-maintenance-activity' ).serialize();
        $.ajax({
            url: '{{url("master/item/maintenance-activity")}}',
            method: 'POST',
            data: data,
            success: function(res) {
                $("#modal-detail").html(res);
            },
            error: function(res) {
                swal('Opps, something went wrong. Please try again');
            },
        })
    }

</script>