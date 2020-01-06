
    <div class="form-wrap">
        <form method="post" action="{{url('profile')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label class="control-label mb-10 text-left">Name </label>
                <input type="text" class="form-control" value="{{$user->name}}" name="name" required>
            </div>
            <div class="form-group">
                <label class="control-label mb-10 text-left">Email</label>
                <input type="email" class="form-control" value="{{$user->email}}" name="email">
            </div>
            <div class="form-group">
                <label class="control-label mb-10 text-left">Username </label>
                <input type="text" class="form-control" value="{{$user->username}}" name="username" required>
            </div>
            <div class="form-group">
                <label class="control-label mb-10 text-left">New Password (optional)</label>
                <input type="password" class="form-control" value="" name="password">
            </div>
            <div class="form-group">
                <label class="control-label mb-10">{!! label('Foto', 'Images') !!}</label>
                <input type="file" name="file" id="file">
            </div>

            @role('client')
            <div class="form-group">
                <label class="control-label mb-10">{!! label('Logo Perusahaan', 'Company Logo') !!}</label>
                <input type="file" name="logo" id="file">
            </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">submit</span></button>
            </div>
        </form>
    </div>