<?php

// Convertir las horas en formato de tiempo
$hora_apertura = strtotime($fila['hora_apertura'] ?? '06:00');
$hora_cierre = strtotime($fila['hora_cierre'] ?? '13:00');

// Si la cancha funciona las 24 horas, establecer el rango completo
if ($fila['hora_apertura'] === '00:00' && $fila['hora_cierre'] === '00:00') {
    $hora_apertura = strtotime('00:00');
    $hora_cierre = strtotime('23:59');
}

echo '<form id="formReserva" method="POST" class="space-y-4">';

// Campo de selección de fecha de reserva
echo '<div class="mb-6">';
echo '<label for="fecha_reserva" class="block text-lg font-semibold text-gray-700">Selecciona la Fecha de Reserva:</label>';
echo '<input type="date" id="fecha_reserva" name="fecha_reserva" class="mt-2 px-4 py-2 border rounded-lg text-gray-800" min="' . date('Y-m-d') . '" value="' . date('Y-m-d') . '" required>';
echo '</div>';

// Obtener la fecha seleccionada (por defecto la fecha actual para la carga inicial)
$fecha_seleccionada = date('Y-m-d'); 

// Consultar las reservas existentes para esta cancha en la fecha seleccionada
$query_reservas = "SELECT hora_inicio, hora_final FROM reserva 
                    WHERE id_cancha = ? AND fecha_reserva = ? AND estado != 'cancelada' AND estado != 'completada' AND estado != 'caducada'    ";
$stmt_reservas = $conn->prepare($query_reservas);
$stmt_reservas->bind_param("ss", $id_cancha, $fecha_seleccionada);
$stmt_reservas->execute();
$result_reservas = $stmt_reservas->get_result();

$horarios_reservados = [];
while ($reserva = $result_reservas->fetch_assoc()) {
    $horarios_reservados[] = [
        'inicio' => $reserva['hora_inicio'],
        'fin' => $reserva['hora_final']
    ];
}

echo '<div class="flex flex-wrap gap-2" id="horarios-container">';
// Este bucle se duplica en obtener_horarios.php, considera usar una función para evitar duplicidad
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
echo '</div>';

echo '<input type="hidden" name="id_cancha" value="' . htmlspecialchars($id_cancha) . '">';


echo '<button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Reservar Horario</button>';



echo '</form>';
?>