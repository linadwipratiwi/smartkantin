<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view">
			<div class="panel-heading">
				<h6 class="panel-title txt-dark">Daftar Reminder Checklist</h6>
			</div>
			@if($reminder_maintenance_activities->count())
			<div class="table-wrap">
				<div class="table-responsive">
					<table id="datatable" class="table table-hover display  pb-30" >
						<thead>
							<tr>
								<th>No</th>
								<th>Item</th>
								<th>Category</th>
								<th>Pengecekan & Periode</th>
								<th>Tanggal Terakhir Checklist</th>
								<th>Tanggal Harus Checklist</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($reminder_maintenance_activities as $row => $maintenance_activity)
							<?php
							$show_reminder = $maintenance_activity->showReminder();
							if (!$show_reminder) continue;
							?>
							<tr id="tr-{{$maintenance_activity->id}}">
								<td>{{$row + 1}}</td>
								<td>{{$maintenance_activity->item->name}}</td>
								<td>{{$maintenance_activity->itemMaintenanceActivity->category->name}}</td>
								<td>{{$maintenance_activity->itemMaintenanceActivity->name}} - <strong> {{$maintenance_activity->itemMaintenanceActivity->periode_value}} {{$maintenance_activity->itemMaintenanceActivity->periode->name}}</strong></td>
								<td>{{\App\Helpers\DateHelper::formatView($maintenance_activity->date)}}</td>
								<td>{{\App\Helpers\DateHelper::formatView($show_reminder['date_must_be_checklist'])}}</td>
								<td>
									<a href="{{url('checklist/create/'.$maintenance_activity->id)}}" data-toggle="tooltip" data-original-title="Edit">
										<button class="btn btn-default btn-icon-anim btn-sm"><i class="fa fa-plus"></i> Buat cheklist sekarang</button>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="table-wrap" style="padding-bottom:20px">
				<a href="{{url('checklist/reminder')}}">
					<button class="btn btn-primary btn-icon-anim btn-sm">Tampilkan data lebih banyak ...</button>                                                    
				</a>
			</div>
			@else
			TIDAK ADA REMINDER UNTUK CHECKLIST
			@endif
		</div>
	</div>
</div>