<?php 
use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Dashboard;
$reports = (new Dashboard())->getDashboardReports();
$colors  = ['success', 'primary', 'danger', 'warning', 'info'];
?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-md-12 col-xl-4 d-flex flex-column justify-content-start">
                                    <div class="ml-xl-4 mt-3">
                                        <p class="card-title"><?= date("l, d/m/Y") ?></p>
                                        <h1 class="text-primary"><?= money_format("&#8358; %!n", $reports['orders']['total']) ?></h1>
                                        <h3 class="font-weight-500 mb-xl-4 text-primary">Total Order Amount</h3>
                                        <p class="mb-2 mb-xl-0 text-justify">
                                            The total amount of all orders created today.<br />
                                            <strong><?= $reports['orders']['count'] ?></strong> Orders. 
                                            
                                        </p>
                                    </div>  
                                </div>
                                <div class="col-md-12 col-xl-8">
                                    <div class="row">
                                        <div class="col-md-12 border-right">
                                            <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                <table class="table table-borderless report-table">
                                                    <?php 
                                                        $oi = 0;
                                                        if( $reports['orders']['count'] ) {
                                                            foreach( $reports['orders']['cats'] as $key => $value ){ 
                                                                $color   = $colors[$oi];
                                                                $percent = calculate_percentage_for_display( $value, $reports['orders']['total'] );
                                                    ?>
                                                                <tr>
                                                                    <td class="text-muted"><?= $key ?></td>
                                                                    <td class="w-100 px-0">
                                                                        <div class="progress progress-md mx-4">
                                                                            <div class="progress-bar bg-<?= $color ?>" role="progressbar" style="width: <?= $percent ?>" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td><h5 class="font-weight-bold mb-0"><?= money_format( "&#8358; %!n", $value ) ?></h5></td>
                                                                </tr>
                                                    <?php 
                                                                $oi++;
                                                            } 
                                                        }else{
                                                    ?>
                                                            <tr>
                                                                <td colspan="1">
                                                                    <?php UIFragments::renderEmptyRecords(
                                                                                "No Orders Today", 
                                                                                "Start accepting orders now!", 
                                                                                "New Order", 
                                                                                "href='orders_manage.php'") ?>
                                                                </td>
                                                                
                                                            </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6 mt-3">
                                            <canvas id="north-america-chart"></canvas>
                                            <div id="north-america-legend"></div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-12 col-xl-4 d-flex flex-column justify-content-start">
                                    <div class="ml-xl-4 mt-3">
                                        <p class="card-title"><?= date("l, d/m/Y") ?></p>
                                        <h1 class="text-primary"><?= money_format( "&#8358; %!n",  $reports['payments']['total'] ) ?></h1>
                                        <h3 class="font-weight-500 mb-xl-4 text-primary">Total Payment Received</h3>
                                        <p class="mb-2 mb-xl-0">
                                            The total amount received in client payments. <br />
                                            <strong><?= $reports['payments']['count'] ?></strong> Payments.
                                        </p>
                                    </div>  
                                </div>
                                <div class="col-md-12 col-xl-8">
                                    <div class="row">
                                        <div class="col-md-12 border-right">
                                            <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                <table class="table table-borderless report-table">
                                                    <?php 
                                                        $payMethods = ['Cash', 'POS', 'Transfer', 'Deposit'];
                                                        foreach ( $payMethods as $index => $method ) {
                                                            $payCatItem = $reports['payments']['cats'][$method];
                                                            $color      = $colors[ $index ];
                                                            $percent    = calculate_percentage_for_display( $payCatItem, $reports['payments']['total'] );
                                                    ?>
                                                            <tr>
                                                                <td class="text-muted"><?= $method ?></td>
                                                                <td class="w-100 px-0">
                                                                    <div class="progress progress-md mx-4">
                                                                        <div class="progress-bar bg-<?= $color ?>" role="progressbar" style="width: <?= $percent ?>" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </td>
                                                                <td><h5 class="font-weight-bold mb-0"><?= money_format( "&#8358; %!n", $payCatItem ) ?></h5></td>
                                                            </tr>
                                                    <?php } ?>
                                                    
                                                </table>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6 mt-3">
                                            <canvas id="south-america-chart"></canvas>
                                            <div id="south-america-legend"></div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#detailedReports" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#detailedReports" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex align-items-center justify-content-end p-2">
                    <a href="reports_daily.php" class="btn btn-block btn-outline-primary">
                        View Today's Full Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>