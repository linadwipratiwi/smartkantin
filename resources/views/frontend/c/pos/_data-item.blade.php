<!-- Row -->
<div class="row">
    @foreach ($list_stand as $stand)
        <div id="stand-{{$stand->id}}" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>{{$stand->name}}</h4>
        </div>
        @foreach ($stand->slots as $item)
            <?php
                $temp_key = \App\Helpers\PosHelper::getTempKey();
                $search = \App\Helpers\TempDataHelper::searchKeyValue($temp_key, auth()->user()->id, ['item_id'], [$item->id]);

            ?>
            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
                <div class="panel panel-default card-view pa-5">
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body pa-5">
                            <article class="col-item">
                                <div class="photo">
                                    <a href="javascript:void(0);"> <img src="{{asset($item->food->photo)}}" id="food-photo-{{$item->id}}" class="img-responsive" style="width:100%" alt="Product Image" /> </a>
                                </div>
                                <div class="pt-5">
                                    <div class="product-rating inline-block" style="font-size:12px">
                                        {{$item->vendingMachine ? $item->vendingMachine->name: null}}
                                    </div>
                                    <h6 id="food-name-{{$item->id}}">{{$item->food->name}}</h6>
                                    <span class="head-font block text-warning font-16" id="food-price-{{$item->id}}">Rp. {{format_quantity($item->food->selling_price_vending_machine)}}</span>
                                    <br>
                                    <div id="btn-action-{{$item->id}}">
                                        @if(!$search)
                                            <button class="btn btn-warning btn-sm btn-block" onclick="addToCart({{$item->id}}, 0)">Tambah</button>
                                        @else 
                                        <div class="row text-centers" style="font-weight:bold; ">
                                            <div style="font-weight:bold;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div style="border-radius:0px; font-size:14px; font-weight:bold; color:#f2b700 !important" class="col-xs-4 btn btn-default btn-xs" onclick="addToCart({{$item->id}}, 1)"><i class="fa fa-minus"></i></div>
                                                <div style="border-radius:0px; font-size:14px; font-weight:bold; " class="col-xs-4 btn btn-default btn-xs">{{$search['quantity']}}</div>
                                                <div style="border-radius:0px; font-size:14px; font-weight:bold; color:#f2b700 !important" class="col-xs-4 btn btn-default btn-xs" onclick="addToCart({{$item->id}}, 0)"><i class="fa fa-plus"></i></div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>	
                </div>	
            </div>
        @endforeach

    @endforeach
</div>
<!-- /Row -->