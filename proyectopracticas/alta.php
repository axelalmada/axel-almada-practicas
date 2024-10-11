<?php
require_once 'conexion.php';

function validateInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = validateInput($_POST['fecha']);
    $hora = validateInput($_POST['hora']);
    $profesorResponsable = validateInput($_POST['profesorResponsable']);
    $curso = validateInput($_POST['curso']);
    $numeroComputadoras = intval($_POST['numeroComputadoras']);
    $numeroCargadores = intval($_POST['numeroCargadores']);

    $sql = "INSERT INTO salidas (fecha, hora, profesor_responsable, curso, numero_computadoras, numero_cargadores) VALUES (?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $fecha, $hora, $profesorResponsable, $curso, $numeroComputadoras, $numeroCargadores);
        
        if ($stmt->execute()) {
            $mensaje = "Registro guardado exitosamente";
        } else {
            throw new Exception("Error al guardar el registro");
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    } finally {
        $stmt->close();
    }
}

function obtenerRegistros($conn, $filtro = '') {
    $sql = "SELECT * FROM salidas " . $filtro . " ORDER BY fecha DESC, hora DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_GET['buscar'])) {
    $filtro = "WHERE fecha LIKE '%" . validateInput($_GET['buscar']) . "%' OR hora LIKE '%" . validateInput($_GET['buscar']) . "%' OR profesor_responsable LIKE '%" . validateInput($_GET['buscar']) . "%' OR curso LIKE '%" . validateInput($_GET['buscar']) . "%'";
} else {
    $filtro = '';
}

$registros = obtenerRegistros($conn, $filtro);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Salidas</title>
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
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            border-collapse: collapse;
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
            margin: 10px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2c3e50;
        }
        .mensaje {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .botones {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .buscador {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .buscador input[type="text"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            width: 50%;
        }
        .buscador button[type="submit"] {
            background-color: #3a4f63;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .buscador button[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <div class="buscador">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <input type="text" name="buscar" placeholder="Buscar...">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <h2>Lista de Registros</h2>
    <?php if (!empty($registros)): ?>
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
                <?php foreach ($registros as $row): ?>
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
        <p>No hay registros en la base de datos.</p>
    <?php endif; ?>

    <div class="botones">
        <button class="btn" onclick="window.location.href='modificar2.php'">Modificar Registros</button>
        <button class="btn" onclick="window.location.href='salida.html.html'">Volver a Salida</button>
        <button class="btn" onclick="window.location.href='alertas.php'">Alertas</button>
    </div>
</body>
</html>
