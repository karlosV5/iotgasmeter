<?php
session_start();
include_once("config.php");

if(!isset($_SESSION['idDispositivo'])){
    header("Location: login.php");
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$idDispositivo = $_SESSION['idDispositivo'];
// Declaramos el fichero de conexión

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
    if($tipo_usuario == 1){

        $suma = "SELECT COALESCE((SELECT SUM(max_valor) FROM (SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = '$month' AND YEAR(Fecha) = '$year1' AND Variable = 'consumo' GROUP BY idDispositivo) AS valores_mes_actual), 0) - COALESCE((SELECT SUM(max_valor) FROM (SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = CASE WHEN '$month' = 1 THEN 12 ELSE '$month' - 1 END AND YEAR(Fecha) = CASE WHEN '$month' = 1 THEN '$year1' - 1 ELSE '$year1' END AND Variable = 'consumo' GROUP BY idDispositivo) AS valores_mes_anterior), 0) AS total";
        } else if($tipo_usuario == 2){
    
        $suma = "SELECT COALESCE(( SELECT SUM(max_valor) FROM ( SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = '$month' AND YEAR(Fecha) = '$year1' AND idDispositivo = '$idDispositivo' AND Variable = 'consumo') AS valores_mes_actual ), 0) - COALESCE(( SELECT SUM(max_valor) FROM ( SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = CASE WHEN '$month' = 1 THEN 12 ELSE '$month' - 1 END AND YEAR(Fecha) = CASE WHEN '$month' = 1 THEN '$year1' - 1 ELSE '$year1' END AND idDispositivo = '$idDispositivo' AND Variable = 'consumo') AS valores_mes_anterior ), 0) AS total";
    }
    $query = $db->prepare("$suma");
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
    if($tipo_usuario == 1){

        $suma_ant = "SELECT COALESCE((SELECT SUM(max_valor) FROM (SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = '$month' AND YEAR(Fecha) = '$year2' AND Variable = 'consumo' GROUP BY idDispositivo) AS valores_mes_actual), 0) - COALESCE((SELECT SUM(max_valor) FROM (SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = CASE WHEN '$month' = 1 THEN 12 ELSE '$month' - 1 END AND YEAR(Fecha) = CASE WHEN '$month' = 1 THEN '$year2' - 1 ELSE '$year2' END AND Variable = 'consumo' GROUP BY idDispositivo) AS valores_mes_anterior), 0) AS total";
        } else if($tipo_usuario == 2){
    
        $suma_ant = "SELECT COALESCE(( SELECT SUM(max_valor) FROM ( SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = '$month' AND YEAR(Fecha) = '$year2' AND idDispositivo = '$idDispositivo' AND Variable = 'consumo') AS valores_mes_actual ), 0) - COALESCE(( SELECT SUM(max_valor) FROM ( SELECT MAX(Valor) AS max_valor FROM consumo_historico WHERE MONTH(Fecha) = CASE WHEN '$month' = 1 THEN 12 ELSE '$month' - 1 END AND YEAR(Fecha) = CASE WHEN '$month' = 1 THEN '$year2' - 1 ELSE '$year2' END AND idDispositivo = '$idDispositivo' AND Variable = 'consumo') AS valores_mes_anterior ), 0) AS total";
    }
    $query = $db->prepare($suma_ant);
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

    
    
    //para el dashboard
    /*if($tipo_usuario == 1){

            $where = "";
            } else if($tipo_usuario == 2){
        
            $where = "WHERE idDispositivo = '$idDispositivo'";
    }

    $sql = "SELECT * FROM consumo_actual $where";
    //$resultado = $mysqli->query($sql);*/
    

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sistema Consumo</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" /><!--añadido-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script><!--añadido-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script><!--añadido-->

        <!-- ChartJS -->
        <script src="chart.js/Chart.js"></script><!--añadido-->
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Sistema de Monitoreo IoT</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <!--<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>-->
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $nombre; ?><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Configuración</a></li>
                        <!--<li><a class="dropdown-item" href="#!">Activity</a></li>-->
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Salir</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Opciones</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Página Principal
                            </a>

                            <?php if($tipo_usuario == 1) { ?>

                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.php">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>

                            <?php } ?>

                            <div class="sb-sidenav-menu-heading">Complementos</div>
                            <?php if($tipo_usuario == 2) { ?>
                            <a class="nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Historial de Consumo
                            </a>
                            <?php } ?>
                            <a class="nav-link" href="tabla.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Consumo Actual
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $nombre; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <!--div class="container-fluid px-4">
                        <h1 class="mt-4">Página Principal</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Página Principal</li>
                        </ol>
                
                    </div-->
                    <!--añadido-->
                    <div class="container-fluid px-4">
                        <!--h3 class="mt-4">Gráfico de Consumo Anual</h3-->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
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
                        </div>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-xs-12 col-sm-10">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Reporte de Consumo (<?php echo $year2; ?> vs <?php echo $year1; ?>)</h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="chart">
                                                <canvas id="barChart" style="height:200px"></canvas>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
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

                    <!--añadido-->
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Created by Carlos Roldan 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script-->
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
