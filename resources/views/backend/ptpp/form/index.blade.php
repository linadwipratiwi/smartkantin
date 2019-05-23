@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'PTPP Pengajuan',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'PTPP Pengajuan'
            ],
        ]
    ])
    
    <!-- /Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <form method="get" action="{{url('ptpp')}}">
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
                        @if(access_is_allowed_to_view('create.ptpp.form'))
                        <div class="dt-buttons">
                            <a class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="example" href="{{url('ptpp/create')}}"><i class="fa fa-plus"></i> <span>Buat baru</span></a>
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
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Kepada Fungsi</th>
                                            <th>Dari</th>
                                            <th>Lokasi/Area</th>
                                            <th>Catatan</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Approval to RSD</th>
                                            <th>Approval to OH</th>
                                            <th>Status Follow Up</th>
                                            <th>Status Verification</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list_ptpp as $row => $ptpp)
                                        <tr id="tr-{{$ptpp->id}}">
                                            <td>{{$ptpp->number}}</td>
                                            <td>{{\App\Helpers\DateHelper::formatView($ptpp->date_created)}}</td>
                                            <td>{{$ptpp->category->name}}</td>
                                            <td>{{$ptpp->to_function}}</td>
                                            <td>{{$ptpp->from}}</td>
                                            <td>{{$ptpp->location}}</td>
                                            <td>{{$ptpp->notes}}</td>
                                            <td>{{$ptpp->createdBy->name}}</td>
                                            <td class="td-status-approval-rsd-{{$ptpp->id}}">{{ user_approval($ptpp->approval_to_spv_rsd) }} {!! status_approval($ptpp->status_approval_to_spv_rsd) !!}</td>
                                            <td class="td-status-approval-oh-{{$ptpp->id}}">{{ user_approval($ptpp->approval_to_oh) }} {!! status_approval($ptpp->status_approval_to_oh) !!}</td>
                                            <td>{!! $ptpp->statusFollowUp() !!}</td>
                                            <td>{!! $ptpp->statusVerification() !!}</td>
                                            <td>
                                                @if($ptpp->status_approval_to_oh == 0 && $ptpp->status_approval_to_spv_rsd == 0)
                                                    @if(access_is_allowed_to_view('update.ptpp.form'))
                                                    <a href="{{url('ptpp/'.$ptpp->id.'/edit')}}" data-toggle="tooltip" data-original-title="Edit">
                                                        <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                                    </a>
                                                    @endif
                                                    @if(access_is_allowed_to_view('delete.ptpp.form'))
                                                    <a onclick="secureDelete('{{url('ptpp/'.$ptpp->id)}}', '#tr-{{$ptpp->id}}')" onclick="document.getElementById('form-delete-{{$ptpp->id}}').submit();"  data-toggle="tooltip" data-original-title="Close">
                                                        <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>                                                    
                                                    </a>
                                                    @endif
                                                @endif
                                                
                                                <a onclick="showDetail('{{url("ptpp/".$ptpp->id."/file")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Detail">
                                                    <button class="btn btn-primary btn-icon-anim btn-square btn-sm"><i class="fa fa-eye"></i></button>
                                                </a>
                                                @if(access_is_allowed_to_view('approval.ptpp.form'))
                                                <a onclick="showDetail('{{url("ptpp/".$ptpp->id."/send-request")}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-toggle="tooltip" data-original-title="Send request approval">
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
                        {{ $list_ptpp->links() }}
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- /Row -->

    @include('backend.include._notes')
@stop