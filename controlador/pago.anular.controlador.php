<?php
require_once '../negocio/Pago.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    if (!isset($_POST["p_numero_pago"])) {
        Funciones::imprimeJSON(500, "Faltan parametros", "");
        exit;
    }
    $numero_pago = $_POST["p_numero_pago"];
    $objPago = new Pago();
    $resultado = $objPago->anular($numero_pago);
    if($resultado == true){
        Funciones::imprimeJSON(200, "Venta anulada correctamente", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}