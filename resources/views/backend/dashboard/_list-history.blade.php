<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view">
			<div class="panel-heading">
				<h6 class="panel-title txt-dark">Daftar history butuh approval</h6>
			</div>
			@if($maintenance_activity_histories->count())
			<div class="table-wrap">
				<div class="table-responsive">
					<table id="datatable" class="table table-hover display  pb-30" >
						<thead>
							<tr>
								<th>No</th>
								<th>Checklist Reference</th>
								<th>Nama Item</th>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th>Pelaksana</th>
								<th>Dibuat oleh</th>
								<th>Status Approval</th>
								<th>Approval to</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($maintenance_activity_histories as $row => $maintenance_activity_history)
							<tr id="tr-{{$maintenance_activity_history->id}}">
								<td>{{$maintenance_activity_history->number}}</td>
								<td>{{$maintenance_activity_history->maintenanceActivity ? $maintenance_activity_history->maintenanceActivity->linkToRef() : '-' }} </td>
								<td>{{$maintenance_activity_history->item->name}}</td>
								<td>{{\App\Helpers\DateHelper::formatView($maintenance_activity_history->date)}}</td>
								<td>{{$maintenance_activity_history->notes}}</td>
								<td>{!! $maintenance_activity_history->executor() !!} </td>
								<td>{{ $maintenance_activity_history->user->name }} </td>
								<td>{!! $maintenance_activity_history->statusApproval() !!}</td>
								<td>{{$maintenance_activity_history->approvalTo->name}}</td>
								<td>
									@if($maintenance_activity_history->approval_to == auth()->user()->id && $maintenance_activity_history->status_approval == 1)
									<a onclick="confirm('{{url('history/'.$maintenance_activity_history->id.'/approve')}}')"  data-toggle="tooltip" data-original-title="Close">
										<button class="btn btn-success btn-icon-anim btn-square  btn-sm"><i class="icon-check"></i></button>                                                    
									</a>

									<a onclick="reject('{{url('reject')}}', 'MaintenanceActivityHistory', {{$maintenance_activity_history->id}}, {{$maintenance_activity_history->approval_to}}, 'status_approval', 'notes_approval', 'approval_at', '{{url('/')}}')" data-toggle="modal" data-target=".detail-modal" data-toggle="tooltip" data-original-title="Close">
										<button class="btn btn-danger btn-icon-anim btn-square  btn-sm"><i class="icon-close"></i></button>                                                    
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
				<a href="{{url('history?status=1')}}">
					<button class="btn btn-primary btn-icon-anim btn-sm">Tampilkan data lebih banyak ...</button>                                                    
				</a>
			</div>
			@else
			TIDAK ADA HISTORY YANG BUTUH PERSETUJUAN ANDA
			@endif
		</div>
	</div>
</div>