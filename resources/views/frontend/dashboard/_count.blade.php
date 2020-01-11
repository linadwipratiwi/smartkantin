<div class="row">
    <a href="{{url('client')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-red">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{format_quantity($total_transaction)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block font-13">Transaction Today</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="zmdi zmdi-chart-donut txt-light data-right-rep-icon"></i>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>
    <a href="{{url('customer')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-yellow">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{format_quantity($total_customer)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">Customer</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="fa fa-support txt-light data-right-rep-icon"></i>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>

    <a href="{{url('vending-machine')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-green">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{format_quantity($total_vending_machine)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">Outlet / Vending Machine</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="fa fa-cubes txt-light data-right-rep-icon"></i>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>

    <a href="{{url('user')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-blue">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{format_quantity($total_profit)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">Profit Today</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 pt-25  data-wrap-right">
                                    <i class="fa fa-dollar txt-light data-right-rep-icon"></i>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>
</div>