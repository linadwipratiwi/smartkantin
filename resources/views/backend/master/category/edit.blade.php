@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Kategori',
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
                'label' => 'Kategori'
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
                            <form method="post" action="{{url('master/category/'.$category->id)}}">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="{{$category->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Tipe', 'Type') !!} </label>
                                    <select name="type" class="form-control" id="">
                                        <option value="item" @if($category->type=='item') selected @endif>Item</option>
                                        <option value="submission" @if($category->type=='submission') selected @endif>submission</option>
                                        <option value="certificate" @if($category->type=='certificate') selected @endif>Certificate</option>
                                        <option value="ptpp"  @if($category->type=='ptpp') selected @endif>PTPP</option>
                                    </select>
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