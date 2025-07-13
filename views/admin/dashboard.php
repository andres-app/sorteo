<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sorteo Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body { height: 100%; margin: 0; }
        body { min-height: 100dvh; }
        .grid-sorteo {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.0rem;
            width: 78vw;
            max-width: 420px;
            margin: 0 auto;
        }
        .num-sorteo {
            font-size: 1.35rem;
            height: 46px;
            min-height: 46px;
            max-height: 46px;
            min-width: 46px;
            max-width: 60px;
            padding: 0 3px;
            line-height: 44px;
            border-radius: 16px;
        }
        @media (max-width: 600px) {
            .grid-sorteo {
                max-width: 99vw;
                gap: 1.3rem;
            }
            .num-sorteo {
                font-size: 1rem;
                height: 34px;
                min-height: 34px;
                max-height: 34px;
                min-width: 30px;
                max-width: 34px;
                border-radius: 8px;
                line-height: 32px;
            }
        }
    </style>
</head>

<body class="bg-red-50" style="min-height: 100dvh;">

    <!-- Header fijo superior -->
    <header class="w-full sticky top-0 z-30 bg-white shadow-md">
        <div class="max-w-lg mx-auto flex items-center justify-between py-2 px-3">
            <div class="flex items-center gap-2">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <span class="font-bold text-lg md:text-2xl text-red-700 tracking-wide">Administrador</span>
            </div>
            <a href="?logout=1"
                class="text-sm bg-red-100 text-red-600 px-3 py-1 rounded-md font-semibold shadow hover:bg-red-200 transition">
                Cerrar sesión
            </a>
        </div>
    </header>

    <?php if (isset($_GET['wa_url'])): ?>
        <script>
            window.open("<?= $_GET['wa_url'] ?>", "_blank");
            if (window.history.replaceState) {
                const url = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, url);
            }
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['edit']) && $_GET['edit'] === 'ok'): ?>
        <div id="toast-edit"
            class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 transition">
            <span class="font-semibold">¡Datos actualizados correctamente!</span>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('toast-edit').style.display = 'none';
                if (window.history.replaceState) {
                    const url = window.location.href.split('?')[0];
                    window.history.replaceState({}, document.title, url);
                }
            }, 1800);
        </script>
    <?php endif; ?>


    <div class="w-full flex flex-col items-center justify-center" style="height: 100dvh;">
        <div class="grid-sorteo my-2">
            <?php foreach ($numeros as $n): ?>
                <div class="num-sorteo
                    <?= $n['estado'] == 'vendido'
                        ? 'bg-green-400 text-white line-through cursor-pointer relative opacity-90'
                        : 'bg-white cursor-pointer hover:bg-yellow-200'; ?>
                    rounded-xl shadow text-center font-bold select-none transition flex items-center justify-center"
                    onclick="abrirModal(<?= htmlspecialchars(json_encode($n), ENT_QUOTES, 'UTF-8') ?>)">
                    <span><?= $n['numero'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal para registrar/editar número -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-xs sm:max-w-sm mx-2 relative">
            <button type="button" onclick="cerrarModal()"
                class="absolute top-2 right-2 text-gray-400 text-2xl">&times;</button>
            <form id="form-modal" method="post" action="/sorteo/public/registrar" class="flex flex-col gap-4"
                onsubmit="return validarCelular();">
                <input type="hidden" name="numero" id="modal-numero">
                <div id="modal-form-fields">
                    <!-- Aquí van los campos generados dinámicamente -->
                </div>
                <button id="modal-btn" type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 transition text-white py-2 rounded-lg font-semibold text-base shadow">
                    <!-- El texto del botón se rellena por JS -->
                </button>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(num) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal-numero').value = num.numero;
            var whatsappValor = (num.comprador_whatsapp && num.comprador_whatsapp.length > 2)
                ? num.comprador_whatsapp.substring(2)
                : '';
            var html = '';
            html += `
                <div class="flex flex-col items-center mb-2">
                    <div class="mb-2">
                        <svg class="w-11 h-11 text-red-500 drop-shadow animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path stroke="currentColor" stroke-width="2" d="M4 20c0-3 8-3 8-3s8 0 8 3v1H4z"/>
                        </svg>
                    </div>
                    <div class="text-lg font-bold text-red-700 mb-1">Número <span class="text-black" id="modal-numero-label">${num.numero}</span></div>
                    <span class="text-xs text-gray-500 mb-1">${num.estado === 'vendido' ? 'Editar datos del participante' : 'Registrar nuevo participante'}</span>
                </div>
            `;
            if (num.estado === 'vendido') {
                html += '<input type="hidden" name="editar" value="1">';
            }
            html += `
                <label class="block mb-1 text-gray-600 font-medium" for="nombre">Nombre del comprador</label>
                <input
                    type="text"
                    name="nombre"
                    id="input-nombre"
                    placeholder="Nombre completo"
                    class="border border-gray-300 focus:ring-2 focus:ring-red-200 rounded-lg px-4 py-2 w-full text-base outline-none mb-3 transition"
                    value="${num.comprador_nombre || ''}"
                    required
                    autofocus
                >
            `;
            html += `
                <label class="block mb-1 text-gray-600 font-medium" for="input-whatsapp">Celular WhatsApp</label>
                <div class="flex items-center gap-1 mb-3">
                    <span class="bg-gray-100 px-3 py-2 rounded-l border border-gray-300 text-gray-700 font-semibold select-none">+51</span>
                    <input type="text"
                        name="whatsapp"
                        id="input-whatsapp"
                        maxlength="9" minlength="9" pattern="[0-9]{9}"
                        placeholder="9XXXXXXXX"
                        class="border border-gray-300 focus:ring-2 focus:ring-red-200 rounded-r px-3 py-2 w-full text-base outline-none transition"
                        value="${whatsappValor}"
                        required
                        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9)"
                        autocomplete="off"
                    >
                </div>
                <p class="text-xs text-gray-400 mb-2">* Solo números peruanos (9 dígitos, empieza con 9)</p>
            `;
            document.getElementById('modal-form-fields').innerHTML = html;
            document.getElementById('modal-btn').textContent = num.estado === 'vendido' ? 'Editar datos' : 'Registrar venta';
        }

        function cerrarModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal-form-fields').innerHTML = '';
            document.getElementById('modal-numero').value = '';
            // document.getElementById('modal-numero-label').textContent = '';
        }

        function validarCelular() {
            var cel = document.getElementById('input-whatsapp').value.trim();
            if (!/^[9][0-9]{8}$/.test(cel)) {
                alert("Ingrese un celular peruano válido (9 dígitos, empieza con 9)");
                return false;
            }
            return true;
        }

        window.onkeydown = function (e) { if (e.key === "Escape") cerrarModal(); }
    </script>
</body>

</html>
