<?php include '../logica/cargardatos_cliente.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil del Cliente - SportRent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100 font-[Inter] flex flex-col">

    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="../vistas/home.php" class="text-2xl font-bold text-gray-800">SportRent</a>
            <div class="space-x-4">
                <a href="./home.php" class="text-gray-600 hover:text-gray-900">Regresar</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="bg-white shadow-xl rounded-xl p-8 max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Perfil del Cliente</h1>
              <?php if (isset($_GET['error'])): ?>
                 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
             <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="../logica/actualizar_cliente.php" method="POST" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Primer nombre</label>
                        <input type="text" name="primer_nombre" required 
                               value="<?php echo htmlspecialchars($cliente['primer_nombre'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Segundo nombre</label>
                        <input type="text" name="segundo_nombre" 
                               value="<?php echo htmlspecialchars($cliente['segundo_nombre'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Primer apellido</label>
                        <input type="text" name="primer_apellido" required 
                               value="<?php echo htmlspecialchars($cliente['primer_apellido'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Segundo apellido</label>
                        <input type="text" name="segundo_apellido" required 
                               value="<?php echo htmlspecialchars($cliente['segundo_apellido'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input type="email" name="correo" required 
                               value="<?php echo htmlspecialchars($cliente['correo'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="tel" name="telefono" required 
                               value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion" required 
                               value="<?php echo htmlspecialchars($cliente['direccion'] ?? ''); ?>" 
                               class="mt-1 block w-full bg-gray-100 border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 SportRent. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
