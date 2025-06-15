<?php
// calificar_usuario.php
if (!isset($_POST['id_reserva'])) {
    die('ID de reserva no proporcionado');
}

$id_reserva = $_POST['id_reserva'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Calificar al Usuario</h1>

        <form id="calificacionForm" action="../logica/guardarcalificacion.php" method="post" class="space-y-4">
            <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($id_reserva); ?>">

            <div>
                <label for="puntuacion" class="block text-gray-700 font-medium mb-2">Puntuación (1 a 5):</label>
                <select name="puntuacion" id="puntuacion" required class="w-full border rounded px-3 py-2">
                    <option value="">Selecciona una opción</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> estrella<?php echo $i > 1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <label for="comentario" class="block text-gray-700 font-medium mb-2">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" class="w-full border rounded px-3 py-2" placeholder="Escribe tu opinión..."></textarea>
                <p id="errorMsg" class="text-red-600 mt-2 hidden"></p>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Enviar Calificación
            </button>
        </form>
    </div>

    <script>
    // Lista de palabras prohibidas (puedes agregar/quitar las que consideres)
    const palabrasProhibidas = [
        "tonto", "idiota", "estúpido", "imbécil", "malo", "grosero", "maldito", "pendejo", "cabron", "puto"
        // Agrega más palabras según tus necesidades
    ];

    document.getElementById('calificacionForm').addEventListener('submit', function(event) {
        const comentario = document.getElementById('comentario').value.toLowerCase();
        let contienePalabraProhibida = false;

        for (const palabra of palabrasProhibidas) {
            // Busca la palabra prohibida como palabra completa (no como parte de otra palabra)
            const regex = new RegExp("\\b" + palabra + "\\b", "i");
            if (regex.test(comentario)) {
                contienePalabraProhibida = true;
                break;
            }
        }

        if (contienePalabraProhibida) {
            event.preventDefault();
            document.getElementById('errorMsg').textContent = "Tu comentario contiene palabras no permitidas. Por favor, modifícalo.";
            document.getElementById('errorMsg').classList.remove('hidden');
        } else {
            document.getElementById('errorMsg').classList.add('hidden');
        }
    });
    </script>
</body>
</html>
