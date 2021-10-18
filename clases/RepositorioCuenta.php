<?php
require_once '.env.php';
require_once 'clases/Repositorio.php';
require_once 'clases/Usuario.php';
require_once 'clases/Cuenta.php';

class RepositorioCuenta extends Repositorio
{

    public function store(Cuenta $cuenta)
    {
        $saldo = $cuenta->getSaldo();
        $idUsuario = $cuenta->getIdUsuario();

        $q = "INSERT INTO cuentas (saldo, id_usuario) VALUES (?, ?)";
        try {
            $query = self::$conexion->prepare($q);

            $query->bind_param("ii", $saldo, $idUsuario);

            if ($query->execute()) {
                return self::$conexion->insert_id;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_all(Usuario $usuario)
    {
        $idUsuario = $usuario->getId();
        $q = "SELECT saldo, numero FROM cuentas WHERE id_usuario = ?";
        try {
            $query = self::$conexion->prepare($q);
            $query->bind_param("i", $idUsuario);
            $query->bind_result($saldo, $numero);

            if ($query->execute()) {
                $listaCuentas = array();
                while ($query->fetch()) {
                    $listaCuentas[] = new Cuenta($usuario, $saldo, $numero);
                }
                return $listaCuentas;
            }
            return false;
        } catch(Exception $e) {
            return false;
        }
    }

    public function get_one($numero)
    {
        $q = "SELECT saldo, id_usuario FROM cuentas WHERE numero = ?";
        try {
            $query = self::$conexion->prepare($q);
            $query->bind_param("i", $numero);
            $query->bind_result($saldo, $idUsuario);

            if ($query->execute()) {
                if ($query->fetch()) {
                    $ru = new RepositorioUsuario();
                    $usuario = $ru->get_one($idUsuario);
                    return new Cuenta($usuario, $saldo, $numero);
                }
            }
            return false;
        } catch(Exception $e) {
            return false;
        }
    }

    public function delete(Cuenta $cuenta)
    {
        $n = $cuenta->getNumero();
        $q = "DELETE FROM cuentas WHERE numero = ?";
        $query = self::$conexion->prepare($q);
        $query->bind_param("i", $n);
        return ($query->execute());
    }

    public function actualizarSaldo(Cuenta $cuenta)
    {
        $n = $cuenta->getNumero();
        $s = $cuenta->getSaldo();

        $q = "UPDATE cuentas SET saldo = ? WHERE numero = ?";

        $query = self::$conexion->prepare($q);
        $query->bind_param("ii", $s, $n);

        return $query->execute();
    }

}


