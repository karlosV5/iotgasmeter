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
<!--div class="row">
      <form class="navbar-form navbar-left" method="POST" action="agregar_ventas.php">
        <div class="form-group">
          <input name="monto" type="text" class="form-control" placeholder="Monto">
        </div>
        <div class="form-group">
          <input name="venta_fecha" type="date" class="form-control" placeholder="Fecha">
        </div>
          <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Agregar Venta </button>
      </form>
</div--><!-- /.row -->
	<div class="row">
		<div class="col-md-10">
			<div class="box box-success">
            <div class="box-header with-border">
            	<?php
					       $year = date('Y');
            	?>
              <h3 class="box-title">Reporte de Consumo (<?php echo $year-2; ?> vs <?php echo $year-3; ?>)</h3>

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
<?php include('proceso_valor_mayor.php'); ?>
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

