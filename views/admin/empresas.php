<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Empresas | Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-red-50 via-yellow-50 to-green-50 min-h-screen py-8">

    <div class="max-w-4xl mx-auto p-5 bg-white shadow-2xl rounded-3xl border">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-2 mb-6 border-b pb-3">
            <div>
                <h1 class="text-2xl font-bold text-red-700 mb-1">Empresas Registradas</h1>
                <span class="text-sm text-gray-500">Administra todas tus empresas SaaS aquí</span>
            </div>
            <a href="/sorteo/public/admin"
                class="text-sm px-4 py-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition font-semibold shadow-sm">←
                Volver al Panel</a>
        </div>

        <!-- Mensaje de error SIEMPRE -->
        <?php if (!empty($_GET['error']) && $_GET['error'] == 'noborrar'): ?>
            <div id="alerta-error"
                class="mb-5 p-3 rounded-lg bg-red-100 text-red-800 font-bold border border-red-300 text-center shadow animate-pulse">
                No puedes eliminar una empresa con sorteos activos.<br>
                Elimina primero sus sorteos.
            </div>
        <?php endif; ?>

        <!-- Mensajes de éxito SOLO si corresponde -->
        <?php if (!empty($_GET['ok'])): ?>
            <?php if ($_GET['ok'] == '1'): ?>
                <div id="alerta-ok"
                    class="mb-5 p-3 rounded-lg bg-green-100 text-green-800 font-bold border border-green-300 text-center shadow animate-pulse">
                    Empresa agregada correctamente
                </div>
            <?php elseif ($_GET['ok'] == 'edit'): ?>
                <div id="alerta-edit"
                    class="mb-5 p-3 rounded-lg bg-blue-100 text-blue-800 font-bold border border-blue-300 text-center shadow animate-pulse">
                    Empresa editada correctamente
                </div>
            <?php elseif ($_GET['ok'] == 'del'): ?>
                <div id="alerta-del"
                    class="mb-5 p-3 rounded-lg bg-red-100 text-red-800 font-bold border border-red-300 text-center shadow animate-pulse">
                    Empresa eliminada correctamente
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Tabla de empresas -->
        <div class="overflow-x-auto rounded-xl shadow mb-6">
            <table class="w-full text-sm border border-gray-200 rounded-xl">
                <thead>
                    <tr class="bg-red-100 text-red-700 text-left">
                        <th class="p-3 font-bold">ID</th>
                        <th class="p-3 font-bold">Nombre</th>
                        <th class="p-3 font-bold">RUC</th>
                        <th class="p-3 font-bold">Dirección</th>
                        <th class="p-3 font-bold">Teléfono</th>
                        <th class="p-3 font-bold">Email</th>
                        <th class="p-3 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($empresas)): ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-400">No hay empresas registradas aún.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($empresas as $empresa): ?>
                            <tr class="border-b hover:bg-yellow-50 transition">
                                <td class="p-2"><?= htmlspecialchars($empresa['id']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($empresa['nombre']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($empresa['ruc']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($empresa['direccion']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($empresa['telefono']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($empresa['email']) ?></td>
                                <td class="p-2 flex gap-2 justify-center">
                                    <a href="/sorteo/public/editarEmpresa?id=<?= $empresa['id'] ?>"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 shadow text-xs font-semibold flex items-center gap-1">Editar</a>
                                    <a href="/sorteo/public/eliminarEmpresa?id=<?= $empresa['id'] ?>"
                                        class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 shadow text-xs font-semibold flex items-center gap-1"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta empresa?')">Eliminar</a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulario: Agregar empresa -->
        <div class="bg-gray-50 p-6 rounded-xl shadow">
            <h2 class="text-xl font-bold text-green-700 mb-2">Agregar nueva empresa</h2>
            <form method="post" action="/sorteo/public/crearEmpresa" class="grid md:grid-cols-2 gap-4">
                <input type="text" name="nombre" placeholder="Nombre de empresa" required
                    class="p-2 rounded border border-gray-300">
                <input type="text" name="ruc" placeholder="RUC" class="p-2 rounded border border-gray-300">
                <input type="text" name="direccion" placeholder="Dirección" class="p-2 rounded border border-gray-300">
                <input type="text" name="telefono" placeholder="Teléfono" class="p-2 rounded border border-gray-300">
                <input type="email" name="email" placeholder="Email"
                    class="p-2 rounded border border-gray-300 md:col-span-2">
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold shadow md:col-span-2">Agregar
                    empresa</button>
            </form>
        </div>
    </div>

    <!-- Modal Editar (simulado, falta lógica backend) -->
    <div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md relative">
            <button onclick="cerrarModalEditar()" class="absolute top-3 right-4 text-gray-400 text-2xl">&times;</button>
            <h3 class="text-xl font-bold text-blue-700 mb-3">Editar empresa (Demo)</h3>
            <div class="text-gray-500 mb-3">Aquí iría el formulario real de edición.</div>
            <button onclick="cerrarModalEditar()"
                class="bg-blue-600 px-6 py-2 rounded text-white font-bold hover:bg-blue-700 mt-2">OK</button>
        </div>
    </div>
    <script>
        function editarEmpresa(id) {
            document.getElementById('modalEditar').classList.remove('hidden');
            // Aquí puedes precargar datos, hacer fetch o mostrar form real.
        }
        function cerrarModalEditar() {
            document.getElementById('modalEditar').classList.add('hidden');
        }
        function eliminarEmpresa(id, nombre) {
            if (confirm('¿Seguro qu deseas eliminar la empresa "' + nombre + '"? (Función demo, debes programar el backend)')) {
                alert('Aquí deberías llamar a tu endpoint para eliminar. Por ahora es solo demo.');
            }
        }

        setTimeout(() => {
            const alerts = ['alerta-ok', 'alerta-edit', 'alerta-del', 'alerta-error'];
            alerts.forEach(function (id) {
                var el = document.getElementById(id);
                if (el) {
                    el.style.transition = 'opacity 0.6s';
                    el.style.opacity = 0;
                    setTimeout(() => { el.style.display = 'none'; }, 600);
                }
            });
        }, 3500); // 3.5 segundos
    </script>
</body>

</html>