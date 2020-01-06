<div class="row">
    <div class="col-lg-3 col-xs-12">
        <div class="panel panel-default card-view  pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body  pa-0">
                    <div class="profile-box">
                        <div class="profile-cover-pic">
                            {{-- <div class="fileupload btn btn-default">
                                <span class="btn-text">edit</span>
                                <input class="upload" type="file">
                            </div> --}}
                            <div class="profile-image-overlay">
                                @if(asset(client()->logo))
                                <img src="{{asset(client()->logo)}}" width="100%" height="200px" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="profile-info text-center">
                            <div class="profile-img-wrap">
                                <img class="inline-block mb-10"
                                    src="{{auth()->user()->photo ? asset(auth()->user()->photo) : 'dist/img/user1.png'}}"
                                    alt="user">

                            </div>
                            <h5 class="block mt-10 mb-5 weight-500 capitalize-font txt-danger">{{auth()->user()->name}}
                            </h5>
                            <h6 class="block capitalize-font pb-20">{{auth()->user()->roleUser->role->name}}</h6>
                        </div>
                        <div class="social-info">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <span class="counts block head-font txt-primary"><span class="counter-anim">{{format_quantity(\App\Models\VendingMachineTransaction::where('client_id', client()->id)->successNotDelivered()->count())}}</span></span>
                                    <span class="counts-text block">success not delivered</span>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <span class="counts block head-font txt-success"><span class="counter-anim">{{format_quantity(\App\Models\VendingMachineTransaction::where('client_id', client()->id)->success()->count())}}</span></span>
                                    <span class="counts-text block">success</span>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <span class="counts block head-font txt-warning"><span class="counter-anim">{{format_quantity(\App\Models\VendingMachineTransaction::where('client_id', client()->id)->pending()->count())}}</span></span>
                                    <span class="counts-text block">pending</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <span class="counts block head-font txt-info"><span class="counter-anim">{{format_quantity(\App\Models\VendingMachineTransaction::where('client_id', client()->id)->count())}}</span></span>
                                    <span class="counts-text block">total</span>
                                </div>
                            </div>
                            <a href="{{url('front/report/transaction')}}" class="btn btn-default btn-block btn-outline mt-30"><span class="btn-text">Transaction Report</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pb-0">
                    <div class="tab-struct custom-tab-1">
                        <ul role="tablist" class="nav nav-tabs nav-tabs-responsive" id="myTabs_8">
                            <li class="active" role="presentation"><a data-toggle="tab" id="profile_tab_8" role="tab" href="#profile_8" aria-expanded="true"><span>update profile</span></a></li>
                            <li role="presentation" class="next"><a aria-expanded="false" data-toggle="tab" role="tab" id="follo_tab_8" href="#follo_8"><span>vending machine <span class="inline-block">({{client()->vendingMachines->count()}})</span></span></a></li>
                            <li role="presentation" class=""><a data-toggle="tab" id="photos_tab_8" role="tab"
                                    href="#photos_8" aria-expanded="false"><span>stand <span class="inline-block">({{client()->stands->count()}})</span></span></a></li>
                            <li role="presentation" class=""><a data-toggle="tab" id="earning_tab_8" role="tab"
                                    href="#earnings_8" aria-expanded="false"><span>master food</span></a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent_8">
                            <div id="profile_8" class="tab-pane fade active in" role="tabpanel">
                                {{-- <div class="row"> --}}
                                    <div class="col-lg-12">
                                        @include('backend.user._profile')
                                    </div>
                                {{-- </div> --}}
                            </div>

                            <div id="follo_8" class="tab-pane fade" role="tabpanel">
                                <div class="col-lg-12">
                                    @include('backend.user.client._vending')
                                </div>
                            </div>
                            <div id="photos_8" class="tab-pane fade" role="tabpanel">
                                <div class="col-lg-12">
                                    @include('backend.user.client._stand')
                                </div>
                            </div>
                            <div id="earnings_8" class="tab-pane fade" role="tabpanel">
                                <div class="col-lg-12">
                                    @include('backend.user.client._food')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>