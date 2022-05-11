<?php
$baseUrl = Yii::$app->request->baseUrl;
?>

<canvas id="bar-chart" style="min-height: 100%; height: 100%; max-height: 250px; max-width: 100%;"></canvas>

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
              label               : 'Total Order (Rupiah)',
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