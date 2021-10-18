<?php
require_once 'clases/Usuario.php';
require_once 'clases/RepositorioCuenta.php';
require_once 'clases/RepositorioUsuario.php';
require_once 'clases/Cuenta.php';
session_start();
if (isset($_SESSION['usuario']) && isset($_GET['n'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $rc = new RepositorioCuenta();
    $cuenta = $rc->get_one($_GET['n']);
    if ($cuenta->getIdUsuario() != $usuario->getId()) {
        die("Error: La cuenta no pertenece al usuario");
    }
    if($cuenta->getSaldo() != 0) {
        header('Location: home.php?mensaje=La cuenta debe estar con saldo 0');
    } else {
        if ($rc->delete($cuenta)) {
            $mensaje = "Cuenta eliminada con éxito";
        } else {
            $mensaje = "Error al eliminar la cuenta";
        }
        header("Location: home.php?mensaje=$mensaje");
    }
} else {
    header('Location: index.php');
}
?>
