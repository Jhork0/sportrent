<?php
include '../logica/autentificar_usuario.php'; 
include '../logica/conectar.php';
include '../logica/iterar_reservas.php';
include '../logica/obtener_nombre_cancha.php'; 

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
            <a href="../vistas/home.php" class="text-2xl font-bold text-gray-800">SportRent</a>
            <div class="space-x-4">
                <a href="../vistas/home.php" class="text-gray-600 hover:text-gray-900">Regresar</a>
                <a href="." class="text-gray-600 hover:text-gray-900">Mis Reservas</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Mis Reservas</h1>

        <form method="get" class="mb-6 bg-white rounded-lg p-4 shadow-md">
    <h2 class="text-xl font-semibold mb-2 text-gray-700">Filtrar por estado:</h2>
    <div class="flex flex-wrap gap-4">
        <?php
        $estados = ['caducada', 'cancelada', 'pendiente', 'transaccion', 'completada', 'finalizada', 'calificado' ];
        $estados_seleccionados = isset($_GET['estado']) ? $_GET['estado'] : [];

        foreach ($estados as $estado) {
            $checked = in_array($estado, $estados_seleccionados) ? 'checked' : '';
            echo '<label class="inline-flex items-center space-x-2">';
            echo '<input type="checkbox" name="estado[]" value="' . $estado . '" class="form-checkbox h-5 w-5 text-blue-600" ' . $checked . '>';
            echo '<span class="text-gray-800 capitalize">' . $estado . '</span>';
            echo '</label>';
        }
        ?>
    </div>
    <div class="mt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
            Aplicar Filtros
        </button>
        <a href="?" class="ml-4 text-blue-600 hover:underline">Limpiar</a>
    </div>
</form>


<?php if ($result->num_rows > 0): ?>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php while ($fila = $result->fetch_assoc()): 
            $nombre_cancha = obtenerNombreCancha($conn, $fila['id_cancha']);
        ?>
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                    <?php echo htmlspecialchars($nombre_cancha); ?>
                </h2>
                <div class="space-y-2 mb-4">
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
                                           ($fila['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' :  
                                           ($fila['estado'] === 'completada' ? 'bg-blue-100 text-blue-800' :  
                                            ($fila['estado'] === 'finalizada' ? 'bg-purple-100 text-purple-800' :  
                                            ($fila['estado'] === 'calificado' ? 'bg-pink-100 text-pink-800' :  
                                          ($fila['estado'] === 'transaccion' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))))); ?>">
                                     <?php echo ucfirst(htmlspecialchars($fila['estado'])); ?>
                        </span>
                    </p>
                </div>
                
                <!-- Botón para cancelar reserva (solo si el estado lo permite) -->
                <?php if ($fila['estado'] === 'confirmada' || $fila['estado'] === 'pendiente' ): ?>
                    <form action="../logica/cancelar_reserva_usuario.php" method="post" class="mt-4">
                        <input type="hidden" name="id_reserva" value="<?php echo $fila['id_reserva']; ?>">
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-colors"
                                onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                            Cancelar Reserva
                        </button>
                    </form>
                <?php endif; ?>
                <?php if ($fila['estado'] === 'confirmada'): ?>
                    <form action="../vistas/pagar_usuario.php" method="post" class="mt-4">
                        <input type="hidden" name="id_reserva" value="<?php echo $fila['id_reserva']; ?>">
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition-colors"
                               onclick="return confirm('¿Estás seguro de que deseas pagar esta reserva?');" >
                            Pagar Reserva
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($fila['estado'] === 'transaccion'): ?>
                    <form action="../vistas/pagar_usuario.php" method="post" class="mt-4">
                        <input type="hidden" name="id_reserva" value="<?php echo $fila['id_reserva']; ?>">
                        <button type="submit" 
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition-colors"
                                >
                            Ver transaccion
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($fila['estado'] === 'finalizada'): ?>
                    <form action="../logica/calificar_cancha.php" method="post" class="mt-4">
                        <input type="hidden" name="id_reserva" value="<?php echo $fila['id_reserva']; ?>">
                        <button type="submit" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition-colors"
                                >
                            Calificar cancha
                        </button>
                    </form>
                <?php endif; ?>


                   <?php if ($fila['estado'] === 'finalizada' || $fila['estado'] === 'calificado'): ?>
    <div class="mt-4">
        <a href="../vistas/plantilla.php?id=<?php echo htmlspecialchars($fila['id_cancha']); ?>" 
           class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition-colors inline-block text-center"
           >
            Volver a arrendar
        </a>
    </div>
<?php endif; ?>
            </div>


            
        <?php endwhile; ?>
    </div>

        <?php else: ?>
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <p class="text-gray-600 mb-4">No tienes reservas registradas.</p>
                <a href="../vistas/home.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
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