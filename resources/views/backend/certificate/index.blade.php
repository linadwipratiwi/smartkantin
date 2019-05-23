@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Sertifikat',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Sertifikat'
            ],
        ]
    ])
    
    <!-- /Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <form method="get" action="{{url('certificate')}}">
                        {!! csrf_field() !!}
                        <?php $category_filter = \Input::get('category');?>
                        <?php $status_filter = \Input::get('status');?>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Category</label>
                                <select name="category" placeholder="" class="form-control" id="item-id">
                                    <option value="">Semua</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" @if($category_filter == $category->id ) selected @endif>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Status</label>
                                <select name="status" placeholder="" class="form-control" id="item-id">
                                    <option value="1" @if($status_filter == 1 ) selected @endif>Masih Berlaku</option>
                                    <option value="2" @if($status_filter == 2 ) selected @endif>Telah Berakhir</option>
                                </select>
                            </div>
                        </div>
    
                        <div class="row mt-10">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-sm btn-anim">Filter</button>
                                <?php $param = 'category='.\Input::get('category').'&status='.\Input::get('status');?>
                                <a href="{{url('certificate/report?'.$param)}}" data-toggle="tooltip" data-original-title="Edit">
                                    <button class="btn btn-info btn-anim btn-sm" type="button"> Download Excel</button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        @if(access_is_allowed_to_view('create.certificate'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('certificate/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
                        </div>
                        @endif
                        <h6 class="panel-title txt-dark"></h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover display  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Category</th>
                                            <th>Item</th>
                                            <th>Year</th>
                                            <th>Date Active</th>
                                            <th>Publisher</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($certificates as $row => $certificate)
                                        <tr id="tr-{{$certificate->id}}">
                                            <td>{{$certificate->number}}</td>
                                            <td>{{$certificate->category->name}}</td>
                                            <td>{{$certificate->name}}</td>
                                            <td>{{$certificate->year}}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($certificate->date_start)}} s/d {{\App\Helpers\DateHelper::formatView($certificate->date_end)}}</td>
                                            <td>{{$certificate->publisher}}</td>
                                            <td>{!! $certificate->file() !!}</td>
                                            <td>{!! $certificate->status() !!}</td>
                                            <td>
                                                @if(access_is_allowed_to_view('update.certificate'))
                                                <a href="{{url('certificate/'.$certificate->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                @endif
                                                @if(access_is_allowed_to_view('delete.certificate'))
                                                <a onclick="secureDelete('{{url('certificate/'.$certificate->id)}}', '#tr-{{$certificate->id}}')" onclick="document.getElementById('form-delete-{{$certificate->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
                                                    <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $certificates->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop