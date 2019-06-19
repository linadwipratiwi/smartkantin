@foreach ($list_client as $client)
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="row ma-0">
                        <div class="col-xs-4 pa-0" style="background:#eee !important">
                            <div class="left-block-wrap pa-30">
                                <p class="block">Profit share {!! $client->profitShare() !!} / transaction</p>
                                <span class="block">{{$client->vendingMachines->count()}} pcs vending machine</span>
                                <div class="left-block  mt-15"><span class="block temprature ">{{$client->name}}</span></div>
                            </div>
                        </div>
                        <div class="col-xs-8 pa-0">
                            <div class="right-block-wrap pa-30 text-left">
                                <p class="block">Share Income</p>
                                <span style="font-size:54px" class="text-success">
                                    Rp. {{format_price($client->shareIncome())}}
                                </span>
                                <p class="block">{{format_quantity($client->totalTransaction(1))}} success of {{format_quantity($client->totalTransaction(3))}} transaction</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
