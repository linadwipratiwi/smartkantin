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
    {{-- Filter --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <form method="get" action="{{url('master/category')}}">
                        {!! csrf_field() !!}
                        <?php $type = \Input::get('type');?>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label mb-10 text-left">Type</label>
                                <select name="type" placeholder="" class="form-control" id="item-id">
                                    <option value="" @if($type=='') selected @endif>All</option>
                                    <option value="item" @if($type=='item') selected @endif>Item</option>
                                    <option value="submission" @if($type=='submission') selected @endif>Submission</option>
                                    <option value="certificate" @if($type=='certificate') selected @endif>Certificate</option>
                                    <option value="ptpp" @if($type=='ptpp') selected @endif>PTPP</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-sm btn-anim">Filter</button>
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
                        @if(access_is_allowed_to_view('create.master.category'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('master/category/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
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
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $row => $category)
                                        <tr id="tr-{{$category->id}}">
                                            <td>{{$row + 1}}</td>
                                            <td>{{$category->name}}</td>
                                            <td>{{$category->type}}</td>
                                            <td>
                                                @if(access_is_allowed_to_view('update.master.category'))
                                                <a href="{{url('master/category/'.$category->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                    <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                </a>
                                                @endif
                                                @if(access_is_allowed_to_view('delete.master.category'))
                                                <a onclick="secureDelete('{{url('master/category/'.$category->id)}}', '#tr-{{$category->id}}')" onclick="document.getElementById('form-delete-{{$category->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
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
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->
@stop