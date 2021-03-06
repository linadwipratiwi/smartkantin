@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('frontend._bread-crumb', [
        'title' => 'Food',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Food'
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
                            <form method="post" action="{{url('front/food/'.$food->id)}}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PUT">
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('nama', 'name') !!}</label>
                                    <input type="text" class="form-control" value="{{$food->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Jenis makanan', 'Type') !!} </label>
                                    <select name="category_id" class="form-control" id="">
                                        @foreach ($categories as $item)
                                            <option value="{{$item->id}}" @if ($item->id == $food->category_id)
                                                selected
                                            @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('HPP', 'harga pokok penjualan') !!} </label>
                                    <input type="text" name="hpp" class="form-control format-price" required id="" value="{{$food->hpp}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">Harga Jual </label>
                                    <input type="text" name="selling_price_client" required class="form-control format-price" id="" value="{{$food->selling_price_client}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 text-left">{!! label('Gambar', 'Photo') !!} </label>
                                    <input type="file" name="file" class="form-control" id="" value="">
                                    {!! $food->photo ? '<img src="'.asset($food->photo).'" widht="50px" height="50px" />' : '-' !!}
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


@section('scripts')
    <script>
    initFormatNumber();
    </script>
@endsection