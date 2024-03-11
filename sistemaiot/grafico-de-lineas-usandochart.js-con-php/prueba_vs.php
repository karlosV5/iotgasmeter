<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico Responsivo con Bootstrap</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <select id="yearSelect" class="form-control mb-2">
                <?php for($i = 2021; $i <= 2030; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if($i == $year) echo 'selected'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <select id="monthSelect" class="form-control mb-3">
                <?php 
                    $months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo sprintf('%02d', $i); ?>" <?php if($i == $month) echo 'selected'; ?>><?php echo $months[$i - 1]; ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="col-md-12">
            <!-- Div contenedor para el gráfico con altura definida -->
            <div style="height:400px;">
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

var ctx = document.getElementById("grafica").getContext("2d");
var data = <?php echo json_encode($data); ?>;

var labels = [];
var valores = [];

moment.locale('es');

for (var i = 0; i < data.length; i++) {
    var date = new Date(data[i].Fecha);
    var formattedDate = moment(date).format('ddd DD HH:mm'); // Formatear la fecha
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

