@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Pengajuan Barang',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Pengajuan Barang'
            ],
        ]
    ])
    
    <!-- /Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <form method="get" action="{{url('submission')}}">
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
                        </div>
    
                        <div class="row mt-10">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-sm btn-anim">Filter</button>
                                <?php $param = 'category='.\Input::get('category');?>
                                <a href="{{url('submission/report?'.$param)}}" data-toggle="tooltip" data-original-title="Edit">
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
                        @if(access_is_allowed_to_view('create.submission'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('submission/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
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
                                            <th>Pemohon</th>
                                            <th>Category</th>
                                            <th>Nama Barang</th>
                                            <th>Alasan Pengajuan</th>
                                            <th>Approval OH</th>
                                            <th>Approval Spv. EPM</th>
                                            <th>Tgl. Dibuat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $row => $submission)
                                        <tr id="tr-{{$submission->id}}">
                                            <td>{{$submission->number}}</td>
                                            <td>{{$submission->createdBy->name}}</td>
                                            <td>{{$submission->category->name}}</td>
                                            <td>{{$submission->name}}</td>
                                            <td>{{$submission->notes}}</td>
                                            <td class="td-status-approval-oh-{{$submission->id}}">{{ user_approval($submission->approval_to_oh) }} {!! status_approval($submission->status_approval_to_oh) !!}</td>
                                            <td class="td-status-approval-epm-{{$submission->id}}">{{ user_approval($submission->approval_to_spv_epm) }} {!! status_approval($submission->status_approval_to_spv_epm) !!}</td>
                                            <td>{{App\Helpers\DateHelper::formatView($submission->created_at, true)}}</td>
                                            <td>
                                                <a onclick="showDetail('{{url("submission/".$submission->id."/file")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                                </a>
                                                <a href="{{url('submission/'.$submission->id.'/print')}}" target="_blank" data-toggle="tooltip" data-original-title="Print">
                                                    <button class="btn btn-flickr btn-icon-anim btn-square btn-sm"><i class="fa fa-print"></i></button>
                                                </a>
                                                @if($submission->status_approval_to_oh == 0 && $submission->status_approval_to_spv_epm == 0)
                                                    @if(access_is_allowed_to_view('update.submission'))
                                                    <a href="{{url('submission/'.$submission->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                        <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                    @endif
                                                    @if(access_is_allowed_to_view('delete.submission'))
                                                    <a onclick="secureDelete('{{url('submission/'.$submission->id)}}', '#tr-{{$submission->id}}')" onclick="document.getElementById('form-delete-{{$submission->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
                                                        <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                    </a>
                                                    @endif
                                                @endif

                                                @if(access_is_allowed_to_view('approval.submission'))
                                                <a onclick="showDetail('{{url("submission/".$submission->id."/send-request")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Send request approval">
                                                    <button class="btn btn-warning btn-icon-anim btn-square btn-sm"><i class="fa fa-external-link"></i></button>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->

    @include('backend.include._notes')
@stop