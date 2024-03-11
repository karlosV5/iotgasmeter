<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "iotgasmeter";

$con = new mysqli($host, $user, $password, $database);
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

$sql = "SELECT Fecha, Valor FROM historial_consumo WHERE idDispositivo='medidor1' AND YEAR(Fecha) = '$year' AND MONTH(Fecha) = '$month' ORDER BY Fecha";
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
    <meta charset="UTF-8">
    <title>Reporte Diario</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <style>
        canvas {
            height: 500px;
            width: auto;
        }
    </style>
</head>
<body>

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
</body>
</html>
