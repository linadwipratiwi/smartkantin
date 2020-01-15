<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">

            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="col-sm-8">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Filter what do you want</h6>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="form-wrap">
                            <form class="form-inline" method="get" action="{{url('other/pak-mahfud/export')}}">
                                <?php
                                // $date = date('m-d-Y') .' - '. date('m-d-Y');
                                // $date = explode('-', \Input::get('date'));
                                // $date = $date[0] .' - '. $date[1];
                                ?>
                                <div class="form-group mr-15"
                                    id="date-range">
                                    <label class="control-label mr-10" for="email_inline">Date range</label>
                                    <input class="form-control input-daterange-datepicker" type="text" name="date"
                                        value="{{\Input::get('date')}}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-anim"><i
                                            class="icon-rocket"></i><span class="btn-text">Search</span></button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    initDateRangePicker('.input-daterange-datepicker');

</script>
@endpush