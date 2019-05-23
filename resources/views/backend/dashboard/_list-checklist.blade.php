<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view">
			<div class="panel-heading">
				<h6 class="panel-title txt-dark">Daftar cheklist butuh approval</h6>
			</div>
			@if($maintenance_activities->count())
			<div class="table-wrap">
				<div class="table-responsive">
					<table id="datatable" class="table table-hover display  pb-30" >
						<thead>
							<tr>
								<th>No</th>
								<th>Item</th>
								<th>Category</th>
								<th>Pengecekan & Periode</th>
								<th>Keterangan</th>
								<th>Tanggal</th>
								<th>Status Approval</th>
								<th>Approval to</th>
								<th>Operator/Enginer</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($maintenance_activities as $row => $maintenance_activity)
							<tr id="tr-{{$maintenance_activity->id}}">
								<td>{{$maintenance_activity->number}}</td>
								<td>{{$maintenance_activity->item->name}}</td>
								<td>{{$maintenance_activity->itemMaintenanceActivity->category->name}}</td>
								<td>{{$maintenance_activity->itemMaintenanceActivity->name}} - <strong> {{$maintenance_activity->itemMaintenanceActivity->periode_value}} {{$maintenance_activity->itemMaintenanceActivity->periode->name}}</strong></td>
								<td>{{$maintenance_activity->notes}} {!! $maintenance_activity->status() !!}</td>
								<td>{{\App\Helpers\DateHelper::formatView($maintenance_activity->date)}}</td>
								<td>{!! $maintenance_activity->statusApproval() !!}</td>
								<td>{{$maintenance_activity->approvalTo->name}}</td>
								<td>{{$maintenance_activity->user->name}}</td>
								<td>

									@if($maintenance_activity->approval_to == auth()->user()->id && $maintenance_activity->status_approval == 1)
									<a onclick="confirm('{{url('checklist/'.$maintenance_activity->id.'/approve')}}')" data-toggle="tooltip" data-original-title="Close">
										<button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
									</a>
									<a onclick="reject('{{url('reject')}}', 'MaintenanceActivity', {{$maintenance_activity->id}}, {{$maintenance_activity->approval_to}}, 'status_approval', 'notes_approval', 'approval_at', '{{url('/')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
										<button class="btn btn-warning btn-icon-anim btn-square  btn-sm"><i class="icon-close"></i></button>                                                    
									</a>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="table-wrap" style="padding-bottom:20px">
				<a href="{{url('checklist?status=1')}}">
					<button class="btn btn-primary btn-icon-anim btn-sm">Tampilkan data lebih banyak ...</button>                                                    
				</a>
			</div>
			@else
			TIDAK ADA CHECKLIST YANG BUTUH PERSETUJUAN ANDA
			@endif
		</div>
	</div>
</div>