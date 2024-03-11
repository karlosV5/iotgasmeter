<?php
// Declaramos el fichero de conexión
include_once("config.php");

if(isset($_POST['year1']) && isset($_POST['year2'])) {
    $year1 = $_POST['year1'];
    $year2 = $_POST['year2'];
} else {
    // Si no se han seleccionado los años, establecemos valores predeterminados
    $year1 = date('Y') - 1;
    $year2 = date('Y');
}

$total = array();
$ptotal = array();

for ($month = 1; $month <= 12; $month ++){
    $query = $db->prepare("SELECT SUM(max_valor) AS total FROM (SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = '$month' AND YEAR(Fecha) = '$year1' GROUP BY idDispositivo) AS max_values");
    $query->execute();
    $row = $query->fetch();
    $total[] = $row['total'];
}

$tjan = $total[0];
$tfeb = $total[1];
$tmar = $total[2];
$tapr = $total[3];
$tmay = $total[4];
$tjun = $total[5];
$tjul = $total[6];
$taug = $total[7];
$tsep = $total[8];
$toct = $total[9];
$tnov = $total[10];
$tdec = $total[11];

for ($month = 1; $month <= 12; $month ++){
    $query = $db->prepare("SELECT SUM(max_valor) AS total FROM (SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = '$month' AND YEAR(Fecha) = '$year2' GROUP BY idDispositivo) AS max_values");
    $query->execute();
    $row = $query->fetch();
    $ptotal[] = $row['total'];
}

$pjan = $ptotal[0];
$pfeb = $ptotal[1];
$pmar = $ptotal[2];
$papr = $ptotal[3];
$pmay = $ptotal[4];
$pjun = $ptotal[5];
$pjul = $ptotal[6];
$paug = $ptotal[7];
$psep = $ptotal[8];
$poct = $ptotal[9];
$pnov = $ptotal[10];
$pdec = $ptotal[11];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Consumo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- ChartJS -->
    <script src="chart.js/Chart.js"></script>
</head>
<body>
<div class="container">
    <h1 class="page-header text-left">Gráfico de Consumo Anual</h1>
    <div class="row">
        <form class="navbar-form navbar-left" method="POST" action="">
            <div class="form-group">
                <label for="year1">Año 1:</label>
                <select name="year1" class="form-control">
                    <?php for ($i = 2021; $i <= 2030; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php if($i == $year1) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="year2">Año 2:</label>
                <select name="year2" class="form-control">
                    <?php for ($i = 2021; $i <= 2030; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php if($i == $year2) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte de Consumo (<?php echo $year2; ?> vs <?php echo $year1; ?>)</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChart" style="height:250px"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
<script>
  $(function () {
    var barChartData = {
      labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      datasets: [
        {
          label               : 'Año Previo',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [ "<?php echo $pjan; ?>",
                                  "<?php echo $pfeb; ?>",
                                  "<?php echo $pmar; ?>",
                                  "<?php echo $papr; ?>",
                                  "<?php echo $pmay; ?>",
                                  "<?php echo $pjun; ?>",
                                  "<?php echo $pjul; ?>",
                                  "<?php echo $paug; ?>",
                                  "<?php echo $psep; ?>",
                                  "<?php echo $poct; ?>",
                                  "<?php echo $pnov; ?>",
                                  "<?php echo $pdec; ?>" 
                                ]
        },
        {
          label               : 'Este año',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [ "<?php echo $tjan; ?>",
                                  "<?php echo $tfeb; ?>",
                                  "<?php echo $tmar; ?>",
                                  "<?php echo $tapr; ?>",
                                  "<?php echo $tmay; ?>",
                                  "<?php echo $tjun; ?>",
                                  "<?php echo $tjul; ?>",
                                  "<?php echo $taug; ?>",
                                  "<?php echo $tsep; ?>",
                                  "<?php echo $toct; ?>",
                                  "<?php echo $tnov; ?>",
                                  "<?php echo $tdec; ?>" 
                                ]
        }
      ]
    }
  
    var barChartCanvas          = $('#barChart').get(0).getContext('2d')
    var barChart                = new Chart(barChartCanvas)
    var barChartOptions         = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio     : true
    }
  
    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)

  })
</script>
</body>
</html>


