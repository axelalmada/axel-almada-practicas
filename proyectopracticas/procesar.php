<?php
require_once "conexion.php";

class NotebookManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertNotebook($data) {
        $sql = "INSERT INTO notebooks (modelo, problemas, estado, numero, fecha_revision, hora_revision, codigo_serie) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssisss", $data['modelo'], $data['problemas'], $data['estado'], $data['numero'], $data['fecha_revision'], $data['hora_revision'], $data['codigo_serie']);
        
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        return true;
    }

    public function getAllNotebooks() {
        $sql = "SELECT * FROM notebooks ORDER BY fecha_revision DESC, hora_revision DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function searchNotebooks($search) {
        $sql = "SELECT * FROM notebooks WHERE modelo LIKE ? OR problemas LIKE ? OR estado LIKE ? OR numero LIKE ? OR fecha_revision LIKE ? OR hora_revision LIKE ? OR codigo_serie LIKE ? ORDER BY fecha_revision DESC, hora_revision DESC";
        $stmt = $this->conn->prepare($sql);
        $search = "%$search%";
        $stmt->bind_param("sssssss", $search, $search, $search, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

class NotebookView {
    public static function render($notebooks) {
        self::renderStyles();
        self::renderSearchForm();
        self::renderTable($notebooks);
        self::renderButtons();
    }

    private static function renderTable($notebooks) {
        if (empty($notebooks)) {
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
                </tr>";

        foreach ($notebooks as $notebook) {
            echo "<tr>";
            foreach ($notebook as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    private static function renderButtons() {
        echo "<div class='button-container'>
                <button onclick=\"window.location.href='modificar.php'\">Modificar Registros</button>
                <button onclick=\"window.location.href='index.html'\">Volver al Inicio</button>
              </div>";
    }

    private static function renderStyles() {
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
                background-color: rgba(255, 255, 255, 0.9);
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
            }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
            th { background-color: #3a4f63; color: white; font-weight: bold; }
            tr:hover { background-color: rgba(245, 245, 245, 0.8); }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            .button-container {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin-top: 20px;
            }
            button {
                background-color: #3a4f63;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 4px;
                transition: background-color 0.3s ease;
            }
            button:hover { background-color: #2c3e50; }
        </style>";
    }

    private static function renderSearchForm() {
        echo "<form action='' method='GET'>
                <input type='text' name='search' placeholder='Buscar...' style='margin-bottom: 20px; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; width: 90%;'>
                <button type='submit' style='background-color: #3a4f63; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; transition: background-color 0.3s ease;'>Buscar</button>
              </form>";
    }
}

try {
    $notebookManager = new NotebookManager($conn);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $notebookData = [
            'modelo' => $_POST['modelo'],
            'problemas' => $_POST['problemas'],
            'estado' => $_POST['estado'],
            'numero' => $_POST['numero'],
            'fecha_revision' => $_POST['fecha_revision'],
            'hora_revision' => $_POST['hora_revision'],
            'codigo_serie' => $_POST['codigo_serie']
        ];

        if ($notebookManager->insertNotebook($notebookData)) {
            echo "<div class='success'>Registro guardado exitosamente</div>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
        $notebooks = $notebookManager->searchNotebooks($_GET['search']);
    } else {
        $notebooks = $notebookManager->getAllNotebooks();
    }

    NotebookView::render($notebooks);

} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
} finally {
    $conn->close();
}
?>