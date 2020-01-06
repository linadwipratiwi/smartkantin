<div class="table-wrap">
    <div class="table-responsive">
        <table id="datatable" class="table table-hover display  pb-30">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Warung</th>
                </tr>
            </thead>
            <tbody>
                @foreach(client()->stands as $row => $vending_machine)
                <tr id="tr-{{$vending_machine->id}}">
                    <td>{{$row + 1}}</td>
                    <td>{{$vending_machine->name}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>