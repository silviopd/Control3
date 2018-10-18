<?php

require_once '../datos/Conexion.clase.php';

class SerieComprobante extends Conexion {

    public function cargarSerie($p_tipoComprobante) {
        try {
            $sql = "select numero_serie from serie_comprobante where codigo_tipo_comprobante = :p_tipoComprobante;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_tipoComprobante", $p_tipoComprobante);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function cargarNroDocumento($p_tipoComprobante, $p_serie){
        try {
            $sql = "select  * from f_generar_correlativo_comprobante(:p_tipoComprobante, :p_serie) as numero;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_tipoComprobante", $p_tipoComprobante);
            $sentencia->bindParam(":p_serie", $p_serie);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            return $resultado;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    

}
