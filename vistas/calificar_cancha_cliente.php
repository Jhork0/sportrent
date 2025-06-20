<?php
session_start(); // Iniciar sesión

// Verificar si el usuario ha iniciado sesión y es un cliente
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: ../index.php");
    exit;
}

// Verificar que exista id_reserva en sesión
if (!isset($_SESSION['id_reserva'])) {
    die("El ID de la reserva no llegó correctamente.");
}

$id_reserva = htmlspecialchars($_SESSION['id_reserva']); // Sanitiza para evitar inyección de código
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificar Cancha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Calificar la cancha</h2>

        <form action="../logica/procesar_calificacion_cancha_cliente.php" method="post" class="space-y-4">
            <!-- ID de la reserva -->
            <input type="hidden" name="id_reserva" value="<?php echo $id_reserva; ?>">

            <!-- Puntuación -->
            <div>
                <label for="puntuacion" class="block text-sm font-medium text-gray-700">Puntuación (1 a 5):</label>
                <select name="puntuacion" id="puntuacion" required
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2">
                    <option value="">Seleccionar</option>
                    <option value="1">1 - Muy mala</option>
                    <option value="2">2 - Mala</option>
                    <option value="3">3 - Regular</option>
                    <option value="4">4 - Buena</option>
                    <option value="5">5 - Excelente</option>
                </select>
            </div>

            <!-- Comentario -->
            <div>
                <label for="comentario" class="block text-sm font-medium text-gray-700">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" required
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2 resize-y"
                    placeholder="Escribe tu opinión..."></textarea>
            </div>

            <!-- Botón enviar -->
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                Enviar calificación
            </button>
        </form>
    </div>

</body>
</html>