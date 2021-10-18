<?php
require_once 'clases/Usuario.php';
require_once 'clases/Cuenta.php';
require_once 'clases/RepositorioCuenta.php';

session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $nomApe = $usuario->getNombreApellido();
    $rc = new RepositorioCuenta();
    $cuentas = $rc->get_all($usuario);
} else {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Sistema bancario</title>
        <link rel="stylesheet" href="bootstrap.min.css">
    </head>
    <body class="container">
      <div class="jumbotron text-center">
      <h1>Sistema bancario</h1>
      </div>    
      <div class="text-center">
        <?php
        if (isset($_GET['mensaje'])) {
            echo '<p class="alert alert-primary">'.$_GET['mensaje'].'</p>';
        }
        ?>
        <h3><?php echo $nomApe;?></h3>
        <h3>Listado de cuentas</h3>
        <table class="table table-striped">
            <tr>
                <th>Número</th><th>Saldo</th><th>Depositar</th><th>Extraer</th><th>Eliminar</th>
            </tr>
        <?php
        if (count($cuentas) == 0) {
            echo "<tr><td colspan='5'>No tiene cuentas creadas</td></tr>";
        } else {
            foreach ($cuentas as $unaCuenta) {
                $n = $unaCuenta->getNumero();
                echo '<tr>';
                echo "<td>$n</td>";
                echo "<td id='saldo-$n'>".$unaCuenta->getSaldo()."</td>";
                echo "<td><button type='button' onclick='depositar($n)'>Depositar</button></td>";
                echo "<td><button type='button' onclick='extraer($n)'>Extraer</button></td>";
                echo "<td><a href='eliminar.php?n=$n'>Eliminar</a></td>";
                echo '</tr>';
            }
        }
        ?>
        </table>
        <br>
        <div id="operacion">
            <h3 id="tipo_operacion">Operación</h3>
            <input type="hidden" id="tipo">
            <input type="hidden" id="numero">
            <label for="monto">Monto de la operación: </label>
            <input type="number" id="monto"><br>
            <button type="button" onclick="operacion();">Realizar operacion</button>
        </div>
        <hr>
        <a class="btn btn-primary" href="crear_cuenta.php">Crear nueva cuenta</a>
    
        <p><a href="logout.php">Cerrar sesión</a></p>
      </div> 
<script>
        function operacion() {
            var tipo = document.querySelector('#tipo').value;
            var cuenta = document.querySelector('#numero').value;
            var monto = document.querySelector('#monto').value;
            var cadena = "tipo="+tipo+"&cuenta="+cuenta+"&monto="+monto;

            var solicitud = new XMLHttpRequest();

            solicitud.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var respuesta = JSON.parse(this.responseText);
                    var identificador = "#saldo-" + respuesta.numero_cuenta;
                    var celda = document.querySelector(identificador);

                    if(respuesta.resultado == "OK") {
                        celda.innerHTML = respuesta.saldo;
                    } else {
                        alert(respuesta.resultado);
                    }
                    celda.scrollIntoView();
                }
            };
            solicitud.open("POST", "operacion.php", true);
            solicitud.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            solicitud.send(cadena);
        }


        function extraer(nroCuenta)
        {
            document.querySelector('#tipo').value = "e";
            document.querySelector('#tipo_operacion').innerHTML = "Extracción";
            document.querySelector('#numero').value = nroCuenta;
            document.querySelector('#monto').focus();
        }

        function depositar(nroCuenta)
        {
            document.querySelector('#tipo').value = "d";
            document.querySelector('#tipo_operacion').innerHTML = "Depósito";
            document.querySelector('#numero').value = nroCuenta;
            document.querySelector('#monto').focus();
        }
</script>
    </body>
</html>

