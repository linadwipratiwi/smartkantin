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
                'label' => 'Update User'
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
                            <form method="post" action="{{url('front/user/'.$user_vending->id)}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Vending Machine</label>
                                    <select name="vending_machine_id" class="form-control" id="">
                                        <option value="">Pilih vending</option>
                                        @foreach ($vending_machines as $vending_machine)
                                            <option value="{{$vending_machine->id}}" @if($vending_machine->id == $user_vending->vending_machine_id) selected @endif>{{$vending_machine->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Name </label>
                                    <input type="text" class="form-control" value="{{$user_vending->user->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Email</label>
                                    <input type="email" class="form-control" value="{{$user_vending->user->email}}" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Username</label>
                                    <input type="text" class="form-control" value="{{$user_vending->user->username}}" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">New Password (optional)</label>
                                    <input type="password" class="form-control" value="" name="password">
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
