<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" >{{$vending_machine->name}}</h4>
            <p>Pengaturan slot untuk setiap vending machine</p>
            <a class="btn btn-success btn-sm btn-lable-wrap left-label pull-right mr-5" href="{{url("front/vending-machine/".$vending_machine->id."/product/stock-opname")}}"> <span class="btn-label"><i class="fa fa-cube"></i> </span><span class="btn-text">Stock Opname Semua produk</span></a> 

        </div>
        <div class="col-lg-12">
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="team">
                        <table class="table color-table info-table">
                            <thead>
                            <tr>
                                <th class="text-center" style="min-width: 50px">#</th>
                                <th class="text-center">Photo</th>
                                <th class="text-center">Slot</th>
                                <th class="text-center">Makanan</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">HPP</th>
                                <th class="text-center">Harga Jual Client</th>
                                <th class="text-center">Harga Jual Di Vending</th>
                            </tr>
                            </thead>
                            @foreach($vending_machine->slots as $i => $slot)
                                <?php 
                                $client = $slot->vendingMachine->client;
                                $profit = $type = $client->profit_platform_type;
                                $profit_vm = 0;
                                if ($type == 'value') {
                                    if ($slot->food) {
                                        $profit_vm = $client->profit_platform_value + $slot->food->selling_price_client;
                                    }
                                } elseif ($type == 'percent') {
                                    if ($slot->food) {
                                        $profit_vm = ($client->profit_platform_percent * $slot->food->selling_price_client) + $slot->food->selling_price_client;
                                    }
                                }


                                ?>
                                <tr id="tr-slot-{{$slot->id}}">
                                    <td>
                                        <a  onclick="showDetail('{{url("front/vending-machine/".$vending_machine->id."/slot/".$slot->id."/edit")}}')"data-toggle="tooltip" data-original-title="Edit">
                                            <button class="btn btn-default btn-icon-anim btn-square btn-sm"><i class="fa fa-pencil"></i></button>
                                        </a>
                                    </td>
                                    <td>{!!$slot->food ? $slot->food->photo ? '<img src="'.url($slot->food->photo).'" width="50px" height="50px">' : '-' : '-'!!}</td>
                                    <td>{{$slot->convertToAsci()}}</td>
                                    <td>{{$slot->food ? $slot->food->name : '-'}}</td>
                                    <td>{{$slot->stock}}</td>
                                    <td>{{$slot->food ? format_price($slot->food->hpp) : '-'}}</td>
                                    <td>{{$slot->food ? format_price($slot->food->selling_price_client) : '-'}}</td>
                                    <td>{{format_price($profit_vm)}}</td>
                                    
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
