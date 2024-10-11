<?php
require_once "conexion.php";
 
class SalidaManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerRegistro($id) {
        $stmt = $this->conn->prepare("SELECT * FROM salidas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarRegistro($id, $fecha, $hora, $profesor_responsable, $curso, $numero_computadoras, $numero_cargadores) {
        $stmt = $this->conn->prepare("UPDATE salidas SET fecha = ?, hora = ?, profesor_responsable = ?, curso = ?, numero_computadoras = ?, numero_cargadores = ? WHERE id = ?");
        $stmt->bind_param("ssssiii", $fecha, $hora, $profesor_responsable, $curso, $numero_computadoras, $numero_cargadores, $id);
        return $stmt->execute();
    }

    public function eliminarRegistro($id) {
        $stmt = $this->conn->prepare("DELETE FROM salidas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerTodosRegistros() {
        return $this->conn->query("SELECT * FROM salidas ORDER BY fecha DESC, hora DESC")->fetch_all(MYSQLI_ASSOC);
    }
}

class SalidaView {
    public static function mostrarFormularioEdicion($registro) {
        echo "<h2>Editar Registro</h2>
              <form method='post' action=''>
                <input type='hidden' name='id' value='{$registro['id']}'>
                <label>Fecha: <input type='date' name='fecha' value='{$registro['fecha']}' required></label><br>
                <label>Hora: <input type='time' name='hora' value='{$registro['hora']}' required></label><br>
                <label>Profesor Responsable: <input type='text' name='profesor_responsable' value='" . htmlspecialchars($registro['profesor_responsable']) . "' required></label><br>
                <label>Curso: <input type='text' name='curso' value='" . htmlspecialchars($registro['curso']) . "' required></label><br>
                <label>Número de Computadoras: <input type='number' name='numero_computadoras' value='{$registro['numero_computadoras']}' required></label><br>
                <label>Número de Cargadores: <input type='number' name='numero_cargadores' value='{$registro['numero_cargadores']}' required></label><br>
                <input type='submit' name='actualizar' value='Actualizar'>
              </form>";
    }

    public static function mostrarListaRegistros($registros) {
        if (empty($registros)) {
            echo "<p>No hay registros en la base de datos.</p>";
            return;
        }

        echo "<h2>Lista de Salidas</h2>
              <table>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Profesor Responsable</th>
                    <th>Curso</th>
                    <th>Número de Computadoras</th>
                    <th>Número de Cargadores</th>
                    <th>Acciones</th>
                </tr>";

        foreach ($registros as $row) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['fecha']}</td>
                    <td>{$row['hora']}</td>
                    <td>{$row['profesor_responsable']}</td>
                    <td>{$row['curso']}</td>
                    <td>{$row['numero_computadoras']}</td>
                    <td>{$row['numero_cargadores']}</td>
                    <td>
                        <a href='?editar={$row['id']}'>Editar</a> | 
                        <a href='?borrar={$row['id']}' onclick='return confirm(\"¿Estás seguro de que quieres borrar este registro?\")'>Borrar</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    }

    public static function mostrarMensaje($mensaje, $tipo = 'success') {
        echo "<div class='$tipo'>$mensaje</div>";
    }

    public static function mostrarBotonVolver() {
        echo "<div style='text-align: center; margin-top: 20px;'>
                <input type='button' value='Volver' onclick=\"window.location.href='alta.php'\" style='background-color: #3a4f63; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;'>
              </div>";
    }

    public static function mostrarEstilos() {
        echo "<style>
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
            }
            h2 { color: #3a4f63; text-align: center; }
            table {
                width: 90%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: #fff;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
            }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
            th { background-color: #3a4f63; color: white; font-weight: bold; }
            tr:hover { background-color: #f5f5f5; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
            form label { display: block; margin-bottom: 10px; }
            form input[type='text'], form input[type='number'], form input[type='date'], form input[type='time'] {
                width: 100%;
                padding: 8px;
                margin-top: 5px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            form input[type='submit'] {
                background-color: #3a4f63;
                color: white;
                border: none;
                padding: 10px 20px;
                margin-top: 10px;
                cursor: pointer;
                border-radius: 4px;
            }
            form input[type='submit']:hover { background-color: #2c3e50; }
            a { color: #3a4f63; text-decoration: none; }
            a:hover { text-decoration: underline; }
        </style>";
    }
}

$salidaManager = new SalidaManager($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $profesor_responsable = $_POST['profesor_responsable'];
    $curso = $_POST['curso'];
    $numero_computadoras = $_POST['numero_computadoras'];
    $numero_cargadores = $_POST['numero_cargadores'];

    if ($salidaManager->actualizarRegistro($id, $fecha, $hora, $profesor_responsable, $curso, $numero_computadoras, $numero_cargadores)) {
        SalidaView::mostrarMensaje("Registro actualizado exitosamente");
    } else {
        SalidaView::mostrarMensaje("Error al actualizar el registro: " . $conn->error, "error");
    }
}

if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    if ($salidaManager->eliminarRegistro($id_borrar)) {
        SalidaView::mostrarMensaje("Registro eliminado exitosamente");
    } else {
        SalidaView::mostrarMensaje("Error al eliminar el registro: " . $conn->error, "error");
    }
}

if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $registro = $salidaManager->obtenerRegistro($id_editar);
    if ($registro) {
        SalidaView::mostrarFormularioEdicion($registro);
    } else {
        SalidaView::mostrarMensaje("Registro no encontrado", "error");
    }
}

$registros = $salidaManager->obtenerTodosRegistros();
SalidaView::mostrarListaRegistros($registros);
SalidaView::mostrarBotonVolver();
SalidaView::mostrarEstilos();

$conn->close();
?>
