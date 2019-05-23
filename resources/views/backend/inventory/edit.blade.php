@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Inventory',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('inventory'),
                'label' => 'Inventory'
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
                            <form method="post" action="{{url('inventory/'.$item->id)}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'Name') !!}</label>
                                    <input type="text" class="form-control"  value="{{$item->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('brand', 'brand') !!}</label>
                                    <input type="text" class="form-control" value="{{$item->brand}}" name="brand">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('tipe', 'type') !!}</label>
                                    <input type="text" class="form-control" value="{{$item->type}}" name="type">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('tahun produksi', 'production year') !!}</label>
                                    <input type="text" class="form-control" value="{{$item->production_year}}" name="production_year">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('lokasi pakai', 'item of use') !!}</label>
                                    <input type="text" class="form-control" value="{{$item->location_of_use}}" name="location_of_use">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('catatan', 'notes') !!}</label>
                                    <textarea name="notes" id="" class="form-control" cols="30" rows="5">{{$item->notes}}</textarea>
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