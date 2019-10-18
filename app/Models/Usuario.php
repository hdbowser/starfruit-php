<?php

namespace App\Models;

use App\Core\Data;

class Usuario extends Contacto
{
    public $usuario;
    public $password;
    public $permisos;

    public function bindJSON($data)
    {
        $data = json_decode($data, true);
        if ($data != null) {
            $salt = "caballo-salvaje";
            $this->nombre = (isset($data['nombre'])) ? $data['nombre'] : null;
            $this->apellidos = (isset($data['apellidos'])) ? $data['apellidos'] : null;
            $this->usuario = (isset($data['usuario'])) ? $data['usuario'] : null;
            $this->email = (isset($data['email'])) ? $data['email'] : null;
            $this->idCuenta = (isset($data['idCuenta'])) ? $data['idCuenta'] : null;
            $this->password = (isset($data['password'])) ? hash("sha256",  $data['password'] . $salt, false) : null;
            return true;
        } else {
            return false;
        }
    }

    public function registrar()
    {
        $db = Data::getDBContext();
        $status = $db->insert("Contacto", [
            "nombre" => $this->nombre,
            "apellidos" => $this->apellidos,
            "usuario" => $this->usuario,
            "email" => $this->email,
            "idCuenta" => $this->idCuenta,
            "password" => $this->password,
            "esUsuario" => 1,
            "permisos" => ""
        ]);
        if ($status == null) {
            return false;
        } else {
            return ($status->rowCount() > 0);
        }
    }

    public function login()
    {
        $db = Data::getDBContext();
        $salt = "caballo-salvaje";
        return $db->select("Contacto", [
            "idContacto",
            "usuario",
            "nombre",
            "permisos".
            "apellidos"

        ], [
            "password" => hash("sha256", $this->password . $salt, false),
            "OR" => [
                "usuario" => $this->usuario,
                "email" => $this->usuario
            ]
        ]);
    }
}
