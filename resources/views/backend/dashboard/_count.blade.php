<div class="row">
    <a href="{{url('checklist')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-red">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_checklist, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block font-13">Checklist</span>
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
    <a href="{{url('history')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-yellow">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_history, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">History</span>
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

    <a href="{{url('submission')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-green">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_submission, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">Pengajuan Alat / Submission</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="fa fa-first-order txt-light data-right-rep-icon"></i>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>

    <a href="{{url('ptpp')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-blue">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_ptpp, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">PTPP</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 pt-25  data-wrap-right">
                                    <i class="fa fa-hashtag txt-light data-right-rep-icon"></i>
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

<style>
.bg-blue-soft { background: #1f6080 !important}
.bg-purple { background: #801f7d !important}
.bg-pink { background: #c523c7 !important}
.bg-magenta { background: #b10058 !important}
</style>
{{-- Vendor, Item, etc --}}
<div class="row">
    <a href="{{url('master/vendor')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-blue-soft">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_vendor, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block font-13">Vendor</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="fa fa-flag-checkered txt-light data-right-rep-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>

    
    <a href="{{url('master/item')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-purple">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_item, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">Item</span>
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

    <a href="{{url('certificate')}}">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-pink">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_certificate, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">Certificate</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="fa fa-certificate txt-light data-right-rep-icon"></i>
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
                    <div class="sm-data-box bg-magenta">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                    <span class="txt-light block counter"><span class="counter-anim">{{App\Helpers\NumberHelper::formatQuantity($total_user, 0)}}</span></span>
                                    <span class="weight-500 uppercase-font txt-light block">User</span>
                                </div>
                                <div class="col-xs-6 text-center  pl-0 pr-0 pt-25  data-wrap-right">
                                    <i class="fa fa-user txt-light data-right-rep-icon"></i>
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