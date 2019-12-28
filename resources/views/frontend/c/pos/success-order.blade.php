@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Orderan Anda',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'Pos'
            ],
            1 => [
                'link' => '#',
                'label' => 'Orderan Anda'
            ],
        ]
    ])
    
    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success">
                Silahkan bayar dan ambil pesanan Anda di Warung yang ditempat Anda beli.
            </div>
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><h6 class="panel-title txt-dark">NO# {{$list_transaction->first()->transaction_number}} {!! $list_transaction->first()->isPreorder() !!}</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <?php $total = 0; ?>
                        @foreach($list_transaction as $row => $transaction_group)
                        <?php
                            $list_transaction_by_stand = \App\Models\VendingMachineTransaction::where('transaction_number', $transaction_group->transaction_number)
                                ->where('vending_machine_id', $transaction_group->vending_machine_id)
                                ->get();
                        ?>

                        @foreach($list_transaction_by_stand as $transaction)
                        <?php
                            $t = $transaction->quantity * $transaction->selling_price_vending_machine;
                            $total += $t;
                            $item = $transaction->vendingMachineSlot;

                        ?>
                        <div class="panel panel-default contact-card card-view" style="border:none">
                            <div class="panel-heading" style="color: black">
                                <div class="pull-left">
                                    <div class="pull-left user-img-wrap mr-15">
                                        {!!$item->food->photo ? '<img width="50px" class="card-user-img pull-left" height="50px" src="'.asset($item->food->photo).'">' : '-'!!}
                                    </div>
                                    <div class="pull-left user-detail-wrap">
                                        <span class="block card-user-desn" style="color: black">
                                            {{$item->vendingMachine->name}}
                                        </span>
                                        <span class="block card-user-name" style="color: black">
                                            {{$item->food->name}}
                                        </span>
                                        <span class="block card-user-desn" style="color: black">
                                            Rp. {{format_price($transaction->selling_price_vending_machine)}}
                                        </span>
                                    </div>
                                </div>
                                <div class="pull-right bg-yellow pa-10" style="border-radius:5px">
                                    
                                    <a class="pull-left inline-block" href="#">
                                        {{$transaction->quantity}}
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        @endforeach
                        @if((count($list_transaction) - $row) > 1)
                        <hr style="border-style:dotted; border-color: #000; border-width:1px" />
                        @endif
                        <?php ++$row;?>
                        @endforeach
                    </div>
                    
                </div>
            </div>	
            <div class="panel panel-default card-view" style="border:none">
                <div class="panel-wrapper pb-10" style="color: black">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <b>Total Pembayaran</b>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right" id="total-price">
                            <b>Rp. {{format_price($total)}}</b>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
@stop
