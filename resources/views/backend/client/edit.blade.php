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
                            <form method="post" action="{{url('client/'.$client->id)}}">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="{{$client->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Telepon', 'Phone') !!} </label>
                                    <input type="text" name="phone" class="form-control" id="" value="{{$client->phone}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Perusahaan', 'Company') !!} </label>
                                    <input type="text" name="company" class="form-control" id="" value="{{$client->company}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Alamat', 'Address') !!} </label>
                                    <textarea name="address" class="form-control" id="" cols="30" rows="10">{{$client->address}}</textarea>
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