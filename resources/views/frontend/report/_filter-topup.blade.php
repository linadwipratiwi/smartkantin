<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Filter what do you want</h6>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="form-wrap">
                            <form class="form-inline" method="get" action="{{url('front/report/topup')}}">
                                <div class="form-group mr-15">
                                    <label class="control-label mr-10" for="date">Date</label>
                                    <select name="type" class="form-control" id="type" onchange="selectDate(this.value)">
                                        <option @if(\Input::get('type') == 'today') selected @endif value="today">Today</option>
                                        <option @if(\Input::get('type') == 'yesterday') selected @endif value="yesterday">Yesterday</option>
                                        <option @if(\Input::get('type') == 'month') selected @endif value="month">This Month</option>
                                        <option @if(\Input::get('type') == 'select-month') selected @endif value="select-month">Select Month</option>
                                        <option @if(\Input::get('type') == 'custom') selected @endif value="custom">Custom</option>
                                    </select>
                                </div>
                                <?php
                                $date = date('m-d-Y') .' - '. date('m-d-Y');
                                if (\Input::get('type') == 'custom') {
                                    $date = explode('-', \Input::get('date'));
                                    $date = $date[0] .' - '. $date[1];
                                }
                                ?>
                                <div class="form-group mr-15 @if(\Input::get('type') == 'custom') @else hidden @endif" id="date-range">
                                    <label class="control-label mr-10" for="email_inline">Date range</label>
                                    <input class="form-control input-daterange-datepicker" type="text" name="date" value="{{$date}}">
                                </div>
                                <div class="form-group mr-15 @if(\Input::get('type') == 'select-month') @else hidden @endif" id="select-month">
                                    <label class="control-label mr-10" for="email_inline">Select Month</label>
                                    <select name="month" id="" class="form-control">
                                        @foreach (App\Helpers\DateHelper::getAllMonths() as $index => $month)
                                            <option value="{{$index}}" @if(\Input::get('month') == $index) selected @endif>{{$month}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">filter</span></button>
                                </div>
                                <?php $url = \Request::fullUrl();
                                $explode = explode('?', $url);
                                $url_export = $url.'/export';
                                if (count($explode) > 1) {
                                    $url_export =$explode[0].'/export?'.$explode[1];
                                }
                                ?>
                                <a href="{{$url_export}}">
                                    <button type="button" class="btn btn-info btn-anim"><i class="fa fa-file"></i><span class="btn-text">download excel</span></button>
                                </a>
                            </form>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Total Topup</h6>
                        </div>
                        <div class="clearfix"></div>
                        <span style="font-size:24px" class="text-success">
                            Rp. {{format_price($total_topup)}}
                        </span>
                    </div>

                    <div class="col-sm-3">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Total Transaction</h6>
                        </div>
                        <div class="clearfix"></div>
                        <span style="font-size:24px" class="text-info">
                            <i class="fa fa-repeat"></i>
                            {{format_quantity($list_topup->count())}}
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
            $('#select-month').addClass('hidden');
        } else if (value == 'select-month') {
            $('#select-month').removeClass('hidden');
            $('#date-range').addClass('hidden');
        } else {
            $('#date-range').addClass('hidden');
            $('#select-month').addClass('hidden');
        }
    }
    </script>
@endsection