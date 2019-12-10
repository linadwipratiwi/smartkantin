<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
            <p>List mutasi stok vending machine</p>
            <a class="btn btn-info btn-sm btn-lable-wrap left-label pull-right" href="{{url("vending-machine/".$vending_machine->id."/stock/export")}}"> <span class="btn-label"><i class="fa fa-file-text"></i> </span><span class="btn-text">Download Laporan</span></a>
        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                {{-- <th class="text-center" style="min-width: 150px">Aksi</th> --}}
                                <th class="text-center">Nama Makanan</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Slot Vending Machine</th>
                                <th class="text-center">HPP</th>
                                <th class="text-center">Harga Jual Client</th>
                                <th class="text-center">Tanggal Transaksi</th>
                                <th class="text-center">Jenis Transaksi</th>
                            </tr>
                            </thead>
                            @foreach($vending_machine->stocks as $i => $stock)
                                <tr id="tr-stock-{{$stock->id}}">
                                    <td class="">{{++$i}}</td>
                                    {{-- <td>
                                        <a  onclick="showDetail('{{url("vending-machine/".$vending_machine->id."/stock/".$stock->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                            <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                        </a>
                                        <a onclick="secureDelete('{{url('vending-machine/".$vending_machine->id."/stock/'.$stock->id)}}', '#tr-stock-{{$stock->id}}')" data-toggle="tooltip" data-original-title="Delete"> 
                                            <button class="btn btn-info btn-icon-anim btn-square  btn-sm"><i class="icon-trash"></i></button>
                                        </a>
                                    </td> --}}
                                    <td>{{$stock->food_name}}</td>
                                    <td>{{$stock->stock}}</td>
                                    <td>{{$stock->vendingMachineSlot->convertToAsci()}}</td>
                                    <td>{{format_price($stock->hpp)}}</td>
                                    <td>{{format_price($stock->selling_price_client)}}</td>
                                    <td>{{$stock->created_at ? date_format_view($stock->created_at) : '-'}}</td>
                                    <td>{!! $stock->typeTransaction() !!}</td>
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
