<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$item->name}}</h4>
            <button class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" onclick="showDetail('{{url("master/item/maintenance-activity/create/".$item->id)}}')"> <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">Buat baru</span></button>
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Perawatan</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center" style="min-width: 200px">Aksi</th>
                            </tr>
                            </thead>
                            @foreach($categories as $category)
                                <tr>
                                    <td colspan="4"><strong>{{$category->category->name}}</strong></td>
                                </tr>
                                <?php 
                                    $item_maintenance_activities = \App\Models\ItemMaintenanceActivity::where('category_id', $category->category->id)->where('item_id', $item->id)->get();
                                    $i=1;
                                ?>
                                @foreach ($item_maintenance_activities as $item_maintenance_activity)
                                    <tr id="tr-item-{{$item_maintenance_activity->id}}">
                                        <td class="">{{$i++}}</td>
                                        <td>{{$item_maintenance_activity->name}}</td>
                                        <td>{{$item_maintenance_activity->periode_value}} {{$item_maintenance_activity->periode->name}}</td>
                                        <td>
                                            <a  onclick="showDetail('{{url("master/item/maintenance-activity/edit/".$item_maintenance_activity->id)}}')"data-toggle="tooltip" data-original-title="Edit">
                                                <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                            </a>
                                            <a onclick="secureDelete('{{url('master/item/maintenance-activity/'.$item_maintenance_activity->id)}}', '#tr-item-{{$item_maintenance_activity->id}}')" data-toggle="tooltip" data-original-title="Delete"> 
                                                <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach 
                            @endforeach
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
