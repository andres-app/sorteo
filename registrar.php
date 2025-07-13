<?php
require 'config.php';

$numero = $_POST['numero'];
$nombre = $_POST['nombre'];
$whatsapp = '51' . $_POST['whatsapp'];
$sorteo_id = 1;

if (isset($_POST['editar'])) {
    $stmt = $conn->prepare("UPDATE numeros SET comprador_nombre=?, comprador_whatsapp=? WHERE sorteo_id=? AND numero=?");
    $stmt->execute([$nombre, $whatsapp, $sorteo_id, $numero]);
    header('Location: admin.php?edit=ok');
    exit;
} else {
    $stmt = $conn->prepare("UPDATE numeros SET estado='vendido', comprador_nombre=?, comprador_whatsapp=?, fecha_vendido=NOW() WHERE sorteo_id=? AND numero=? AND estado='libre'");
    $stmt->execute([$nombre, $whatsapp, $sorteo_id, $numero]);
    if ($stmt->rowCount()) {
        $mensaje = urlencode("¡Gracias por participar en el SORTEO PISHCOTA-LOVER!\n\nTu número es: $numero\nNombre: $nombre\nFecha del sorteo: 27-07-25\n¡Mucha suerte!\nMás detalles en: https://felicitygirls.pe");
        // Enviar la URL de WhatsApp como parámetro GET y volver al admin
        $wa_url = "https://wa.me/" . preg_replace('/\D/', '', $whatsapp) . "?text=$mensaje";
        header("Location: admin.php?wa_url=" . urlencode($wa_url));
        exit;
    } else {
        echo "Error: el número ya fue vendido o hubo un problema.";
    }
}
?>
