
        <div class="fixed-sidebar-left">
			@role('administrator')
			<ul class="nav navbar-nav side-nav nicescroll-bar">
				<li class="navigation-header">
					<span>Main</span> 
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if(\Request::segment(1) == '') class="active" @endif  href="{{url('/')}}"><div class="pull-left"><i class="zmdi zmdi-landscape mr-20"></i><span class="right-nav-text">Beranda</span></div><div class="clearfix"></div></a>
				</li>
				
				@if(access_is_allowed_to_view('read.checklist'))
				<li>
					<a @if(\Request::segment(1) == 'checklist') class="active" @endif href="{{url('checklist')}}"><div class="pull-left"><i class="zmdi zmdi-chart-donut mr-20"></i><span class="right-nav-text">Checklist Activity</span></div><div class="clearfix"></div></a>
				</li>
				@endif
				@if(access_is_allowed_to_view('read.history'))
				<li>
					<a @if(\Request::segment(1) == 'history') class="active" @endif href="{{ url('history') }}"><div class="pull-left"><i class="fa fa-support mr-20"></i><span class="right-nav-text">Riwayat </span></div><div class="clearfix"></div></a>
				</li>
				@endif
				<li>
					<a @if(\Request::segment(1) == 'inventory') class="active" @endif href="javascript:void(0);" data-toggle="collapse" data-target="#menu-inventory"><div class="pull-left"><i class="fa fa-cubes mr-20"></i><span class="right-nav-text">Inventory</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
					<ul id="menu-inventory" class="collapse @if(\Request::segment(1) == 'inventory') in @endif  collapse-level-1">
						@if(access_is_allowed_to_view('read.inventory'))
						<li>
							<a href="{{url('inventory')}}">Inventory</a>
						</li>
						@endif
						@if(access_is_allowed_to_view('read.inventory.history'))
						<li>
							<a href="{{url('inventory/history')}}">History</a>
						</li>
						@endif
					</ul>
				</li>
				@if(access_is_allowed_to_view('read.certificate'))
				<li>
					<a @if(\Request::segment(1) == 'certificate') class="active" @endif href="{{ url('certificate') }}"><div class="pull-left"><i class="fa fa-file mr-20"></i><span class="right-nav-text">Sertifikat </span></div><div class="clearfix"></div></a>
				</li>
				@endif
				{{-- @if(access_is_allowed_to_view('menu.submission')) --}}
				<li>
					<a @if(\Request::segment(1) == 'submission') class="active" @endif href="javascript:void(0);" data-toggle="collapse" data-target="#submission-menu"><div class="pull-left"><i class="fa fa-first-order mr-20"></i><span class="right-nav-text">Pengajuan Barang </span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
					<ul id="submission-menu" class="collapse @if(\Request::segment(1) == 'submission') in @endif  collapse-level-1">
						@if(access_is_allowed_to_view('create.submission'))
						<li>
							<a href="{{url('submission/create')}}">Buat Baru</a>
						</li>
						@endif
						@if(access_is_allowed_to_view('read.submission'))
						<li>
							<a href="{{url('submission')}}">Daftar Pengajuan</a>
						</li>
						@endif
						@if(setting('spv_oh') == auth()->user()->id)
						<li>
							<a href="{{url('submission/pending-approval-oh')}}">Menunggu Persetujuan OH</a>
						</li>
						@endif
						@if(setting('spv_epm') == auth()->user()->id)
						<li>
							<a href="{{url('submission/pending-approval-epm')}}">Menunggu Persetujuan EPM</a>
						</li>
						@endif
					</ul>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'ptpp') class="active" @endif href="javascript:void(0);" data-toggle="collapse" data-target="#ptpp-menu"><div class="pull-left"><i class="fa fa-hashtag mr-20"></i><span class="right-nav-text">PTPP </span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
					<ul id="ptpp-menu" class="collapse @if(\Request::segment(1) == 'ptpp') in @endif  collapse-level-1">
						<li>
							<a href="javascript:void(0);" data-toggle="collapse" data-target="#form-ptpp" class="collapsed" aria-expanded="false">Form Pengajuan<div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
							<ul id="form-ptpp" class="collapse-level-2 collapse" aria-expanded="false" style="height: 0px;">
								@if(access_is_allowed_to_view('create.ptpp.form'))
								<li>
									<a href="{{url('ptpp/create')}}">Pengajuan Baru</a>
								</li>
								@endif
								@if(access_is_allowed_to_view('read.ptpp.form'))
								<li>
									<a href="{{url('ptpp')}}">Data Pengajuan</a>
								</li>
								@endif
								@if(setting('spv_oh') == auth()->user()->id)
								<li>
									<a href="{{url('ptpp/pending-approval-oh')}}">Menunggu Persetujuan OH</a>
								</li>
								@endif
								@if(setting('spv_rsd') == auth()->user()->id)
								<li>
									<a href="{{url('ptpp/pending-approval-rsd')}}">Menunggu Persetujuan RSD</a>
								</li>
								@endif
							</ul>
						</li>

						<li>
							<a href="javascript:void(0);" data-toggle="collapse" data-target="#form-ptpp-follow-up" class="collapsed" aria-expanded="false">Follow Up<div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
							<ul id="form-ptpp-follow-up" class="collapse-level-2 collapse" aria-expanded="false" style="height: 0px;">
								@if(access_is_allowed_to_view('create.ptpp.follow.up'))
								<li>
									<a href="{{url('ptpp/follow-up/create')}}">Buat Baru</a>
								</li>
								@endif
								@if(access_is_allowed_to_view('read.ptpp.follow.up'))
								<li>
									<a href="{{url('ptpp/follow-up')}}">Data Follow Up</a>
								</li>
								@endif 
								@if(setting('spv_epm') == auth()->user()->id)
								<li>
									<a href="{{url('ptpp/follow-up/pending-epm')}}">Menunggu Persetujuan EPM</a>
								</li>
								@endif
							</ul>
						</li>
						<li>
							<a href="javascript:void(0);" data-toggle="collapse" data-target="#form-ptpp-verifikasi" class="collapsed" aria-expanded="false">Verifikasi<div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
							<ul id="form-ptpp-verifikasi" class="collapse-level-2 collapse" aria-expanded="false" style="height: 0px;">
								@if(access_is_allowed_to_view('read.ptpp.verification'))
								<li>
									<a href="{{url('ptpp/verification')}}">PTPP Terverifikasi</a>
								</li>
								@endif
								@if(setting('spv_oh') == auth()->user()->id)
								<li>
									<a href="{{url('ptpp/verification/pending-oh')}}">Menunggu Persetuan OH</a>
								</li>
								@endif
							</ul>
						</li>
					</ul>
				</li>
				{{-- @endif --}}
				<li><hr class="light-grey-hr mb-10"/></li>
				<li class="navigation-header">
					<span>Master</span>
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'master') class="active" @endif href="javascript:void(0);" data-toggle="collapse" data-target="#app_dr"><div class="pull-left"><i class="zmdi zmdi-apps mr-20"></i><span class="right-nav-text">Master Data </span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
					<ul id="app_dr" class="collapse @if(\Request::segment(1) == 'master') in @endif  collapse-level-1">
						@if(access_is_allowed_to_view('read.master.item'))
						<li>
							<a href="{{url('master/item')}}">Item</a>
						</li>
						@endif
						@if(access_is_allowed_to_view('read.master.category'))
						<li>
							<a href="{{url('master/category')}}">Kategori</a>
						</li>
						@endif
						@if(access_is_allowed_to_view('read.master.vendor'))
						<li>
							<a href="{{url('master/vendor')}}">Vendor</a>
						</li>
						@endif
						@if(access_is_allowed_to_view('read.master.periode'))
						<li>
							<a href="{{url('master/periode')}}">Periode</a>
						</li>
						@endif
					</ul>
				</li>
				@if(access_is_allowed_to_view('read.user'))
					<li>
						<a @if(\Request::segment(1) == 'user') class="active" @endif href="{{url('user')}}"><div class="pull-left"><i class="fa fa-user  mr-20"></i><span class="right-nav-text">Pengguna </span></div><div class="clearfix"></div></a>
					</li>
				@endif
				@if(access_is_allowed_to_view('read.setting'))<li>
				<li>
					<a @if(\Request::segment(1) == 'setting') class="active" @endif href="{{url('setting')}}"><div class="pull-left"><i class="fa fa-cog  mr-20"></i><span class="right-nav-text">Pengaturan </span></div><div class="clearfix"></div></a>
				</li>
				@endif
				<li><hr class="light-grey-hr mb-10"/></li>
				<li class="navigation-header">
					<span>Account</span> 
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'profile') class="active" @endif href="{{url('profile')}}"><div class="pull-left"><i class="fa fa-user  mr-20"></i><span class="right-nav-text">Profile </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a href="{{url('/logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="collapse" data-target="#ui_dr"><div class="pull-left"><i class="fa fa-lock mr-20"></i><span class="right-nav-text">Logout</span></div><div class="clearfix"></div></a>
				</li>
				
			</ul>
			@endrole
        </div>
