@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'User',
        'breadcrumbs' => [
            0 => [
                'link' => url('dashboard'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Create User'
            ],
        ]
    ])
    
    <!-- /Title -->
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="post" action="{{url('front/user')}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Vending Machine</label>
                                    <select name="vending_machine_id" class="form-control" id="">
                                        <option value="">Pilih vending</option>
                                        @foreach ($vending_machines as $vending_machine)
                                            <option value="{{$vending_machine->id}}">{{$vending_machine->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Name</label>
                                    <input type="text" class="form-control" value="" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Username</label>
                                    <input type="text" class="form-control" value="" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Password</label>
                                    <input type="password" class="form-control" value="" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">{!! label('Foto', 'Images') !!}</label>
                                    <input type="file" name="file" id="file">
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">submit</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop