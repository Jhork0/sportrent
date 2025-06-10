<?php
// obtener_horarios.php
include '../logica/conectar.php';


$fecha_reserva = $_POST['fecha_reserva'] ?? date('Y-m-d');
$id_cancha = $_POST['id_cancha'] ?? '';

// Consultar horarios de apertura/cierre de la cancha
$query_cancha = "SELECT hora_apertura, hora_cierre FROM cancha WHERE id_cancha = ?";
$stmt_cancha = $conn->prepare($query_cancha);
$stmt_cancha->bind_param("s", $id_cancha);
$stmt_cancha->execute();
$result_cancha = $stmt_cancha->get_result();
$fila = $result_cancha->fetch_assoc();

// Procesar horarios como en el código anterior
$hora_apertura = strtotime($fila['hora_apertura'] ?? '06:00');
$hora_cierre = strtotime($fila['hora_cierre'] ?? '13:00');

if ($fila['hora_apertura'] === '00:00' && $fila['hora_cierre'] === '00:00') {
    $hora_apertura = strtotime('00:00');
    $hora_cierre = strtotime('23:59');
}

// Consultar reservas existentes
$query_reservas = "SELECT hora_inicio, hora_final FROM reserva 
                  WHERE id_cancha = ? AND fecha_reserva = ? AND estado != 'cancelada'";
$stmt_reservas = $conn->prepare($query_reservas);
$stmt_reservas->bind_param("ss", $id_cancha, $fecha_reserva);
$stmt_reservas->execute();
$result_reservas = $stmt_reservas->get_result();

$horarios_reservados = [];
while ($reserva = $result_reservas->fetch_assoc()) {
    $horarios_reservados[] = [
        'inicio' => $reserva['hora_inicio'],
        'fin' => $reserva['hora_final']
    ];
}

// Generar los botones de horario
for ($hora = $hora_apertura; $hora < $hora_cierre; $hora = strtotime("+1 hour", $hora)) {
    $hora_inicio = date("H:i", $hora);
    $hora_fin = date("H:i", strtotime("+1 hour", $hora));
    $valor = "$hora_inicio - $hora_fin";
    
    $disponible = true;
    foreach ($horarios_reservados as $reservado) {
        if ($hora_inicio == $reservado['inicio'] && $hora_fin == $reservado['fin']) {
            $disponible = false;
            break;
        }
    }
    
    if ($disponible) {
        echo '<label class="cursor-pointer flex items-center gap-2 px-4 py-2 rounded bg-blue-500 text-white transition duration-200 border border-gray-300">';
        echo '<input type="radio" name="horario" value="' . htmlspecialchars($valor) . '" class="hidden peer" required>';
        echo '<span class="peer-checked:bg-gray-600 peer-checked:text-white px-2 py-1 rounded transition duration-200">' . htmlspecialchars($valor) . '</span>';
        echo '</label>';
    } else {
        echo '<label class="flex items-center gap-2 px-4 py-2 rounded bg-gray-300 text-gray-500 cursor-not-allowed">';
        echo '<span class="px-2 py-1 rounded">' . htmlspecialchars($valor) . ' (Reservado)</span>';
        echo '</label>';
    }
}
?>