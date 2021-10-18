<?php
require_once '.env.php';
require_once 'clases/Repositorio.php';
require_once 'Usuario.php';

class RepositorioUsuario extends Repositorio
{
    public function login($nombre_usuario, $clave)
    {
        $q = "SELECT id, clave, nombre, apellido FROM usuarios ";
        $q.= "WHERE usuario = ?";
        $query = self::$conexion->prepare($q);
        $query->bind_param("s", $nombre_usuario);
        if ( $query->execute() ) {
            $query->bind_result($id, $clave_encriptada, $nombre, $apellido);
            if ( $query->fetch() ) {
                if( password_verify($clave, $clave_encriptada) === true) {
                    return new Usuario($nombre_usuario, $nombre, $apellido, $id);
                }
            }
        }
        return false;
    }

    public function save(Usuario $u, $clave)
    {
        $q = "INSERT INTO usuarios (usuario, nombre, apellido, clave) ";
        $q.= "VALUES (?, ?, ?, ?)";
        $query = self::$conexion->prepare($q);

        $query->bind_param("ssss", $u->getUsuario(), $u->getNombre(),
            $u->getApellido(), password_hash($clave, PASSWORD_DEFAULT));

        if ($query->execute()) {
            // Retornamos el id del usuario recién insertado.
            return self::$conexion->insert_id;
        } else {
            return false;
        }
    }

    public function get_one($id)
    {
        $q = "SELECT usuario, nombre, apellido FROM usuarios WHERE id = ?";
        $query = self::$conexion->prepare($q);
        $query->bind_param("i", $id);
        if ($query->execute()) {
            $query->bind_result($nombre_usuario, $nombre, $apellido);
            if ($query->fetch()) {
                return new Usuario($nombre_usuario, $nombre, $apellido, $id);
            }
        }
        return false;
    }
}
    
