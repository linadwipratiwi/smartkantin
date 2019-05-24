<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
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
		
		<link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/app.css')}}" />
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/all.css')}}" />
	</head>
	<body>
		<!--Preloader-->
		<div class="preloader-it">
			<div class="la-anim-1"></div>
		</div>
		<!--/Preloader-->
		
		<div class="wrapper pa-0">
			<header class="sp-header">
				<div class="sp-logo-wrap pull-left">
					<a href="index.html">
						<img class="brand-img mr-10" style="height:250px; width: auto" src="{{asset('dist/img/vending.png')}}" alt="brand"/>
						<span class="brand-text"></span>
					</a>
				</div>
				
				<div class="clearfix"></div>
			</header>
			
			<!-- Main Content -->
			<div class="page-wrapper pa-0 ma-0 auth-page">
				<div class="container-fluid">
					<!-- Row -->
					<div class="table-struct full-width full-height">
						<div class="table-cell vertical-align-middle auth-form-wrap">
							<div class="auth-form  ml-auto mr-auto no-float">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<div class="mb-30">
											<h3 class="text-center txt-dark mb-10">Sign in to <br> <strong>SMART CANTEEN</strong></h3>
										</div>	
										<div class="form-wrap">
                                            <form role="form" method="POST" action="{{ url('/login') }}">
                                                {{ csrf_field() }}
												<div class="form-group">
													<label class="control-label mb-10" for="username">Username</label>
													<input type="text" class="form-control" name="username" required value="{{old('username')}}" id="username">
												</div>
												<div class="form-group">
													<label class="pull-left control-label mb-10" for="password">Password</label>
													<div class="clearfix"></div>
													<input type="password" class="form-control" name="password" required="" id="password" placeholder="Enter pwd">
												</div>
												
												<div class="form-group">
													<div class="checkbox checkbox-primary pr-10 pull-left">
														<input id="remember" name="remember" type="checkbox">
														<label for="remember"> Keep me logged in</label>
													</div>
													<div class="clearfix"></div>
												</div>
												<div class="form-group text-center">
													<button type="submit" class="btn btn-info btn-rounded">sign in</button>
												</div>
											</form>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<!-- /Row -->	
				</div>
				
			</div>
			<!-- /Main Content -->
		
		</div>
		<!-- /#wrapper -->
		
		<!-- JavaScript -->
        <script src="{{ asset('js/app.js') }}"></script>
		<script src="{{ asset('js/all.js') }}"></script>
		<script>
		$('#username').select2();
		</script>
	</body>
</html>
