<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "iotgasmeter";

$con = new mysqli($host, $user, $password, $database);
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// Obtener los datos de la base de datos
$sql = "SELECT Fecha, Valor FROM historial_consumo WHERE idDispositivo='medidor1'";
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
</head>
<body>
    <div>
        <label for="mes">Selecciona un mes:</label>
        <select id="mes" onchange="actualizarGrafico()">
            <?php
            // Genera las opciones para los 12 meses
            for ($mes = 1; $mes <= 12; $mes++) {
                echo "<option value='$mes'>$mes</option>";
            }
            ?>
        </select>
    </div>
    <div>
        <label for="anio">Selecciona un año:</label>
        <select id="anio" onchange="actualizarGrafico()">
            <?php
            // Genera las opciones para los años desde 2021 hasta 2030
            for ($anio = 2021; $anio <= 2030; $anio++) {
                echo "<option value='$anio'>$anio</option>";
            }
            ?>
        </select>
    </div>
    <canvas id="grafica"></canvas>
    <script>
        var ctx = document.getElementById("grafica").getContext("2d");
        var labels = <?php echo json_encode(array_column($data, "Fecha")); ?>;
        var ventas = <?php echo json_encode(array_column($data, "Valor")); ?>;
        var chart;

        function actualizarGrafico() {
            var mesSeleccionado = document.getElementById("mes").value;
            var anioSeleccionado = document.getElementById("anio").value;

            // Filtrar los datos según las selecciones de mes y año
            var datosFiltrados = [];
            for (var i = 0; i < labels.length; i++) {
                var fecha = new Date(labels[i]);
                if (fecha.getMonth() + 1 == mesSeleccionado && fecha.getFullYear() == anioSeleccionado) {
                    datosFiltrados.push(ventas[i]);
                }
            }

            // Crear un arreglo con los días del mes
            var diasDelMes = Array.from({ length: new Date(anioSeleccionado, mesSeleccionado, 0).getDate() }, (_, i) => i + 1);

            // Actualizar el gráfico con los datos filtrados y los días del mes
            if (chart) {
                chart.destroy();
            }
            chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: diasDelMes,
                    datasets: [{
                        label: "Ventas Diarias",
                        data: datosFiltrados,
                        borderColor: "blue",
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: "Días del mes"
                            },
                            grid: {
                                drawOnChartArea: false,
                                tickLength: 30
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: "Valor"
                            }
                        }
                    }
                }
            });
        }

        // Inicializar el gráfico con todos los datos
        actualizarGrafico();
    </script>
</body>
</html>
