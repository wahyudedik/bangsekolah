<?php
use common\models\Order;

$this->title = 'Last 30 Days Summary';

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;

?>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>1</h3>
                <p>New Orders</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>2<sup style="font-size: 20px;"></sup></h3>
                <p>Success Orders</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>2</h3>
                <p>Finish Production</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>4</h3>
                <p>Total Order</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- <div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Omzet By Month</h3>
    </div>
    <div class="card-body">
        <div class="chart">
            <canvas id="bar-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
    </div>
</div> -->

<script src="<?= $baseUrl ?>/theme/js/jquery-1.11.2.min.js"></script>
<script src="<?= $baseUrl ?>/theme/plugins/chart.js/Chart.min.js"></script>