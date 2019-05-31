<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="col-sm-4">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Filter what do you want</h6>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="form-wrap">
                            <form class="form-inline" method="get" action="http://localhost/client/pjb/report/gate">
                                <div class="form-group mr-15">
                                    <label class="control-label mr-10" for="date">Date</label>
                                    <select name="date" class="form-control" id="date" onchange="selectDate(this.value)">
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="this-month">This Month</option>
                                        <option value="three-month">3 Month</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                <div class="form-group mr-15 hidden" id="date-range">
                                    <label class="control-label mr-10" for="email_inline">Date range</label>
                                    <input class="form-control input-daterange-datepicker" type="text" name="date" value="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">filter</span></button>
                                </div>
                                {{-- <a href="http://localhost/client/pjb/report/gate/download?date=&amp;pos=">
                                    <button type="button" class="btn btn-info btn-anim"><i class="fa fa-file"></i><span class="btn-text">download pdf</span></button>
                                </a> --}}
                            </form>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Total Income</h6>
                        </div>
                        <div class="clearfix"></div>
                        <span style="font-size:54px" class="text-success">
                            Rp. 15,000,000
                        </span>
                    </div>

                    <div class="col-sm-3">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Total Transaction</h6>
                        </div>
                        <div class="clearfix"></div>
                        <span style="font-size:54px" class="text-info">
                            <i class="fa fa-repeat"></i>
                            2,500
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
    initDateRangePicker('.input-daterange-datepicker');

    function selectDate(value) {
        if (value == 'custom') {
            $('#date-range').removeClass('hidden');
        } else {
            $('#date-range').addClass('hidden');
        }
    }
    </script>
@endsection