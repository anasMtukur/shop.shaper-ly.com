<?php 
use SRC\DB\Entities\Dashboard;
$counters = (new Dashboard())->getDashboardCounters();
?>
<div class="row">
    <div class="col-md-12 grid-margin transparent">
        <div class="row">
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Products</p>
                        <p class="fs-30 mb-2"><?= $counters['products'] ?></p>
                        <!-- <p>10.00% (30 days)</p> -->
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Clients</p>
                        <p class="fs-30 mb-2"><?= $counters['clients'] ?></p>
                        <!-- <p>22.00% (30 days)</p> -->
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Total Orders</p>
                        <p class="fs-30 mb-2"><?= $counters['orders'] ?></p>
                        <!-- <p>22.00% (30 days)</p> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Total Order Amount</p>
                        <p class="fs-30 mb-2"><?= money_format( "&#8358; %!n", $counters['totalOrderAmount'] ) ?></p>
                        <!-- <p>2.00% (30 days)</p> -->
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">Order Amount Today</p>
                        <p class="fs-30 mb-2"><?= money_format( "&#8358; %!n", $counters['todayOrderAmount'] ) ?></p>
                        <!-- <p>0.22% (30 days)</p> -->
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Total Payment</p>
                        <p class="fs-30 mb-2"><?= money_format( "&#8358; %!n", $counters['totalPayment'] ) ?></p>
                        <!-- <p>2.00% (30 days)</p> -->
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">Payment Today</p>
                        <p class="fs-30 mb-2"><?= money_format( "&#8358; %!n", $counters['todayPayment'] ) ?></p>
                        <!-- <p>0.22% (30 days)</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>