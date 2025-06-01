<?php
// Convertir las horas en formato de tiempo
$hora_apertura = strtotime($fila['hora_apertura'] ?? '06:00');
$hora_cierre = strtotime($fila['hora_cierre'] ?? '13:00');

// Si la cancha funciona las 24 horas, establecemos el rango completo
if ($fila['hora_apertura'] === '00:00' && $fila['hora_cierre'] === '00:00') {
    $hora_apertura = strtotime('00:00');
    $hora_cierre = strtotime('23:59'); // Se usa 23:59 para que incluya el último checkbox correctamente
}

// Iterar y generar los botones estilo radio
echo '<form action="procesar_reserva.php" method="POST" class="space-y-4">';
echo '<div class="flex flex-wrap gap-2">';

for ($hora = $hora_apertura; $hora < $hora_cierre; $hora = strtotime("+1 hour", $hora)) {
    $hora_inicio = date("H:i", $hora);
    $hora_fin = date("H:i", strtotime("+1 hour", $hora));
    $valor = "$hora_inicio - $hora_fin";

    echo '<label class="cursor-pointer flex items-center gap-2 px-4 py-2 rounded bg-blue-500 text-white transition duration-200 border border-gray-300">';
    echo '<input type="radio" name="horario" value="'.htmlspecialchars($valor).'" class="hidden peer" required>';
    echo '<span class="peer-checked:bg-gray-600 peer-checked:text-white px-2 py-1 rounded transition duration-200">'.htmlspecialchars($valor).'</span>';
    echo '</label>';
}

echo '</div>';

// Puedes agregar el ID de la cancha como campo oculto
echo '<input type="hidden" name="id_cancha" value="'.htmlspecialchars($id_cancha).'">';

echo '<button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">';
echo 'Reservar Horario';
echo '</button>';

echo '</form>';




?>