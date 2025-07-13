<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Admin - Sorteo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-200 via-white to-red-100">
    <div class="w-full max-w-xs">
        <form method="post" class="bg-white shadow-xl rounded-2xl px-8 py-8 relative">
            <div class="flex flex-col items-center mb-6">
                <h2 class="text-2xl font-bold text-red-600 mb-1 text-center">Panel de Administración</h2>
                <span class="text-sm text-gray-500 mb-2">Acceso restringido</span>
            </div>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-300 text-red-700 p-2 mb-3 rounded text-center shadow"><?= $error ?></div>
            <?php endif; ?>
            <label class="block mb-2 text-gray-600 font-medium" for="pass">Contraseña</label>
            <input type="password" name="pass" id="pass" placeholder="••••••••"
                class="border border-gray-300 focus:ring-2 focus:ring-red-200 rounded-lg px-4 py-2 w-full text-lg outline-none mb-5 transition"
                required autocomplete="current-password" autofocus>
            <button type="submit" name="login"
                class="w-full bg-red-600 hover:bg-red-700 transition text-white py-2 rounded-lg font-semibold text-lg shadow">
                Ingresar
            </button>
        </form>
        <div class="text-xs text-center text-gray-400 mt-6">
            &copy; <?= date("Y") ?> Sorteo Pishcota-Lover. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
