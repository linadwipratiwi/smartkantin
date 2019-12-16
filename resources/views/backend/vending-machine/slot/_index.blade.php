<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
            <p>Pengaturan slot untuk setiap vending machine</p>
            <button class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" onclick="showDetail('{{url("vending-machine/".$vending_machine->id."/slot/create")}}')"> <span class="btn-label"><i class="fa fa-plus"></i> </span><span class="btn-text">Buat baru</span></button>
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" style="min-width: 150px">Aksi</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Alias</th>
                                <th class="text-center">Makanan</th>
                                <th class="text-center">HPP</th>
                                <th class="text-center">Harga Jual Client</th>
                                <th class="text-center">Harga Jual (asli)</th>
                                <th class="text-center">Profit Client</th>
                                <th class="text-center">Profit Platform</th>
                                <th class="text-center">Kapasitas</th>
                                <th class="text-center">Tgl. Expired</th>
                            </tr>
                            </thead>
                            @foreach($vending_machine->slots as $i => $slot)
                                <tr id="tr-slot-{{$slot->id}}">
                                    <td class="">{{++$i}}</td>
                                    <td>
                                        <a  onclick="showDetail('{{url("vending-machine/".$vending_machine->id."/slot/".$slot->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                            <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                        </a>
                                        <a onclick="secureDelete('{{url('vending-machine/".$vending_machine->id."/slot/'.$slot->id)}}', '#tr-slot-{{$slot->id}}')" data-toggle="tooltip" data-original-title="Delete"> 
                                            <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>
                                        </a>
                                    </td>
                                    <td>{{$slot->convertToAsci()}}</td>
                                    <td>{{$slot->stock}}</td>
                                    <td>{{$slot->alias}}</td>
                                    <td>{{$slot->food_name}}</td>
                                    <td>{{format_price($slot->hpp)}}</td>
                                    <td>{{format_price($slot->selling_price_client)}}</td>
                                    <td>{{format_price($slot->selling_price_vending_machine)}}</td>
                                    <td>{{format_price($slot->profit_client)}}</td>
                                    <td>{!! $slot->profitPlatform() !!}</td>
                                    <td>{{$slot->capacity}}</td>
                                    <td>{{$slot->expired_date}}</td>
                                    
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
