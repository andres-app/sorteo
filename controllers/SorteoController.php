<?php
require_once __DIR__ . '/../models/Numero.php';
require_once __DIR__ . '/../models/Empresa.php'; // <-- ¡Asegúrate de incluir esto!

class SorteoController
{
    public function index()
    {
        $numeroModel = new Numero();
        $numeros = $numeroModel->getNumeros();
        $precio_numero = 8;
        $url_img_qr = 'https://suministrosperu.com/wp-content/uploads/2016/01/YAPE-QR.png';
        $whatsapp_admin = '51986634352';

        require __DIR__ . '/../views/sorteo/index.php';
    }

    public function admin()
    {
        session_start();
        $admin_pass = '123123';
        if (isset($_POST['login'])) {
            if ($_POST['pass'] === $admin_pass) {
                $_SESSION['is_admin'] = true;
            } else {
                $error = "Contraseña incorrecta";
            }
        }
        if (isset($_GET['logout'])) {
            session_destroy();
            header('Location: /sorteo/public/admin');
            exit;
        }
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            require __DIR__ . '/../views/admin/login.php';
            exit;
        }
        $numeroModel = new Numero();
        $numeros = $numeroModel->getNumeros();
        $whatsapp_admin = '51987654321';
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function registrar()
    {
        $numeroModel = new Numero();
        $numero = $_POST['numero'];
        $nombre = $_POST['nombre'];
        $whatsapp = '51' . $_POST['whatsapp'];
        $sorteo_id = 1;

        if (isset($_POST['editar'])) {
            $numeroModel->actualizarNumero($numero, $nombre, $whatsapp, $sorteo_id);
            header('Location: /sorteo/public/admin?edit=ok');
            exit;
        } else {
            if ($numeroModel->venderNumero($numero, $nombre, $whatsapp, $sorteo_id)) {
                $mensaje = urlencode("¡Gracias por participar en el SORTEO PISHCOTA-LOVER!\n\nTu número es: $numero\nNombre: $nombre\nFecha del sorteo: 27-07-25\n¡Mucha suerte!\nMás detalles en: https://felicitygirls.pe");
                $wa_url = "https://wa.me/" . preg_replace('/\D/', '', $whatsapp) . "?text=$mensaje";
                header("Location: /sorteo/public/admin?wa_url=" . urlencode($wa_url));
                exit;
            } else {
                echo "Error: el número ya fue vendido o hubo un problema.";
            }
        }
    }

    public function empresas()
    {
        $empresaModel = new Empresa();
        $empresas = $empresaModel->getAll();
        require __DIR__ . '/../views/admin/empresas.php';
    }

    public function crearEmpresa()
    {
        $empresaModel = new Empresa();
        $empresaModel->create(
            $_POST['nombre'],
            $_POST['ruc'],
            $_POST['direccion'],
            $_POST['telefono'],
            $_POST['email']
        );
        header('Location: /sorteo/public/empresas');
        exit;
    }

    public function editarEmpresa()
    {
        $empresaModel = new Empresa();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresaModel->update(
                $_POST['id'],
                $_POST['nombre'],
                $_POST['ruc'],
                $_POST['direccion'],
                $_POST['telefono'],
                $_POST['email']
            );
            header('Location: /sorteo/public/empresas?ok=edit');
            exit;
        } else {
            $empresa = $empresaModel->get($_GET['id']);
            require __DIR__ . '/../views/admin/empresa_editar.php';
        }
    }

    public function eliminarEmpresa()
    {
        $empresaModel = new Empresa();
        $result = $empresaModel->delete($_GET['id']);
        if ($result) {
            header('Location: /sorteo/public/empresas?ok=del');
        } else {
            header('Location: /sorteo/public/empresas?error=noborrar');
        }
        exit;
    }
    


    // Aquí puedes agregar métodos editarEmpresa() y deleteEmpresa() si quieres CRUD completo.
}
?>