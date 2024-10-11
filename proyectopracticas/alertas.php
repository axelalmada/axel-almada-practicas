<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php';

// Función para obtener los registros con más de 2 horas de salida
function obtenerRegistrosConAlerta($conn) {
    $sql = "SELECT * FROM salidas WHERE TIMESTAMPDIFF(HOUR, CONCAT(fecha, ' ', hora), NOW()) > 2 ORDER BY fecha DESC, hora DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$registrosConAlerta = obtenerRegistrosConAlerta($conn);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas de Salidas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-image: url('fondo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        h2 {
            color: #3a4f63;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 8px;
        }
        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #3a4f63;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: rgba(245, 245, 245, 0.8);
        }
        .btn {
            background-color: #3a4f63;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2c3e50;
        }
        p {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h2>Alertas de Salidas (Más de 2 horas)</h2>
    <?php if (!empty($registrosConAlerta)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Profesor Responsable</th>
                    <th>Curso</th>
                    <th>Número de Computadoras</th>
                    <th>Número de Cargadores</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrosConAlerta as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $row['hora']; ?></td>
                        <td><?php echo $row['profesor_responsable']; ?></td>
                        <td><?php echo $row['curso']; ?></td>
                        <td><?php echo $row['numero_computadoras']; ?></td>
                        <td><?php echo $row['numero_cargadores']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay registros con más de 2 horas de salida.</p>
    <?php endif; ?>

    <button class="btn" onclick="window.location.href='alta.php'">Volver a Lista de Registros</button>
</body>
</html>
