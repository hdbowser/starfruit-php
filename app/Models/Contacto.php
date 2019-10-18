<?php

namespace App\Models;

use Medoo\Medoo;
use App\Core\Data;

class Contacto
{
    public $idContacto;
    public $nombre;
    public $apellidos;
    public $telefono;
    public $celular;
    public $email;
    public $direccion;
    public $ciudad;
    public $nota;
    public $idCuenta;
    public $fechaCreado;

    public function bindJSON($data)
    {
        $data = json_decode($data, true);
        if ($data != null) {
            $this->nombre = (isset($data['nombre'])) ? $data['nombre'] : null;
            $this->apellidos = (isset($data['apellidos'])) ? $data['apellidos'] : null;
            $this->telefono = (isset($data['telefono'])) ? $data['telefono'] : null;
            $this->celular = (isset($data['celular'])) ? $data['celular'] : null;
            $this->email = (isset($data['email'])) ? $data['email'] : null;
            $this->direccion = (isset($data['direccion'])) ? $data['direccion'] : null;
            $this->ciudad = (isset($data['ciudad'])) ? $data['ciudad'] : null;
            $this->nota = (isset($data['nota'])) ? $data['nota'] : null;
            $this->idCuenta = (isset($data['idCuenta'])) ? $data['idCuenta'] : null;
            $this->fechaCreado = (isset($data['fechaCreado'])) ? $data['fechaCreado'] : null;
            return true;
        } else {
            return false;
        }
    }

    public function buscar($busqueda)
    {
        $db = Data::getDBContext();
        return $db->select("Contacto", [
            "idContacto",
            "nombre",
            "apellidos",
            "telefono",
            "celular",
            "email",
            "direccion",
            "ciudad",
            "nota",
            "idCuenta",
            "fechaCreado",
        ], [
            "eliminado[!]" => 1,
            "OR" => [
                "nombre[~]" => "%$busqueda%",
                "apellidos[~]" => "%$busqueda%",
                "email[~]" => "%$busqueda%"
            ]
        ]);
    }
    public function registrar()
    {
        $db = Data::getDBContext();
        $status = $db->insert("Contacto", [
            "nombre" => $this->nombre,
            "apellidos" => $this->apellidos,
            "telefono" => $this->telefono,
            "celular" => $this->celular,
            "email" => $this->email,
            "direccion" => $this->direccion,
            "ciudad" => $this->ciudad,
            "nota" => $this->nota,
            "idCuenta" => $this->idCuenta,
            "fechaCreado" => Medoo::raw("datetime()"),
        ]);
        if ($status != null) {
            return ($status->rowCount() > 0);
        } else {
            return false;
        }
    }

    public function actualizar()
    {
        $db = Data::getDBContext();
        $status = $db->update("Contacto", [
            "nombre" => $this->nombre,
            "apellidos" => $this->apellidos,
            "telefono" => $this->telefono,
            "celular" => $this->celular,
            "email" => $this->email,
            "direccion" => $this->direccion,
            "ciudad" => $this->ciudad,
            "nota" => $this->nota,
            "fechaCreado" => Medoo::raw("datetime()"),
        ], [
            'idContacto' => $this->idContacto
        ]);
        if ($status == null) {
            return false;
        } else {

            return ($status->rowCount() > 0);
        }
    }

    public function eliminar()
    {
        $db = Data::getDBContext();
        $status = $db->update("Contacto", [
            "eliminado" => 1,
            "fechaEliminado" => Medoo::raw("datetime()"),
        ], [
            'idContacto' => $this->idContacto
        ]);
        if ($status == null) {
            return false;
        } else {

            return ($status->rowCount() > 0);
        }
    }

    public function detalle()
    {
        $db = Data::getDBContext();
        return $db->select("Contacto", [
            "idContacto",
            "nombre",
            "apellidos",
            "telefono",
            "celular",
            "email",
            "direccion",
            "ciudad",
            "nota",
            "idCuenta",
            "fechaCreado"
        ], [
            'idContacto' => $this->idContacto
        ]);
    }

    public function agregarMeta($llave, $valor)
    {
        $db = Data::getDBContext();
        $status = $db->insert("ContactoMeta", [
            'idContacto' => $this->idContacto,
            'llave' => $llave,
            'valor' => $valor
        ]);
        if ($status != null) {
            return ($status->rowCount() > 0);
        } else {
            return false;
        }
    }

    public function eliminarMeta($llave)
    {
        $db = Data::getDBContext();
        $status = $db->delete("ContactoMeta", [
            "AND" => [
                'idContacto' => $this->idContacto,
                'llave' => $llave
            ]
        ]);
        if ($status != null) {
            return ($status->rowCount() > 0);
        } else {
            return false;
        }
    }

    public function actualizarMeta($llave, $valor)
    {
        $db = Data::getDBContext();
        $status = $db->update("ContactoMeta", [
            'valor' => $valor
        ], [
            'idContacto' => $this->idContacto,
            'llave' => $llave,
        ]);
        if ($status != null) {
            return ($status->rowCount() > 0);
        } else {
            return false;
        }
    }

    public function metaData()
    {
        $db = Data::getDBContext();
        return $db->select("ContactoMeta", [
            "llave",
            "valor"
        ], [
            'idContacto' => $this->idContacto
        ]);
    }
}
