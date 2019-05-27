
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
				
				<li>
					<a @if(\Request::segment(1) == 'client') class="active" @endif href="{{url('client')}}"><div class="pull-left"><i class="zmdi zmdi-chart-donut mr-20"></i><span class="right-nav-text">Client</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'customer') class="active" @endif href="{{ url('customer') }}"><div class="pull-left"><i class="fa fa-support mr-20"></i><span class="right-nav-text">Customer </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'vending-machine') class="active" @endif href="{{ url('vending-machine') }}"><div class="pull-left"><i class="fa fa-cubes mr-20"></i><span class="right-nav-text">Vending Machine</span></div><div class="clearfix"></div></a>
					{{-- <ul id="menu-vending-machine" class="collapse @if(\Request::segment(1) == 'vending-machine') in @endif  collapse-level-1">
						<li>
							<a href="{{url('vending-machine')}}">Vending Machine</a>
						</li>
						<li>
							<a href="{{url('inventory/history')}}">Stock Opname</a>
						</li>
					</ul> --}}
				</li>
				<li>
					<a @if(\Request::segment(1) == 'transaction') class="active" @endif href="{{ url('transaction') }}"><div class="pull-left"><i class="fa fa-file mr-20"></i><span class="right-nav-text">Transaction </span></div><div class="clearfix"></div></a>
				</li>
				{{-- @endif --}}
				<li><hr class="light-grey-hr mb-10"/></li>
				<li class="navigation-header">
					<span>Master</span>
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'user') class="active" @endif href="{{url('user')}}"><div class="pull-left"><i class="fa fa-user  mr-20"></i><span class="right-nav-text">Pengguna </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'setting') class="active" @endif href="{{url('setting')}}"><div class="pull-left"><i class="fa fa-cog  mr-20"></i><span class="right-nav-text">Pengaturan </span></div><div class="clearfix"></div></a>
				</li>
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
