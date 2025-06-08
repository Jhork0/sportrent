<?php
// Convertir las horas en formato de tiempo
$hora_apertura = strtotime($fila['hora_apertura'] ?? '06:00');
$hora_cierre = strtotime($fila['hora_cierre'] ?? '13:00');

// Si la cancha funciona las 24 horas, establecer el rango completo
if ($fila['hora_apertura'] === '00:00' && $fila['hora_cierre'] === '00:00') {
    $hora_apertura = strtotime('00:00');
    $hora_cierre = strtotime('23:59'); // Se usa 23:59 para incluir el último horario correctamente
}

// **Iniciar el formulario aquí**
echo '<form action="../logica/procesar_reserva.php" method="POST" class="space-y-4">';

// **Campo de selección de fecha de reserva**
echo '<div class="mb-6">';
echo '<label for="fecha_reserva" class="block text-lg font-semibold text-gray-700">Selecciona la Fecha de Reserva:</label>';
echo '<input type="date" id="fecha_reserva" name="fecha_reserva" class="mt-2 px-4 py-2 border rounded-lg text-gray-800" min="' . date('Y-m-d') . '" required>';
echo '</div>';

echo '<div class="flex flex-wrap gap-2">';
for ($hora = $hora_apertura; $hora < $hora_cierre; $hora = strtotime("+1 hour", $hora)) {
    $hora_inicio = date("H:i", $hora);
    $hora_fin = date("H:i", strtotime("+1 hour", $hora));
    $valor = "$hora_inicio - $hora_fin";

    echo '<label class="cursor-pointer flex items-center gap-2 px-4 py-2 rounded bg-blue-500 text-white transition duration-200 border border-gray-300">';
    echo '<input type="radio" name="horario" value="' . htmlspecialchars($valor) . '" class="hidden peer" required>';
    echo '<span class="peer-checked:bg-gray-600 peer-checked:text-white px-2 py-1 rounded transition duration-200">' . htmlspecialchars($valor) . '</span>';
    echo '</label>';
}
echo '</div>';

// ID de la cancha como campo oculto
echo '<input type="hidden" name="id_cancha" value="' . htmlspecialchars($id_cancha) . '">';

// Botón de envío
echo '<button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Reservar Horario</button>';
echo '</form>';
?>