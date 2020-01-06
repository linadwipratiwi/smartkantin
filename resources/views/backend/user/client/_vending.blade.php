<div class="table-wrap">
    <div class="table-responsive">
        <table id="datatable" class="table table-hover display  pb-30">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Vending Machine</th>
                    <th>Client</th>
                    <th>Tahun Produksi</th>
                    <th>Lokasi</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @foreach(client()->vendingMachines as $row => $vending_machine)
                <tr id="tr-{{$vending_machine->id}}">
                    <td>{{$row + 1}}</td>
                    <td>{{$vending_machine->name}}</td>
                    <td>{{$vending_machine->client->name}}</td>
                    <td>{{$vending_machine->production_year}}</td>
                    <td>{{$vending_machine->location}}</td>
                    <td>{{$vending_machine->ip}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>