<?php
require_once 'clases/Usuario.php';
require_once 'clases/RepositorioCuenta.php';
require_once 'clases/RepositorioUsuario.php';
require_once 'clases/Cuenta.php';
session_start();
if (isset($_SESSION['usuario']) && isset($_POST['monto'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $rc = new RepositorioCuenta();
    $cuenta = $rc->get_one($_POST['cuenta']);
    if ($cuenta->getIdUsuario() != $usuario->getId()) {
        die("Error: La cuenta no pertenece al usuario");
    }

    if ($_POST['tipo'] == 'e') {
        $r = $cuenta->extraer($_POST['monto']);
    } else if ($_POST['tipo'] == 'd') {
        $r = $cuenta->depositar($_POST['monto']);
    }
    if ($r) {
        $rc->actualizarSaldo($cuenta);
        $respuesta['resultado'] = "OK";
    } else {
        $respuesta['resultado'] = "Error al realizar la operación";
    }

    $respuesta['numero_cuenta'] = $cuenta->getNumero();
    $respuesta['saldo'] = $cuenta->getSaldo();
    echo json_encode($respuesta);
}



