<?php
session_start();
include '../logica/detalles_cancha.php';

if (!isset($_SESSION['cedula_usuario']) || $_SESSION['tipo_usuario'] != 'cliente') {
    header("Location: ../index.php");
    exit();
}



// Obtener calificación promedio y comentarios
$id_cancha = $fila['id_cancha'];
$promedio_cancha = "Sin calificaciones";
$comentarios = [];

// Obtener promedio de calificación
$sql_prom = "
    SELECT AVG(c.puntuacion) AS promedio
    FROM calificacion c
    JOIN reserva r ON c.id_reserva = r.id_reserva
    WHERE r.id_cancha = ?
";

$stmt = $conn->prepare($sql_prom);
$stmt->bind_param("s", $id_cancha);
$stmt->execute();
$result_prom = $stmt->get_result();

if ($row = $result_prom->fetch_assoc()) {
    if (!is_null($row['promedio'])) {
        $promedio_cancha = number_format($row['promedio'], 1) . " ⭐";
    }
}
$stmt->close();

// Obtener comentarios
$sql_com = "
    SELECT c.puntuacion, c.comentario, c.fecha, p.primer_nombre
    FROM calificacion c
    JOIN reserva r ON c.id_reserva = r.id_reserva
    JOIN persona p ON r.cedula_persona = p.cedula_persona
    WHERE r.id_cancha = ?
    ORDER BY c.fecha DESC
    LIMIT 5
";
$stmt2 = $conn->prepare($sql_com);
$stmt2->bind_param("s", $id_cancha);
$stmt2->execute();
$result_com = $stmt2->get_result();

while ($com = $result_com->fetch_assoc()) {
    $comentarios[] = $com;
}
$stmt2->close();
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
        <a href="../vistas/home.php" class="text-2xl font-bold text-gray-800">SportRent</a>
        <div class="space-x-4">
            <a href="../vistas/home.php" class="text-gray-600 hover:text-gray-900">Regresar</a>
            <a href="../vistas/vista_reservas.php" class="text-gray-600 hover:text-gray-900">Mis Reservas</a>
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

                <div class="flex items-center justify-between mb-4">
                    <p class="text-3xl font-bold text-green-600">
                        $<?php echo number_format($fila['valor_hora'] ?? 0, 0, ',', '.'); ?>/hora
                    </p>
                    
                    <div class="flex items-center bg-yellow-100 px-3 py-1 rounded-full">
                        <span class="text-yellow-800 font-semibold mr-1"><?php echo $promedio_cancha; ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>

                <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <?php echo htmlspecialchars($fila['tipo_cancha'] ?? 'Categoría'); ?>
                </span>

                <h3 class="text-xl font-semibold text-gray-800 mb-2">Descripción:</h3>
                <p class="text-gray-700 leading-relaxed mb-6">
                    <?php echo htmlspecialchars($fila['descripcion'] ?? 'Aquí va una descripción detallada de la cancha...'); ?>
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
                        <?php echo htmlspecialchars($fila['direccion_cancha'] ?? 'Dirección completa de la cancha...'); ?>
                    </p>
                </div>
            </div>

            <div>
                <?php include '../logica/iterarhoras.php'; ?>
            </div>
        </div>
    </div>

    <!-- Sección de Comentarios -->
    <div class="mt-12 bg-white rounded-xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Calificaciones y Comentarios</h2>
        
        <?php if (empty($comentarios)): ?>
            <p class="text-gray-600">Esta cancha aún no tiene calificaciones.</p>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="bg-blue-100 text-blue-800 rounded-full w-10 h-10 flex items-center justify-center font-bold">
                                    <?php echo substr($comentario['primer_nombre'], 0, 1); ?>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($comentario['primer_nombre']); ?></h4>
                                    <p class="text-sm text-gray-500"><?php echo date('d/m/Y', strtotime($comentario['fecha'])); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center bg-yellow-100 px-2 py-1 rounded">
                                <span class="text-yellow-800 font-medium mr-1"><?php echo $comentario['puntuacion']; ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-700 mt-2 pl-13"><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-gray-800 text-white py-6 mt-8">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; 2025 SportRent. Todos los derechos reservados.</p>
    </div>
</footer>

<script type="module" src="../logicacalendario.js"></script> 
<script type="module" src="../logica_mensaje.js"></script>

<div id="toast" class="fixed top-5 right-5 z-50 hidden bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300">
    Reserva guardada exitosamente.
</div>

</body>
</html>