<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empresa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-8">
    <form method="post" action="/sorteo/public/editarEmpresa" class="bg-white p-8 rounded-xl shadow-xl max-w-md w-full space-y-4 border">
        <h2 class="text-xl font-bold text-blue-700 mb-4">Editar Empresa</h2>
        <input type="hidden" name="id" value="<?= htmlspecialchars($empresa['id']) ?>">
        <input type="text" name="nombre" placeholder="Nombre de empresa" required value="<?= htmlspecialchars($empresa['nombre']) ?>" class="w-full p-2 border rounded">
        <input type="text" name="ruc" placeholder="RUC" value="<?= htmlspecialchars($empresa['ruc']) ?>" class="w-full p-2 border rounded">
        <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($empresa['direccion']) ?>" class="w-full p-2 border rounded">
        <input type="text" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($empresa['telefono']) ?>" class="w-full p-2 border rounded">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($empresa['email']) ?>" class="w-full p-2 border rounded">
        <div class="flex justify-between mt-4">
            <a href="/sorteo/public/empresas" class="text-gray-500 px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold shadow">Guardar cambios</button>
        </div>
    </form>
</body>
</html>
