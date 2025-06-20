<?php
session_start();
include '../logica/detalles_cancha.php';

if ($_SESSION['tipo_usuario'] === 'cliente') {
     header("Location: ../index.php");
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Cancha - SportRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilodetalles.css">
</head>
<body class="min-h-screen flex flex-col">


<nav class="bg-white shadow-md py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="../vistas/proveedor.php" class="text-2xl font-bold text-gray-800">SportRent</a>
        <div class="space-x-4">
            <a href="../vistas/proveedor.php" class="text-gray-600 hover:text-gray-900">Regresar</a>
            <a href="#" class="text-gray-600 hover:text-gray-900">Mis Reservas</a>
        </div>
    </div>
</nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden md:flex card-shadow">
            <div class="md:w-1/2">
                <img class="w-full h-96 object-cover" 
                     src="data:image/jpeg;base64,<?php echo base64_encode($fila['foto']); ?>" 
                     alt="Imagen de la Cancha: <?php echo htmlspecialchars($fila['nombre_cancha'] ?? 'Nombre de la Cancha'); ?>">
            </div>

            <div class="md:w-1/2 p-8 flex flex-col justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4"><?php echo htmlspecialchars($fila['nombre_cancha'] ?? 'Nombre de la Cancha'); ?></h1>

                    <p class="text-3xl font-bold text-green-600 mb-4">
                        $<?php echo number_format($fila['valor_hora'] ?? 0, 0, ',', '.'); ?>/hora
                    </p>

                    <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 mb-6">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <?php echo htmlspecialchars($fila['tipo_cancha'] ?? 'Categoría'); ?>
                    </span>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Descripción:</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        <?php echo htmlspecialchars($fila['descripcion'] ?? 'Aquí va una descripción detallada de la cancha, sus características, el tipo de superficie, si cuenta con iluminación, vestuarios, etc. Proporciona toda la información relevante para que los usuarios puedan tomar una decisión informada.'); ?>
                    </p>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Horario de Operación:</h3>
                        <p class="text-gray-700">
                            <span class="font-medium">Apertura:</span> <?php echo htmlspecialchars($fila['hora_apertura'] ?? '00:00'); ?>
                        </p>
                        <p class="text-gray-700">
                            <span class="font-medium">Cierre:</span> <?php echo htmlspecialchars($fila['hora_cierre'] ?? '00:00'); ?>
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Dirección:</h3>
                        <p class="text-gray-700">
                            <?php echo htmlspecialchars($fila['direccion_cancha'] ?? 'Dirección completa de la cancha, incluyendo ciudad y país.'); ?>
                        </p>
                    </div>
                </div>

                <div>
                    <?php 

                    include '../logica/iterarhorasproveedor.php'; 
                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 SportRent. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script  type="module"  src="../logicacalendario.js"></script> 
    <script  type="module"  src="../logica_mensaje.js"></script>

    <div id="toast" class="fixed top-5 right-5 z-50 hidden bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300">
        Reserva guardada exitosamente.
    </div>

</body>
</html>