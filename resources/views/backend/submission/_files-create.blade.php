
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$submission->name}}</h4>
        </div>
        <form id="form-file" enctype="multipart/form-data">
            <div class="col-lg-12" id="form-item">
                {!! csrf_field() !!}
                <input type="hidden" id="submission_id" name="submission_id" value="{{$submission->id}}">
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">{!! label('nama file pendukung', 'name') !!}</label>
                    <input type='text' name="file_name" id="name" required class="form-control" />
                </div>
                <div class="form-group mt-20 ">
                    <label class="control-label mb-10">File</label>
                    <input type='file' name="file" id="file" required class="form-control" />
                </div>
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

    $("#form-file").on('submit', (function(ev) {
        ev.preventDefault();

        $.ajax({
            xhr: function() {
                var progress = $('.progress'),
                    xhr = $.ajaxSettings.xhr();
                progress.show();
                xhr.upload.onprogress = function(ev) {
                    if (ev.lengthComputable) {
                        var percentComplete = parseInt((ev.loaded / ev.total) * 100);
                        progress.val(percentComplete);
                        if (percentComplete === 100) {
                            progress.hide().val(0);
                        }
                    }
                };
                return xhr;
            },
            url: '{{url("submission/file")}}',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(res, status, xhr) {
                $("#modal-detail").html(res);
            },
            error: function(res, status, xhr) {
                swal('Opps, something went wrong. Please try again');
            },
        });
    }));

</script>