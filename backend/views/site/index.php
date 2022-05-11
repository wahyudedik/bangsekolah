<?php
use common\models\Order;

$this->title = 'Last 30 Days Summary';

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;

// variable definition
$jsonMonth = '';
$jsonTotal = '';

// loop last 6 month
$dateTime = new DateTime('first day of this month');
for ($i = 1; $i <= 5; $i++) {
    $dateTime->modify('-1 month');
}
for ($i = 1; $i <= 6; $i++) {
    $orders = Order::find()
        ->where(['year(ordered_at)' => $dateTime->format('Y')])
        ->andWhere(['month(ordered_at)' => $dateTime->format('m')])
        ->andWhere(['in', 'status', ['FINALIZE', 'SENT', 'PAID']])
        ->all();
    $totalInMonth = 0;
    foreach ($orders as $order) {
        $totalInMonth += $order->total;
    }

    // add value to array
    $jsonMonth .= '"'.$dateTime->format('M Y').'", ';
    $jsonTotal .= $totalInMonth.', ';

    // modify date -1 month
    $dateTime->modify('+1 month');
}

?>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $new_order ?></h3>
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
                <h3><?= number_format($success_order, 0) ?><sup style="font-size: 20px;"></sup></h3>
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
                <h3><?= $production ?></h3>
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
                <h3><?= $total_order ?></h3>
                <p>Total Order</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Omzet By Month</h3>
    </div>
    <div class="card-body">
        <div class="chart">
            <canvas id="bar-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
    </div>
</div>

<script src="<?= $baseUrl ?>/theme/js/jquery-1.11.2.min.js"></script>
<script src="<?= $baseUrl ?>/theme/plugins/chart.js/Chart.min.js"></script>
<script type="text/javascript">
    $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        var areaChartData = {
          labels  : [<?= $jsonMonth ?>],
          datasets: [
            {
              label               : 'Total Outlet Order (Rupiah)',
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : [<?= $jsonTotal ?>]
            },
          ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#bar-chart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        barChartData.datasets[0] = temp0

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        new Chart(barChartCanvas, {
          type: 'bar',
          data: barChartData,
          options: barChartOptions
        })
    });
</script>