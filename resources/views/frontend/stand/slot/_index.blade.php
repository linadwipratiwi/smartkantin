<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
            <p>Pengaturan produk yang dijual untuk setiap warung</p>
            <a class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" onclick="showDetail('{{url("front/stand/".$vending_machine->id."/product/create")}}')"> <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">Tambah produk baru</span></a>
            <a class="btn btn-success btn-sm btn-lable-wrap left-label pull-right mr-5" href="{{url("front/stand/".$vending_machine->id."/product/stock-opname")}}"> <span class="btn-label"><i class="fa fa-cube"></i> </span><span class="btn-text">Stock Opname Semua produk</span></a> 

        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-left" style="min-width: 50px">#</th>
                                <th class="text-left">Foto</th>
                                <th class="text-left">Makanan</th>
                                <th class="text-left">Jenis</th>
                                <th class="text-right">Stok</th>
                                <th class="text-right">Harga Jual</th>
                            </tr>
                            </thead>
                            @foreach($vending_machine->slots as $i => $slot)
                                <tr id="tr-slot-{{$slot->id}}">
                                    <td>
                                        <a onclick="secureDelete('{{url('front/stand/".$vending_machine->id."/product/'.$slot->id)}}', '#tr-slot-{{$slot->id}}')"
                                            data-toggle="tooltip" data-original-title="Delete">
                                            <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>
                                        </a>
                                        <a  onclick="showDetail('{{url("front/stand/".$vending_machine->id."/product/".$slot->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                            <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                        </a>
                                    </td>
                                    <td>{!!$slot->photo ? '<img width="50px" height="50px" src="'.asset($slot->photo).'">' : '-'!!}</td>
                                    <td>{{$slot->food_name}}</td>
                                    <td>{{$slot->category ? $slot->category->name : '-' }}</td>
                                    <td class="text-right">{{$slot->stock}}</td>
                                    <td class="text-right">{{format_price($slot->selling_price_vending_machine)}}</td>
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
