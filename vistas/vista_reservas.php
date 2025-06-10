<?php
include '../logica/conectar.php';
include '../logica/obtener_nombre_cancha.php'; // El nuevo archivo que creamos
include '../logica/ruta.php';
include '../logica/autentificar_usuario.php'; 
include '../logica/iterar_reservas.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - SportRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilodetalles.css">
</head>
<body class="min-h-screen flex flex-col">

    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="<?php echo $rutaHome; ?>" class="text-2xl font-bold text-gray-800">SportRent</a>
            <div class="space-x-4">
                <a href="<?php echo $rutaHome; ?>" class="text-gray-600 hover:text-gray-900">Regresar</a>
                <a href="#" class="text-gray-600 hover:text-gray-900">Mis Reservas</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Mis Reservas</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php while ($fila = $result->fetch_assoc()): 
                    $nombre_cancha = obtenerNombreCancha($conn, $fila['id_cancha']);
                ?>
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                            <?php echo htmlspecialchars($nombre_cancha); ?>
                        </h2>
                        <div class="space-y-2">
                            <p class="text-gray-600">
                                <span class="font-medium">Fecha:</span> 
                                <?php echo date('d/m/Y', strtotime($fila['fecha_reserva'])); ?>
                            </p>
                            <p class="text-gray-600">
                                <span class="font-medium">Horario:</span> 
                                <?php echo htmlspecialchars($fila['hora_inicio']); ?> - <?php echo htmlspecialchars($fila['hora_final']); ?>
                            </p>
                            <p class="text-gray-600">
                                <span class="font-medium">Estado:</span> 
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    <?php echo $fila['estado'] === 'confirmada' ? 'bg-green-100 text-green-800' : 
                                           ($fila['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo ucfirst(htmlspecialchars($fila['estado'])); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <p class="text-gray-600 mb-4">No tienes reservas registradas.</p>
                <a href="<?php echo $rutaHome; ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    Explorar Canchas
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 SportRent. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>