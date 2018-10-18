<?php

require_once '../datos/Conexion.clase.php';

class Pago extends Conexion {

    private $numero_pago;
    private $fecha_pago;
    private $total;
    private $detallePago; //JSON

    function getNumero_pago() {
        return $this->numero_pago;
    }

    function getFecha_pago() {
        return $this->fecha_pago;
    }

    function getTotal() {
        return $this->total;
    }

    function getDetallePago() {
        return $this->detallePago;
    }

    function setNumero_pago($numero_pago) {
        $this->numero_pago = $numero_pago;
    }

    function setFecha_pago($fecha_pago) {
        $this->fecha_pago = $fecha_pago;
    }

    function setTotal($total) {
        $this->total = $total;
    }

    function setDetallePago($detallePago) {
        $this->detallePago = $detallePago;
    }

    public function agregar() {
        $this->dblink->beginTransaction();
        try {
            $sql = "select * from f_generar_correlativo('pago') as nc;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            if ($sentencia->rowCount()) {
                $numeroPago = $resultado["nc"];
                $this->setNumero_pago($numeroPago);

                $sql = "INSERT INTO public.pago(num_pago, fecha_pago, total) VALUES (:p_numero_pago, :p_fecha_pago, :p_total);";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_numero_pago", $numeroPago);
                $sentencia->bindParam(":p_fecha_pago", $this->getFecha_pago());
                $sentencia->bindParam(":p_total", $this->getTotal());

                $sentencia->execute();

                //insertar en la tabla VentaDetalle
                $detallePagoArray = json_decode($this->getDetallePago());


                foreach ($detallePagoArray as $key => $value) {//permite recorrer el arreglo
                    $numeroRecibo = $value->numero_recibo;
                    $importe = $value->importe;

                    $sql = "INSERT INTO public.pago_detalle(numero_pago, numero_recibo, importe) VALUES (:p_numero_pago, :p_numero_recibo, :p_importe);";
                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_numero_pago", $numeroPago);
                    $sentencia->bindParam(":p_numero_recibo", $numeroRecibo);
                    $sentencia->bindParam(":p_importe", $importe);
                    $sentencia->execute();

                    $sql = "update recibo set estado = 'C' where numero_recibo = :p_numero_recibo;";
                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_numero_recibo", $numeroRecibo);
                    $sentencia->execute();
                }

                $sql = "UPDATE correlativo SET numero = numero + 1 WHERE tabla = 'pago';";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();
                
                //terminar la transaccion
                $this->dblink->commit();
                return true;
            }
        } catch (Exception $ex) {
            $this->dblink->rollBack();
            throw $ex;
        }
        return false;
    }

    public function listar($fecha1, $fecha2, $tipo) {
        $this->dblink->beginTransaction();
        try {
            $sql = "select * from f_listar_pagos(:p_fecha1, :p_fecha2, :p_tipo);";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_fecha1", $fecha1);
            $sentencia->bindParam(":p_fecha2", $fecha2);
            $sentencia->bindParam(":p_tipo", $tipo);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    public function anular($numeroPago) {
        $this->dblink->beginTransaction();
        try {
            $sql = "update pago set estado = 'A' where num_pago = :p_numero_pago;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_numero_pago", $numeroPago);
            $sentencia->execute();

            $sql = "select * from pago_detalle where numero_pago = :p_numero_pago;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_numero_pago", $numeroPago);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($resultado); $i++) {
                $sql = "update recibo set estado = 'P' where numero_recibo = :p_numero_recibo";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_numero_recibo", $resultado[$i]["numero_recibo"]);
                $sentencia->execute();
            }
            $this->dblink->commit();
            return true;
        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }
    }

}
