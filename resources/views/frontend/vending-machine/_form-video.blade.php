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
                    <label class="control-label mb-10">Masukkan Url Youtube disini</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-youtube"></i></div>
                        <input type='text' name="file" id="file" placeholder="Contoh. https://www.youtube.com/watch?v=vljXoPISytg" class="form-control" />
                    </div>
                    
                </div>
                @if ($vending_machine->video)
                    <?php
                    // https://www.youtube.com/watch?v=vljXoPISytg
                    $url = explode('=', $vending_machine->video);
                    ?>
                    @if(count($url) > 1)
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{$url[1]}}" frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @endif
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