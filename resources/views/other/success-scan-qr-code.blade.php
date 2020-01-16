<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Smart Canteen ​</title>
	<meta name="description" content="Selamat datang di Website resmi Smart Canteen" />
	<meta name="keywords" content="Smart Canteen" />
	<meta name="author" content="Pringgo Juni Saputro | odyinggo@gmail.com"/>
	
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="{{asset('dist/icon/apple-icon-57x57.png')}}">
	<link rel="apple-touch-icon" sizes="60x60" href="{{asset('dist/icon/apple-icon-60x60.png')}}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{asset('dist/icon/apple-icon-72x72.png')}}">
	<link rel="apple-touch-icon" sizes="76x76" href="{{asset('dist/icon/apple-icon-76x76.png')}}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{asset('dist/icon/apple-icon-114x114.png')}}">
	<link rel="apple-touch-icon" sizes="120x120" href="{{asset('dist/icon/apple-icon-120x120.png')}}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{asset('dist/icon/apple-icon-144x144.png')}}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{asset('dist/icon/apple-icon-152x152.png')}}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{asset('dist/icon/apple-icon-180x180.png')}}">
	<link rel="icon" type="image/png" sizes="192x192" href="{{asset('dist/icon/android-icon-192x192.png')}}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{asset('dist/icon/favicon-32x32.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{asset('dist/icon/favicon-96x96.png')}}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{asset('dist/icon/favicon-16x16.png')}}">
	<link rel="manifest" href="{{asset('dist/icon/manifest.json')}}">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="{{asset('dist/icon/ms-icon-144x144.png')}}">
	<meta name="theme-color" content="#ffffff">
    
    {{-- Css --}}
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/app.css')}}" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/all.css')}}" />
     @yield('styles')
</head>

<body>
	<!-- Preloader -->
	<div class="preloader-it">
		<div class="la-anim-1"></div>
	</div>
	<!-- /Preloader -->
    <div class="wrapper theme-1-active pimary-color-red">
		<!-- Top Menu Items -->
		
        <!-- Main Content -->
		<div class="">
            <div class="container-fluid pt-25">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
                                <button onclick="backHome()" class="btn btn-primary btn-icon-anim btn-square"><i class="fa  fa-arrow-left"></i></button> 
                            </div>
                            <div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
                                <div class="alert alert-success">
                                    Makanan sudah selesai Anda ambil.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h6 class="panel-title txt-dark"><h6 class="panel-title txt-dark">Daftar Makanan</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body">
                                    <?php $total = 0; ?>
                                    @foreach($list_transaction as $transaction)
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
			</div>
			<!-- Footer -->
			<footer class="footer container-fluid pl-30 pr-30">
				<div class="row">
					<div class="col-sm-12">
						<p>{{date('Y')}} &copy; Smart Canteen</p>
					</div>
				</div>
			</footer>
			<!-- /Footer -->
			
		</div>
        <!-- /Main Content -->

		<!-- Modal Camera -->
		<div class="row">
			<input type="hidden" id="img-target">
			<input type="hidden" id="input-link">
			<div class="modal fade camera-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-lg" id="modal-camera"></div>
			</div>
		</div>

		{{-- Modal From --}}
		<div class="row">
			<div class="modal fade detail-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-lg" style="width:90% !important" id="modal-detail">
		
				</div>
			</div>
		</div>

    </div>
    <!-- /#wrapper -->
	<script>
		
	</script>
	<!-- JavaScript -->
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/all.js') }}"></script>
	
	<script>
        function backHome() {
            location.href="{{url('/')}}";
        }
	</script>
	
</body>

</html>
