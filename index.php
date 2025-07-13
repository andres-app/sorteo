<?php
require 'config.php';

$sorteo_id = 1;
$stmt = $conn->prepare("SELECT * FROM numeros WHERE sorteo_id = ?");
$stmt->execute([$sorteo_id]);
$numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// WhatsApp del admin
$whatsapp_admin = '51986634352'; // Tu número real
$precio_numero = 8; // Precio real de cada número
$url_img_qr = 'https://suministrosperu.com/wp-content/uploads/2016/01/YAPE-QR.png'; // Imagen de tu QR Yape
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sorteo Pishcota-Lover</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            min-height: 100dvh;
        }

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

<body class="bg-red-50 flex items-center justify-center" style="height: 100dvh;">
    <div id="main-content" class="flex flex-col items-center justify-center w-full" style="height: 100dvh;">
        <h1 class="text-xl md:text-3xl font-bold mb-2 text-center text-red-700 drop-shadow">
            SORTEO PISHCOTA-LOVER
        </h1>
        <p class="mb-2 text-xs text-center">Selecciona tu número, paga con Yape y envía tu comprobante</p>
        <div class="grid-sorteo my-2">
            <?php foreach ($numeros as $n): ?>
                <?php if ($n['estado'] == 'vendido'): ?>
                    <div
                        class="num-sorteo bg-green-400 text-white line-through cursor-not-allowed select-none transition flex items-center justify-center opacity-70 font-bold shadow">
                        <?= $n['numero'] ?>
                    </div>
                <?php else: ?>
                    <a href="#" onclick="mostrarYape(<?= $n['numero'] ?>); return false;"
                        class="num-sorteo bg-white cursor-pointer hover:bg-yellow-200 rounded-lg shadow text-center font-bold select-none transition flex items-center justify-center">
                        <?= $n['numero'] ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Yape -->
    <!-- Modal Yape -->
    <div id="modalYape"
        class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center transition-all duration-300 hidden">
        <div
            class="relative bg-white rounded-2xl shadow-2xl px-5 py-6 w-full max-w-xs sm:max-w-sm mx-2 flex flex-col items-center animate-fade-in">
            <!-- Botón cerrar -->
            <button type="button" onclick="cerrarYape()"
                class="absolute top-2 right-2 text-gray-300 hover:text-red-500 text-2xl transition focus:outline-none">&times;</button>

            <!-- Icono animado -->
            <div class="mb-1">
                <svg class="w-12 h-12 text-green-500 animate-pulse" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <rect x="5" y="6" width="14" height="12" rx="4" fill="none" stroke="currentColor"
                        stroke-width="2" />
                    <path stroke="currentColor" stroke-width="2" d="M8 10h8M8 14h5" />
                </svg>
            </div>
            <div class="text-xl font-bold mb-1 text-center text-green-700">Confirma tu pago por Yape</div>
            <div class="mb-1 text-center text-base font-semibold">
                Número elegido: <span id="yape-numero" class="font-bold text-blue-700"></span>
            </div>
            <div class="mb-1 text-center text-base">
                Monto: <span class="font-bold text-green-700">S/ <?= $precio_numero ?></span>
            </div>
            <img src="<?= $url_img_qr ?>" alt="QR Yape"
                class="w-32 h-32 object-contain mx-auto rounded-lg mb-2 border shadow-md">

            <div class="text-xs text-center mb-2 text-gray-500">
                Escanea el código QR o paga al número:<br>
                <span
                    class="font-mono text-base font-bold text-black tracking-wider bg-yellow-100 px-2 py-0.5 rounded">986634352</span>
            </div>

            <form id="formYape" class="w-full flex flex-col gap-2 items-center">
                <input type="hidden" id="input-numero-yape">
                <div class="w-full">
                    <label for="input-nombre" class="block text-gray-600 font-medium text-sm mb-1">Tu nombre
                        completo</label>
                    <input type="text" id="input-nombre" placeholder="Ej: Andres Silva"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-green-200 outline-none transition text-base"
                        required>
                </div>
                <div class="w-full">
                    <label for="input-aprobacion" class="block text-gray-600 font-medium text-sm mb-1">N° de aprobación
                        Yape <span class="text-gray-400">(opcional)</span></label>
                    <input type="text" id="input-aprobacion" placeholder="123456" maxlength="10"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-green-100 outline-none transition text-base">
                </div>
                <button type="button" onclick="enviarWaYape()"
                    class="mt-2 bg-green-600 hover:bg-green-700 transition text-white rounded-lg py-2 w-full font-bold shadow text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                    Enviar comprobante por WhatsApp
                </button>
            </form>
            <p class="mt-1 text-xs text-gray-400 text-center">* Se enviará un mensaje al WhatsApp del organizador</p>
        </div>
        <style>
            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: scale(0.98);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.3s cubic-bezier(.28, .84, .42, 1) both;
            }
        </style>
    </div>


    <script>
        var numeroSeleccionado = null;
        function mostrarYape(numero) {
            numeroSeleccionado = numero;
            document.getElementById('yape-numero').textContent = numero;
            document.getElementById('input-numero-yape').value = numero;
            document.getElementById('modalYape').classList.remove('hidden');
            document.getElementById('input-nombre').value = '';
            document.getElementById('input-aprobacion').value = '';
            document.getElementById('input-nombre').focus();
        }
        function cerrarYape() {
            document.getElementById('modalYape').classList.add('hidden');
        }
        function enviarWaYape() {
            var nombre = document.getElementById('input-nombre').value.trim();
            var aprobacion = document.getElementById('input-aprobacion').value.trim();
            var num = numeroSeleccionado;
            if (!nombre) {
                alert("Por favor, completa tu nombre.");
                document.getElementById('input-nombre').focus();
                return;
            }
            var mensaje = "Hola, quiero reservar el número " + num +
                " para el SORTEO PISHCOTA-LOVER.%0ANombre: " + nombre;
            if (aprobacion) {
                mensaje += "%0AN° de aprobación Yape: " + aprobacion;
            }
            mensaje += "%0A¡Adjunto mi pago!";
            var wa = "https://wa.me/<?= $whatsapp_admin ?>?text=" + mensaje;
            window.open(wa, '_blank');
            cerrarYape();
        }

        // Permite cerrar el modal con Escape
        window.addEventListener('keydown', function (e) {
            if (e.key === "Escape") cerrarYape();
        });

    </script>
</body>

</html>