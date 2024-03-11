<?php
    session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "prueba";

    $con = new mysqli($host, $user, $password, $database);
    if ($con->connect_error) {
        die("Error de conexión: " . $con->connect_error);
    }

    if(!isset($_SESSION['idDispositivo'])){
        header("Location: login.php");
    }
    $nombre = $_SESSION['nombre'];
    $idDispositivo = $_SESSION['idDispositivo'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');

    $sql = "SELECT Fecha, Valor FROM consumo_historico WHERE idDispositivo='$idDispositivo' AND YEAR(Fecha) = '$year' AND MONTH(Fecha) = '$month' AND Variable = 'consumo' ORDER BY Fecha";
    $query = $con->query($sql);
    $data = array();

    while ($row = $query->fetch_assoc()) {
        $data[] = array(
            "Fecha" => $row["Fecha"],
            "Valor" => $row["Valor"],
        );
    }

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Reporte Diario</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
        <style>
            canvas {
                height: 500px;
                width: auto;
            }
        </style>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Sistema de Monitoreo</a>
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
                            <div class="sb-sidenav-menu-heading">Complementos</div>
                            <a class="nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Historial de Consumo
                            </a>
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
                    <div class="container-fluid px-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="mt-4">Tendencia</h2>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="index.php">Página Principal</a></li>
                                    <li class="breadcrumb-item active">Historial de Consumo</li>
                                </ol>
                                <!--añadido-->
                                <select id="yearSelect">
                                    <?php for($i = 2021; $i <= 2030; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php if($i == $year) echo 'selected'; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <select id="monthSelect">
                                    <?php 
                                        $months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                        for($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo sprintf('%02d', $i); ?>" <?php if($i == $month) echo 'selected'; ?>><?php echo $months[$i - 1]; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <!-- Div contenedor para el gráfico con altura definida -->
                                <div style="height:350px;">
                                    <canvas id="grafica"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                                <script>
                                document.getElementById('yearSelect').addEventListener('change', applyFilter);
                                document.getElementById('monthSelect').addEventListener('change', applyFilter);

                                function applyFilter() {
                                    var year = document.getElementById('yearSelect').value;
                                    var month = document.getElementById('monthSelect').value;
                                    window.location.href = `?year=${year}&month=${month}`;
                                }
                                </script>

                                <canvas id="grafica"></canvas>
                                <script>
                                    var ctx = document.getElementById("grafica").getContext("2d");
                                    var data = <?php echo json_encode($data); ?>;

                                    var labels = [];
                                    var valores = [];

                                    // Establecer el idioma de moment.js a español
                                    moment.locale('es');

                                    for (var i = 0; i < data.length; i++) {
                                        var date = new Date(data[i].Fecha);
                                        var formattedDate = moment(date).format('ddd DD HH:mm'); // Formatear la fecha como "Mi 07 00:00"
                                        labels.push(formattedDate);
                                        valores.push(data[i].Valor);
                                    }

                                    var chart = new Chart(ctx, {
                                        type: "line",
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: "Valor Consumido",
                                                data: valores,
                                                borderColor: "rgb(75, 192, 192)",
                                                backgroundColor: "rgba(75, 192, 192, 0.2)",
                                                fill: true,
                                                tension: 0.1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                </script>
                                <!--añadido-->
                          
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="assets/demo/chart-pie-demo.js"></script>
    </body>
</html>
