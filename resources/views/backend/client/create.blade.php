@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Client',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Client'
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
                            <form method="post" action="{{url('client')}}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!} </label>
                                    <input type="text" class="form-control" value="" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Telepon', 'Phone') !!} </label>
                                    <input type="text" name="phone" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Perusahaan', 'Company') !!} </label>
                                    <input type="text" name="company" class="form-control" id="" value="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Alamat', 'Address') !!} </label>
                                    <textarea name="address" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                                <div class="seprator-block"></div>
                                {{-- Visit Transaction --}}
                                <h6 class="txt-dark capitalize-font"><i class="fa fa-user mr-10"></i>Akun Client</h6>
                                <hr class="light-grey-hr" />
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Username</label>
                                    <input type="text" name="username" class="form-control" id="" value="" placeholder="username">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Password', 'Default: 12345') !!} </label>
                                    <input type="text" name="password" class="form-control" id="" value="12345">
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