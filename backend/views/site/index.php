<?php
use common\helpers\Strings;
use common\models\Invoice;
use common\models\Transaction;
use common\models\Student;

$this->title = 'Laporan 30 Hari Terakhir';

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;
$schoolId = Yii::$app->session->get('school_id');

/**
 *-------------------------------
 * Card Report
 *-------------------------------
 */

// CARD 1
$last30Day = new DateTime('-30 days');
$transactionCount = Transaction::find()
    ->select('number')
    ->where(['school_id' => $schoolId])
    ->andWhere(['between', 'date', $last30Day->format('Y-m-d'), date('Y-m-d')])
    ->distinct()
    ->count();

// CARD 2
$totalPayment = 0;

// CARD 3
$last30Day = new DateTime('-30 days');
$overdueCount = Invoice::find()
    ->select('number')
    ->where(['school_id' => $schoolId])
    ->andWhere(['between', 'due_date', $last30Day->format('Y-m-d'), date('Y-m-d')])
    ->andWhere('paid_amount < amount')
    ->distinct()
    ->count();

// CARD 4
$studentCount = Student::find()
    ->where(['school_id' => $schoolId])
    ->andWhere(['status' => 'ACTIVE'])
    ->count();

/**
 *-------------------------------
 * Last 30 Days Chart
 *-------------------------------
 */

// variable definition
$jsonDay = '';
$jsonTotal = '';

// loop last 30 days
$dateTime = new DateTime('-30 days');
for ($i = 1; $i <= 31; $i++) {
    $transactions = Transaction::find()
        ->where(['school_id' => $schoolId])
        ->andWhere(['date' => $dateTime->format('Y-m-d')])
        ->all();
    $totalInDay = 0;
    foreach ($transactions as $transaction) {
        $totalInDay += $transaction->amount;
    }

    // add value to array
    $jsonDay .= '"'.$dateTime->format('d').'", ';
    $jsonTotal .= $totalInDay.', ';

    // modify date -1 month
    $dateTime->modify('+1 day');

    // add total payment
    $totalPayment += $totalInDay;
}
?>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $transactionCount ?></h3>
                <p>Transaksi Pembayaran</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= Strings::getRupiah($totalPayment) ?><sup style="font-size: 20px;"></sup></h3>
                <p>Total Pembayaran</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $overdueCount ?></h3>
                <p>Tunggakan Tagihan</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $studentCount ?></h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
</div>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Total Pembayaran 30 Hari Terakhir</h3>
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
          labels  : [<?= $jsonDay ?>],
          datasets: [
            {
              label               : 'Total Pembayaran (Rupiah)',
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