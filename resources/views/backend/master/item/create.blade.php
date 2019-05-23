@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Item',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => url('master'),
                'label' => 'Master Data'
            ],
            2 => [
                'link' => '#',
                'label' => 'Item'
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
                            <form method="post" action="{{url('master/item/')}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'Name') !!}</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('brand', 'brand') !!}</label>
                                    <input type="text" class="form-control" name="brand">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('tipe', 'type') !!}</label>
                                    <input type="text" class="form-control" name="type">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('tahun produksi', 'production year') !!}</label>
                                    <input type="text" class="form-control" name="production_year">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('lokasi pakai', 'location of use') !!}</label>
                                    <input type="text" class="form-control" name="location_of_use">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('catatan', 'notes') !!}</label>
                                    <textarea name="notes" id="" class="form-control" cols="30" rows="5"></textarea>
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