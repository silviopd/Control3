<?php

require_once '../datos/Conexion.clase.php';

class Categoria extends Conexion {

    private $codigoCategoria;
    private $descripcion;
    private $codigoLinea;

    function getCodigoCategoria() {
        return $this->codigoCategoria;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getCodigoLinea() {
        return $this->codigoLinea;
    }

    function setCodigoCategoria($codigoCategoria) {
        $this->codigoCategoria = $codigoCategoria;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setCodigoLinea($codigoLinea) {
        $this->codigoLinea = $codigoLinea;
    }

    public function cargarListaDatos($p_codigoLinea) {
        try {
            $sql = "select * from f_listar_categoria(:p_codigoLinea);";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigoLinea", $p_codigoLinea);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function eliminar($p_codigoCategoria) {
        $this->dblink->beginTransaction();
        try {
            $sql = "delete from categoria where codigo_categoria = :p_codigoCategoria;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigoCategoria", $p_codigoCategoria);
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
            $sql = "select * from f_generar_correlativo('categoria') as nc;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            if ($sentencia->rowCount()) {
                $codigoCategoria = $resultado["nc"];
                $this->setCodigoCategoria($codigoCategoria);
                $sql = "INSERT INTO public.categoria(codigo_categoria, descripcion, codigo_linea) VALUES (:p_codigo_categoria, :p_descripcion, :p_codigo_linea);";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_codigo_categoria", $this->getCodigoCategoria());
                $sentencia->bindParam(":p_descripcion", $this->getDescripcion());
                $sentencia->bindParam(":p_codigo_linea", $this->getCodigoLinea());
                $sentencia->execute();
                $sql = "UPDATE correlativo SET numero = numero + 1 WHERE tabla = 'categoria';";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();
                $this->dblink->commit();
                return true;
            } else {
                throw new Exception("No se ha configurado el correlativo para la tabla categoria.");
            }
        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
    }

    public function leerDatos($p_codigoCategoria) {
        try {
            $sql = "select c.codigo_categoria, c.descripcion, c.codigo_linea, l.descripcion as linea from categoria c inner join linea l on(c.codigo_linea = l.codigo_linea) where codigo_categoria = :p_codigoCategoria;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigoCategoria", $p_codigoCategoria);
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
            $sql = "UPDATE categoria SET descripcion = :p_descripcion, codigo_linea = :p_codigoLinea WHERE codigo_categoria = :p_codigoCategoria;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_descripcion", $this->getDescripcion());
            $sentencia->bindParam(":p_codigoLinea", $this->getCodigoLinea());
            $sentencia->bindParam(":p_codigoCategoria", $this->getCodigoCategoria());
            $sentencia->execute();
            $this->dblink->commit();
            return true;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

}
