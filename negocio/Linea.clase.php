<?php

require_once '../datos/Conexion.clase.php';

class Linea extends Conexion {

    private $codigoLinea;
    private $descripcion;

    function getCodigoLinea() {
        return $this->codigoLinea;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function setCodigoLinea($codigoLinea) {
        $this->codigoLinea = $codigoLinea;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function cargarListaDatos() {
        try {
            $sql = "select l.codigo_linea, l.descripcion, (case when count(c.codigo_categoria) = 0 then false else true end)::boolean as estado from linea l left join categoria c on(l.codigo_linea = c.codigo_linea) group by l.codigo_linea";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function eliminar($p_codigoLinea) {
        $this->dblink->beginTransaction();
        try {
            $sql = "delete from linea where codigo_linea = :p_codigoLinea;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigoLinea", $p_codigoLinea);
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
            $sql = "select * from f_generar_correlativo('linea') as nc;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            if ($sentencia->rowCount()) {
                $codigoLinea = $resultado["nc"];
                $this->setCodigoLinea($codigoLinea);
                $sql = "INSERT INTO linea(codigo_linea, descripcion) VALUES (:p_codigo_linea, :p_descripcion);";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_codigo_linea", $this->getCodigoLinea());
                $sentencia->bindParam(":p_descripcion", $this->getDescripcion());
                $sentencia->execute();
                $sql = "UPDATE correlativo SET numero = numero + 1 WHERE tabla = 'linea';";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();
                $this->dblink->commit();
                return true;
            } else {
                throw new Exception("No se ha configurado el correlativo para la tabla Linea.");
            }
        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
    }

    public function editar() {
        $this->dblink->beginTransaction();
        try {
            $sql = "update linea set descripcion = :p_descripcion where codigo_linea = :p_codigo_linea;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_descripcion", $this->getDescripcion());
            $sentencia->bindParam(":p_codigo_linea", $this->getCodigoLinea());
            $sentencia->execute();
            $this->dblink->commit();
            return true;
        } catch (Exception $ex) {
            throw new Exception("No se ha configurado el correlativo para la tabla articulo.");
        }
    }

    public function leerDatos($p_codigoLinea) {
        try {
            $sql = "select * from linea where codigo_linea = :p_codigo_linea;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigo_linea", $p_codigoLinea);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }



}
