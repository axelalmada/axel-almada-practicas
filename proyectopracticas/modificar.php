<?php
require_once "conexion.php";

class NotebookManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerRegistro($id) {
        $sql = "SELECT * FROM notebooks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function actualizarRegistro($id, $modelo, $problemas, $estado, $numero, $fecha_revision, $hora_revision, $codigo_serie) {
        $sql = "UPDATE notebooks SET modelo = ?, problemas = ?, estado = ?, numero = ?, fecha_revision = ?, hora_revision = ?, codigo_serie = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssisssi", $modelo, $problemas, $estado, $numero, $fecha_revision, $hora_revision, $codigo_serie, $id);
        return $stmt->execute();
    }

    public function eliminarRegistro($id) {
        $sql = "DELETE FROM notebooks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerTodosRegistros() {
        $sql = "SELECT * FROM notebooks ORDER BY fecha_revision DESC, hora_revision DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

class NotebookView {
    public static function mostrarFormularioEdicion($registro) {
        echo "<h2>Editar Registro</h2>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='id' value='" . $registro['id'] . "'>";
        echo "<label>Modelo: <input type='text' name='modelo' value='" . htmlspecialchars($registro['modelo']) . "' required></label><br>";
        echo "<label>Problemas: <textarea name='problemas' required>" . htmlspecialchars($registro['problemas']) . "</textarea></label><br>";
        echo "<label>Estado: 
              <select name='estado' required>
                <option value='nuevo' " . ($registro['estado'] == 'nuevo' ? 'selected' : '') . ">Nuevo</option>
                <option value='usado' " . ($registro['estado'] == 'usado' ? 'selected' : '') . ">Usado</option>
                <option value='reparado' " . ($registro['estado'] == 'reparado' ? 'selected' : '') . ">Reparado</option>
                <option value='dañado' " . ($registro['estado'] == 'dañado' ? 'selected' : '') . ">Dañado</option>
              </select></label><br>";
        echo "<label>Número: <input type='number' name='numero' value='" . $registro['numero'] . "' required></label><br>";
        echo "<label>Fecha de Revisión: <input type='date' name='fecha_revision' value='" . $registro['fecha_revision'] . "' required></label><br>";
        echo "<label>Hora de Revisión: <input type='time' name='hora_revision' value='" . $registro['hora_revision'] . "' required></label><br>";
        echo "<label>Código de Serie: <input type='text' name='codigo_serie' value='" . htmlspecialchars($registro['codigo_serie']) . "' required></label><br>";
        echo "<input type='submit' name='actualizar' value='Actualizar'>";
        echo "</form>";
    }

    public static function mostrarTablaRegistros($registros) {
        if (empty($registros)) {
            echo "<p>No hay registros en la base de datos.</p>";
            return;
        }

        echo "<h2>Lista de Notebooks</h2>";
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Problemas</th>
                    <th>Estado</th>
                    <th>Número</th>
                    <th>Fecha de Revisión</th>
                    <th>Hora de Revisión</th>
                    <th>Código de Serie</th>
                    <th>Acciones</th>
                </tr>";

        foreach ($registros as $row) {
            echo "<tr>
                    <td>".$row["id"]."</td>
                    <td>".$row["modelo"]."</td>
                    <td>".$row["problemas"]."</td>
                    <td>".$row["estado"]."</td>
                    <td>".$row["numero"]."</td>
                    <td>".$row["fecha_revision"]."</td>
                    <td>".$row["hora_revision"]."</td>
                    <td>".$row["codigo_serie"]."</td>
                    <td>
                        <a href='?editar=".$row["id"]."'>Editar</a> | 
                        <a href='?borrar=".$row["id"]."' onclick='return confirm(\"¿Estás seguro de que quieres borrar este registro?\")'>Borrar</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    }

    public static function mostrarMensaje($mensaje, $tipo = 'success') {
        echo "<div class='" . $tipo . "'>" . $mensaje . "</div>";
    }

    public static function mostrarBotonVolver() {
        echo "<div style='text-align: center; margin-top: 20px;'>";
        echo "<a href='procesar.php' style='background-color: #3a4f63; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; text-decoration: none;'>Volver</a>";
        echo "</div>";
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
            h2 {
                color: #3a4f63;
                text-align: center;
            }
            table {
                width: 90%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: #fff;
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
                background-color: #f5f5f5;
            }
            .success {
                color: green;
                font-weight: bold;
            }
            .error {
                color: red;
                font-weight: bold;
            }
            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
            form label {
                display: block;
                margin-bottom: 10px;
            }
            form input[type='text'],
            form input[type='number'],
            form input[type='date'],
            form input[type='time'],
            form select,
            form textarea {
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
            form input[type='submit']:hover {
                background-color: #2c3e50;
            }
            a {
                color: #3a4f63;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>";
    }
}

$notebookManager = new NotebookManager($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $modelo = $_POST['modelo'];
    $problemas = $_POST['problemas'];
    $estado = $_POST['estado'];
    $numero = $_POST['numero'];
    $fecha_revision = $_POST['fecha_revision'];
    $hora_revision = $_POST['hora_revision'];
    $codigo_serie = $_POST['codigo_serie'];

    if ($notebookManager->actualizarRegistro($id, $modelo, $problemas, $estado, $numero, $fecha_revision, $hora_revision, $codigo_serie)) {
        NotebookView::mostrarMensaje("Registro actualizado exitosamente");
    } else {
        NotebookView::mostrarMensaje("Error al actualizar el registro", "error");
    }
}

if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    if ($notebookManager->eliminarRegistro($id_borrar)) {
        NotebookView::mostrarMensaje("Registro eliminado exitosamente");
    } else {
        NotebookView::mostrarMensaje("Error al eliminar el registro", "error");
    }
}

if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $registro = $notebookManager->obtenerRegistro($id_editar);
    if ($registro) {
        NotebookView::mostrarFormularioEdicion($registro);
    } else {
        NotebookView::mostrarMensaje("Registro no encontrado", "error");
    }
}

$registros = $notebookManager->obtenerTodosRegistros();
NotebookView::mostrarTablaRegistros($registros);

NotebookView::mostrarBotonVolver();
NotebookView::mostrarEstilos();

$conn->close();
?>
