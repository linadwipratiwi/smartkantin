@extends('layouts.app')

@section('content')
    <!-- Title -->
    @include('backend._bread-crumb', [
        'title' => 'Firmware',
        'breadcrumbs' => [
            0 => [
                'link' => url('/'),
                'label' => 'dashboard'
            ],
            1 => [
                'link' => '#',
                'label' => 'Firmware'
            ],
        ]
    ])
    
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Vending Machine</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        {{-- <p class="muted">Add class <code class="">.carousel-caption</code>.</p> --}}
                        <!-- START carousel-->
                        <div id="carousel-example-captions" data-ride="carousel" class="carousel slide mt-40">
                            <ol class="carousel-indicators" style="top:-50px;">
                               <li style="border: 1px solid #b19393;" data-target="#carousel-example-captions" data-slide-to="0" class="active"></li>
                               <li style="border: 1px solid #b19393;" data-target="#carousel-example-captions" data-slide-to="1" class=""></li>
                            </ol>
                            <div role="listbox" class="carousel-inner">
                               <div class="item active">
                                  <div class="row">
                                        <div class="col-12">
                                            <p class="text-center">Vending Machine 1</p>
                                        </div>
                                      
                                        <div class="col-md-3 text-center" style="border:1px solid #eee">
                                            A1 <br> Nasi Goreng <br> 3 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A2 <br>Sate <br> 4 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A3 <br> Bubur Kacang <br> 3 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A4 <br> Soto Babat <br> 1 pcs
                                        </div>
                                        <div class="col-md-3 text-center" style="border:1px solid #eee">
                                            A1 <br> Nasi Goreng <br> 3 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A2 <br>Sate <br> 4 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A3 <br> Bubur Kacang <br> 3 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A4 <br> Soto Babat <br> 1 pcs
                                        </div>
                                  </div>
                               </div>
                               <div class="item">
                                    <div class="row">
                                        <div class="col-md-3 text-center" style="border:1px solid #eee">
                                            A1 <br> Nasi Goreng <br> 3 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A2 <br>Sate <br> 4 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A3 <br> Bubur Kacang <br> 3 pcs
                                        </div>
                                        <div class="col-md-3  text-center" style="border:1px solid #eee">
                                            A4 <br> Soto Babat <br> 1 pcs
                                        </div>
                                    </div>
                               </div>
                            </div>
                            <a href="#carousel-example-captions" role="button" data-slide="prev" class="left carousel-control"> <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Previous</span> </a> <a href="#carousel-example-captions" role="button" data-slide="next" class="right carousel-control"> <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span> </a> 
                        </div>
                        <!-- END carousel-->
                    </div>
                </div>
            </div>
       </div>
    </div>
    <!-- /Row -->
@stop

@section('scripts')
<script>
    initDatatable('#datatable');
</script>
@stop