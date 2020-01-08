
        <div class="fixed-sidebar-left">
			<ul class="nav navbar-nav side-nav nicescroll-bar">
			@role('administrator')
				<li class="navigation-header">
					<span>Main</span> 
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if(\Request::segment(1) == '') class="active" @endif  href="{{url('/')}}"><div class="pull-left"><i class="zmdi zmdi-landscape mr-20"></i><span class="right-nav-text">Dashboard</span></div><div class="clearfix"></div></a>
				</li>
				
				<li>
					<a @if(\Request::segment(1) == 'client') class="active" @endif href="{{url('client')}}"><div class="pull-left"><i class="fa fa-university mr-20"></i><span class="right-nav-text">Client</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'customer') class="active" @endif href="{{ url('customer') }}"><div class="pull-left"><i class="fa fa-users mr-20"></i><span class="right-nav-text">Customer </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'vending-machine') class="active" @endif href="{{ url('vending-machine') }}"><div class="pull-left"><i class="fa fa-building mr-20"></i><span class="right-nav-text">Vending Machine</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'stand') class="active" @endif href="{{ url('stand') }}"><div class="pull-left"><i class="fa fa-shopping-bag mr-20"></i><span class="right-nav-text">Stand</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'transaction') class="active" @endif href="{{ url('transaction') }}"><div class="pull-left"><i class="fa fa-list mr-20"></i><span class="right-nav-text">Transaction </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'gopay-transaction') class="active" @endif href="{{ url('gopay-transaction') }}"><div class="pull-left"><i class="fa fa-file mr-20"></i><span class="right-nav-text">Gopay Transaction </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'withdraw') class="active" @endif href="{{url('withdraw')}}"><div class="pull-left"><i class="fa fa-credit-card mr-20"></i><span class="right-nav-text">Withdraw </span></div><div class="clearfix"></div></a>
				</li>
				{{-- @endif --}}
				<li><hr class="light-grey-hr mb-10"/></li>
				<li class="navigation-header">
					<span>Master</span>
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'firmware') class="active" @endif href="{{url('firmware')}}"><div class="pull-left"><i class="fa fa-tag  mr-20"></i><span class="right-nav-text">Firmware </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'user') class="active" @endif href="{{url('user')}}"><div class="pull-left"><i class="fa fa-user  mr-20"></i><span class="right-nav-text">User </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'setting') class="active" @endif href="{{url('setting')}}"><div class="pull-left"><i class="fa fa-cog  mr-20"></i><span class="right-nav-text">Setting </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(1) == 'kartu-sakti') class="active" @endif href="{{url('kartu-sakti')}}"><div class="pull-left"><i class="fa fa-wpforms mr-20"></i><span class="right-nav-text">Kartu Sakti </span></div><div class="clearfix"></div></a>
				</li>
				
				
			@endrole

			@role('client')
			
				<li class="navigation-header">
					<span>Main</span> 
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if((\Request::segment(2) == '') && (\Request::segment(1) != 'profile')) class="active" @endif  href="{{url('front/')}}"><div class="pull-left"><i class="zmdi zmdi-landscape mr-20"></i><span class="right-nav-text">Dashboard</span></div><div class="clearfix"></div></a>
				</li>

				<li>
					<a @if(\Request::segment(2) == 'topup') class="active" @endif href="{{ url('front/topup') }}"><div class="pull-left"><i class="fa fa-dollar mr-20"></i><span class="right-nav-text">Topup </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'customer') class="active" @endif href="{{ url('front/customer') }}"><div class="pull-left"><i class="fa fa-group mr-20"></i><span class="right-nav-text">Customer </span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'vending-machine') class="active" @endif href="{{ url('front/vending-machine') }}"><div class="pull-left"><i class="fa fa-building mr-20"></i><span class="right-nav-text">Vending Machine</span></div><div class="clearfix"></div></a>
				</li>
				
				<li>
					<a @if(\Request::segment(2) == 'stand') class="active" @endif href="{{ url('front/stand') }}"><div class="pull-left"><i class="fa fa-shopping-bag mr-20"></i><span class="right-nav-text">Stand</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'food') class="active" @endif href="{{ url('front/food') }}"><div class="pull-left"><i class="fa fa-cubes mr-20"></i><span class="right-nav-text">Food</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'multipayment') class="active" @endif href="{{ url('front/multipayment') }}"><div class="pull-left"><i class="fa fa-money mr-20"></i><span class="right-nav-text">Multipayment</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'report') class="active" @endif href="javascript:void(0);" data-toggle="collapse" data-target="#app_report"><div class="pull-left"><i class="fa fa-file mr-20"></i><span class="right-nav-text">Report </span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
					<ul id="app_report" class="collapse @if(\Request::segment(2) == 'report') in @endif  collapse-level-1">
						<li>
							<a href="{{url('front/report/transaction')}}">Transaction</a>
						</li>
						<li>
							<a href="{{url('front/report/topup')}}">Topup</a>
						</li>
					</ul>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'user') class="active" @endif href="{{url('front/user')}}"><div class="pull-left"><i class="fa fa-user  mr-20"></i><span class="right-nav-text">User </span></div><div class="clearfix"></div></a>
				</li>
				
			@endrole
			
			@role('customer')
				<li class="navigation-header">
					<span>Main</span> 
					<i class="zmdi zmdi-more"></i>
				</li>
				<li>
					<a @if((\Request::segment(2) == '') && (\Request::segment(1) != 'profile')) class="active" @endif  href="{{url('front/')}}"><div class="pull-left"><i class="zmdi zmdi-shopping-basket mr-20"></i><span class="right-nav-text">Pos</span></div><div class="clearfix"></div></a>
				</li>
				<li>
						<a @if(\Request::segment(2) == 'history-transaction') class="active" @endif  href="{{url('c/history-transaction')}}"><div class="pull-left"><i class="zmdi zmdi-file mr-20"></i><span class="right-nav-text">Riwayat Transaksi</span></div><div class="clearfix"></div></a>
				</li>
				<li>
					<a @if(\Request::segment(2) == 'topup') class="active" @endif  href="{{url('c/topup')}}"><div class="pull-left"><i class="fa fa-dollar mr-20"></i><span class="right-nav-text">Riwayat Topup</span></div><div class="clearfix"></div></a>
				</li>
			@endrole
				
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
        </div>
