<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
            <p>Pengaturan produk yang dijual untuk setiap warung</p>
            <a class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" onclick="showDetail('{{url("front/stand/".$vending_machine->id."/product/create")}}')"> <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">Tambah produk baru</span></a>
            <a class="btn btn-success btn-sm btn-lable-wrap left-label pull-right mr-5" href="{{url("front/stand/".$vending_machine->id."/stock/export")}}"> <span class="btn-label"><i class="fa fa-cube"></i> </span><span class="btn-text">Stock Opname</span></a> 

        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-center" style="min-width: 50px">#</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Makanan</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Harga Jual</th>
                            </tr>
                            </thead>
                            @foreach($vending_machine->slots as $i => $slot)
                                <tr id="tr-slot-{{$slot->id}}">
                                    <td>
                                        <a  onclick="showDetail('{{url("front/stand/".$vending_machine->id."/product/".$slot->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                            <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                        </a>
                                    </td>
                                    <td>{!!$slot->photo ? '<img width="50px" height="50px" src="'.asset($slot->photo).'">' : '-'!!}</td>
                                    <td>{{$slot->food_name}}</td>
                                    <td>{{$slot->stock}}</td>
                                    <td>{{format_price($slot->selling_price_vending_machine)}}</td>
                                </tr>
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
