<div class="table-wrap">
    <div class="table-responsive">
        <table id="datatable" class="table table-hover display  pb-30">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>HPP</th>
                    <th>Harga Jual</th>
                    <th>Jenis Keuntungan Platform</th>
                    <th>Keuntungan Platform / transaksi</th>
                    <th>Harga Jual VM</th>
                    <th>Gambar</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Food::where('client_id', client()->id)->get() as $row => $food)
                <tr id="tr-{{$food->id}}">
                    <td>{{$row + 1}}</td>
                    <td>{{$food->name}}</td>
                    <td>{{$food->category->name}}</td>
                    <td>{{format_quantity($food->hpp)}}</td>
                    <td>{{format_quantity($food->selling_price_client)}}</td>
                    <td>{{$food->profit_platform_type}}</td>
                    <td>{{$food->profit_platform_type == 'value' ? format_quantity($food->profit_platform_value): $food->profit_platform_percent}} </td>
                    <td>{{format_quantity($food->selling_price_vending_machine)}}</td>
                    <td>{!! $food->photo ? '<img src="'.asset($food->photo).'" widht="50px" height="50px" />' : '-' !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>