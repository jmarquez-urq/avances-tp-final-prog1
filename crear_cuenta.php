<?php
require_once 'clases/Usuario.php';
session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $nomApe = $usuario->getNombreApellido();
} else {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Crear cuenta bancaria</title>
    </head>
    <body>
        <h1>Crear cuenta bancaria</h1>
        <form action="nueva_cuenta.php" method="post">
           <label for="saldo">Saldo incial</label>
           <input type="number" name="saldo" id="saldo">
           <input type="submit" value="Crear la cuenta">
        </form>
    </body>
</html>
