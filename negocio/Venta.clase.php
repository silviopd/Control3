<?php

require_once '../datos/Conexion.clase.php';

class Venta extends Conexion {

    private $numero_venta;
    private $codigo_tipo_comprobante;
    private $numero_serie;
    private $numero_documento;
    private $codigo_cliente;
    private $fecha_venta;
    private $porcentaje_igv;
    private $sub_total;
    private $igv;
    private $total;
    private $codigo_usuario;
    private $detalleVenta; //JSON

    function getNumero_venta() {
        return $this->numero_venta;
    }

    function getCodigo_tipo_comprobante() {
        return $this->codigo_tipo_comprobante;
    }

    function getNumero_serie() {
        return $this->numero_serie;
    }

    function getNumero_documento() {
        return $this->numero_documento;
    }

    function getCodigo_cliente() {
        return $this->codigo_cliente;
    }

    function getFecha_venta() {
        return $this->fecha_venta;
    }

    function getPorcentaje_igv() {
        return $this->porcentaje_igv;
    }

    function getSub_total() {
        return $this->sub_total;
    }

    function getIgv() {
        return $this->igv;
    }

    function getTotal() {
        return $this->total;
    }

    function getCodigo_usuario() {
        return $this->codigo_usuario;
    }

    function getDetalleVenta() {
        return $this->detalleVenta;
    }

    function setNumero_venta($numero_venta) {
        $this->numero_venta = $numero_venta;
    }

    function setCodigo_tipo_comprobante($codigo_tipo_comprobante) {
        $this->codigo_tipo_comprobante = $codigo_tipo_comprobante;
    }

    function setNumero_serie($numero_serie) {
        $this->numero_serie = $numero_serie;
    }

    function setNumero_documento($numero_documento) {
        $this->numero_documento = $numero_documento;
    }

    function setCodigo_cliente($codigo_cliente) {
        $this->codigo_cliente = $codigo_cliente;
    }

    function setFecha_venta($fecha_venta) {
        $this->fecha_venta = $fecha_venta;
    }

    function setPorcentaje_igv($porcentaje_igv) {
        $this->porcentaje_igv = $porcentaje_igv;
    }

    function setSub_total($sub_total) {
        $this->sub_total = $sub_total;
    }

    function setIgv($igv) {
        $this->igv = $igv;
    }

    function setTotal($total) {
        $this->total = $total;
    }

    function setCodigo_usuario($codigo_usuario) {
        $this->codigo_usuario = $codigo_usuario;
    }

    function setDetalleVenta($detalleVenta) {
        $this->detalleVenta = $detalleVenta;
    }

    public function agregar() {
        $this->dblink->beginTransaction();
        try {
            $sql = "select * from f_generar_correlativo('venta') as nc;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            if ($sentencia->rowCount()) {
                $numeroVenta = $resultado["nc"];
                $this->setNumero_venta($numeroVenta);
                $sql = "INSERT INTO venta(numero_venta, codigo_tipo_comprobante, numero_serie, numero_documento, codigo_cliente, fecha_venta, porcentaje_igv, sub_total, igv, total, codigo_usuario) "
                        . "VALUES (:p_numero_venta, :p_codigo_tipo_comprobante, :p_numero_serie, :p_numero_documento, :p_codigo_cliente, :p_fecha_venta, :p_porcentaje_igv, :p_sub_total, :p_igv, :p_total, :p_codigo_usuario);";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_numero_venta", $this->getNumero_venta());
                $sentencia->bindParam(":p_codigo_tipo_comprobante", $this->getCodigo_tipo_comprobante());
                $sentencia->bindParam(":p_numero_serie", $this->getNumero_serie());
                $sentencia->bindParam(":p_numero_documento", $this->getNumero_documento());
                $sentencia->bindParam(":p_codigo_cliente", $this->getCodigo_cliente());
                $sentencia->bindParam(":p_fecha_venta", $this->getFecha_venta());
                $sentencia->bindParam(":p_porcentaje_igv", $this->getPorcentaje_igv());
                $sentencia->bindParam(":p_sub_total", $this->getSub_total());
                $sentencia->bindParam(":p_igv", $this->getIgv());
                $sentencia->bindParam(":p_total", $this->getTotal());
                $sentencia->bindParam(":p_codigo_usuario", $this->getCodigo_usuario());
                $sentencia->execute();

                $item = 0;
                //insertar en la tabla VentaDetalle
                $detalleVentaArray = json_decode($this->getDetalleVenta());


                foreach ($detalleVentaArray as $key => $value) {//permite recorrer el arreglo
                    $codArticulo = $value->codigoArticulo;
                    $cant = $value->cantidad;


                    $sql = "select nombre, stock from articulo where codigo_articulo = :p_codigo_articulo";
                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_codigo_articulo", $codArticulo);


                    $sentencia->execute();
                    $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                    $stock = $resultado[0]["stock"];
                    $nomArtic = $resultado[0]["nombre"];
                    if ($stock < $cant) {
                        throw new Exception("Stock insuficiente para el articulo " . $nomArtic . "\n" . "stock actual: " . $stock . "\n" . "cantidad de venta: " . $cant);
                    }

                    $sql = "INSERT INTO venta_detalle(numero_venta, item, codigo_articulo, cantidad, precio, importe, descuento1, descuento2)
                            VALUES (:p_numeroVenta, :p_item, :p_codigoArticulo, :p_cantidad, :p_precio, :p_importe, :p_descuento1, :p_descuento2);";
                    $sentencia = $this->dblink->prepare($sql);
                    $item++;
                    $sentencia->bindParam(":p_numeroVenta", $this->getNumero_venta());
                    $sentencia->bindParam(":p_item", $item);
                    $sentencia->bindParam(":p_codigoArticulo", $codArticulo);
                    $sentencia->bindParam(":p_cantidad", $cant);
                    $sentencia->bindParam(":p_precio", $value->precio);
                    $sentencia->bindParam(":p_importe", $value->importe);
                    $descuento1 = 0;
                    $descuento2 = 0;
                    $sentencia->bindParam(":p_descuento1", $descuento1);
                    $sentencia->bindParam(":p_descuento2", $descuento2);

                    $sentencia->execute();


                    //ahora debemos actualizar el stock de cada articulo vendido
                    $sql = "update articulo set stock = stock - :p_cantidad where codigo_articulo = :p_codigo_articulo;";
                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_cantidad", $cant);
                    $sentencia->bindParam(":p_codigo_articulo", $codArticulo);
                    $sentencia->execute();
                }

                $sql = "UPDATE correlativo SET numero = numero + 1 WHERE tabla = 'venta';";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();

                //actualizar el correlativo segun el tipo de documento y la serie
                $sql = "UPDATE serie_comprobante SET numero_documento = numero_documento + 1 WHERE codigo_tipo_comprobante = :p_codigo_tipo_comprobante and numero_serie = :p_numero_serie;";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_codigo_tipo_comprobante", $this->getCodigo_tipo_comprobante());
                $sentencia->bindParam(":p_numero_serie", $this->getNumero_serie());
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
            $sql = "select * from f_listar_venta(:p_fecha1, :p_fecha2, :p_tipo);";
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

    public function anular($numeroVenta) {
        $this->dblink->beginTransaction();
        try {
            $sql = "update venta set estado = 'A' where numero_venta = :p_numero_venta;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_numero_venta", $numeroVenta);
            $sentencia->execute();

            $sql = "select codigo_articulo, cantidad from venta_detalle where numero_venta = :p_numero_venta;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_numero_venta", $numeroVenta);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($resultado); $i++) {
                $sql = "update articulo set stock = stock + :p_cantidad where codigo_articulo = :p_codigo_articulo;";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_cantidad", $resultado[$i]["cantidad"]);
                $sentencia->bindParam(":p_codigo_articulo", $resultado[$i]["codigo_articulo"]);
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
