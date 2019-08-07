<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
            <p>Unggah video untuk iklan</p>
        </div>
        <form id="form-file" enctype="multipart/form-data">
            <div class="col-lg-12" id="form-item">
                {!! csrf_field() !!}
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">Video</label>
                    <input type='file' name="file" id="file" class="form-control" />
                </div>
                @if($vending_machine->video)
                <a href="{{asset($vending_machine->video)}}"><i class="fa fa-link"></i> Unduh video</a>
                @endif
            </div>
            <div class="modal-footer">
                <div class="button-list">
                    <progress class="progress" value="0" max="100"></progress>
                    <button type="submit" class="btn btn-success bt-store pull-right">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.progress {display: none}
</style>

<script>
    initDatetime('.date');
    var status = 0;

    $("#form-file").on('submit', (function(ev) {
        ev.preventDefault();
        store();
    }));

    function store() {
        var form = $('#form-file')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '{{url("front/vending-machine/".$vending_machine->id."/video")}}',
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res, status, xhr) {
                location.href="{{url('front/vending-machine')}}";
            },
            error: function(res, status, xhr) {
                swal('Opps, something went wrong. Please try again');
            },
        });
    }

</script>