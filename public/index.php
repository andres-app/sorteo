<?php
require_once __DIR__ . '/../controllers/SorteoController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// --- ARREGLO PARA SUBCARPETA ---
$baseFolder = '/sorteo/public'; // Ajusta si tu carpeta cambia
if (strpos($uri, $baseFolder) === 0) {
    $uri = substr($uri, strlen($baseFolder));
    if ($uri === '')
        $uri = '/';
}
// -------------------------------

$controller = new SorteoController();

if ($uri == '/' || $uri == '/index.php') {
    $controller->index();
} elseif ($uri == '/admin') {
    $controller->admin();
} elseif ($uri == '/registrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->registrar();
} elseif ($uri == '/empresas') {
    $controller->empresas();
} elseif ($uri == '/crearEmpresa' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->crearEmpresa();
} elseif ($uri == '/editarEmpresa') {
    $controller->editarEmpresa();
} elseif ($uri == '/eliminarEmpresa') {
    $controller->eliminarEmpresa();

} else {
    http_response_code(404);
    echo "Página no encontrada.";
}
?>