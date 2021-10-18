<?php
require_once 'clases/Usuario.php';

class Cuenta
{
    protected $saldo;
    protected $usuario;
    protected $numero;

    public function __construct(Usuario $usuario, $saldo, $numero = null)
    {
        $this->usuario = $usuario;
        $this->saldo = $saldo;
        $this->numero = $numero;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getIdUsuario()
    {
        return $this->usuario->getId();
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($n)
    {
        $this->numero = $n;
    }

    public function extraer($monto)
    {
        if ($this->saldo >= $monto) {
            $this->saldo = $this->saldo - $monto;
            return true;
        } else {
            return false;
        }
    }

    public function depositar($monto)
    {
        $this->saldo = $this->saldo + $monto;
        return true;
    }
}
