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
		@include('backend.include.navbar')
		<!-- /Top Menu Items -->
		
		<!-- Left Sidebar Menu -->
		@include('backend.include.menu')
		<!-- /Left Sidebar Menu -->
		

        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid pt-25">
				
				@yield('content')
			</div>
			<div id="app"></div>
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

		<!-- Modal Camera -->
		<div class="row">
			<input type="hidden" id="img-target">
			<input type="hidden" id="input-link">
			<div class="modal fade camera-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
				aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-lg" id="modal-camera"></div>
			</div>
		</div>
    </div>
    <!-- /#wrapper -->
	<script>
		
	</script>
	<!-- JavaScript -->
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/all.js') }}"></script>
	<script src="{{ asset('webcamjs/webcam.min.js') }}"></script>
	
	<script>
	function openCamera(url, img_target, input_link) {
		$('#img-target').val(img_target);
		$('#input-link').val(input_link);
        $.ajax({
            url: url,
            success: function(result){
                $("#modal-camera").html(result);
            }, error: function(result){
                alert("Failed something went wrong");
            }
        });
    }
	</script>
	
	<script>
		/*Sidebar Collapse Animation*/
		var sidebarNavCollapse = $('.fixed-sidebar-left .side-nav  li .collapse');
		var sidebarNavAnchor = '.fixed-sidebar-left .side-nav  li a';
		$(document).on("click",sidebarNavAnchor,function (e) {
			if ($(this).attr('aria-expanded') === "false")
					$(this).blur();
			$(sidebarNavCollapse).not($(this).parent().parent()).collapse('hide');
		});
		
		/*Panel Remove*/
		$(document).on('click', '.close-panel', function (e) {
			var effect = $(this).data('effect');
				$(this).closest('.panel')[effect]();
			return false;	
		});
		
		/*Accordion js*/
			$(document).on('show.bs.collapse', '.panel-collapse', function (e) {
			$(this).siblings('.panel-heading').addClass('activestate');
		});
		
		$(document).on('hide.bs.collapse', '.panel-collapse', function (e) {
			$(this).siblings('.panel-heading').removeClass('activestate');
		});
		
		/*Sidebar Navigation*/
		$(document).on('click', '#toggle_nav_btn,#open_right_sidebar,#setting_panel_btn', function (e) {
			$(".dropdown.open > .dropdown-toggle").dropdown("toggle");
			return false;
		});
		$(document).on('click', '#toggle_nav_btn', function (e) {
			$wrapper.removeClass('open-right-sidebar open-setting-panel').toggleClass('slide-nav-toggle');
			return false;
		});
	</script>
	<script>
		@for($i=0;$i<=10;$i++)
			@if(session()->has('toaster_message_'.$i))
                $.toast({
                    heading: '{{ session()->get("toaster_title_".$i) }}',
                    text: '{{ session()->get("toaster_message_".$i) }}',
                    position: 'top-right',
                    loaderBg:'#f2b701',
                    icon: '{{ session()->get("toaster_icon_".$i) }}',
                    hideAfter: 3000, 
                    stack: 6
                });
                <?php  session()->forget('toaster_message_'.$i); ?>
            @endif
        @endfor
		
		function notification(title, message) {
			$.toast({
				heading: title,
				text: message,
				position: 'top-right',
				loaderBg:'#ff6849',
				icon: 'info',
				hideAfter: 3000, 
				stack: 6
			});
		}
    </script>
	
	@yield('scripts')
	@stack('scripts')
</body>

</html>
