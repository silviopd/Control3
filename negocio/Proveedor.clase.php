<?php

require_once '../datos/Conexion.clase.php';

class Proveedor extends Conexion {

    private $rucProveedor;
    private $razonSocial;
    private $direccion;
    private $telefono;
    private $representanteLegal;

    function getRucProveedor() {
        return $this->rucProveedor;
    }

    function getRazonSocial() {
        return $this->razonSocial;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getRepresentanteLegal() {
        return $this->representanteLegal;
    }

    function setRucProveedor($ruc) {
        $this->rucProveedor = $ruc;
    }

    function setRazonSocial($razonSocial) {
        $this->razonSocial = $razonSocial;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setRepresentanteLegal($representanteLegal) {
        $this->representanteLegal = $representanteLegal;
    }

    public function cargarListaDatos() {
        try {
            $sql = "select * from proveedor order by 2;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function eliminar($p_rucProveedor) {
        $this->dblink->beginTransaction();
        try {
            $sql = "delete from proveedor where ruc_proveedor = :p_rucProveedor;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_rucProveedor", $p_rucProveedor);
            $sentencia->execute();
            $this->dblink->commit();
            return true;
        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
    }

    public function agregar() {
        $this->dblink->beginTransaction();
        try {
            $sql = "INSERT INTO proveedor(ruc_proveedor, razon_social, direccion, telefono, representante_legal) VALUES (:p_rucProveedor, :p_razonSocial, :p_direccion, :p_telefono, :p_representanteLegal);";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_rucProveedor", $this->getRucProveedor());
            $sentencia->bindParam(":p_razonSocial", $this->getRazonSocial());
            $sentencia->bindParam(":p_direccion", $this->getDireccion());
            $sentencia->bindParam(":p_telefono", $this->getTelefono());
            $sentencia->bindParam(":p_representanteLegal", $this->getRepresentanteLegal());
            $sentencia->execute();
            $this->dblink->commit();
            return true;
        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
    }

    public function leerDatos($p_rucProveedor) {
        try {
            $sql = "select * from proveedor where ruc_proveedor = :p_rucProveedor";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_rucProveedor", $p_rucProveedor);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    public function editar() {
        $this->dblink->beginTransaction();
        try {
            $sql = "UPDATE proveedor SET razon_social = :p_razonSocial, direccion = :p_direccion, telefono = :p_telefono, representante_legal = :p_representanteLegal WHERE ruc_proveedor = :p_rucProveedor;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_razonSocial", $this->getRazonSocial());
            $sentencia->bindParam(":p_direccion", $this->getDireccion());
            $sentencia->bindParam(":p_telefono", $this->getTelefono());
            $sentencia->bindParam(":p_representanteLegal", $this->getRepresentanteLegal());
            $sentencia->bindParam(":p_rucProveedor", $this->getRucProveedor());
            $sentencia->execute();
            $this->dblink->commit();
            return true;
        } catch (Exception $ex) {
            throw new Exception("No se ha configurado el correlativo para la tabla proveedor.");
        }
    }

}
