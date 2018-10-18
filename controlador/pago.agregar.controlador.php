<?php
//
//session_name("sistemacomercial1");
//session_start();
require_once '../negocio/Pago.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (!isset($_POST["p_datosFormulario"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}

$datosFormulario = $_POST["p_datosFormulario"];
parse_str($datosFormulario, $datosFormularioArray);

$datosJSONDetalle = $_POST["p_datosJSONDetalle"];


//print_r($datosFormularioArray);
try {
    $objPago = new Pago();
    $objPago->setFecha_pago($datosFormularioArray["txtfecha"]);
    $objPago->setTotal($datosFormularioArray["txtimportesubtotal"]);
    
    $objPago->setDetallePago($datosJSONDetalle);
    
    $resultado = $objPago->agregar();
    
    
    
    if($resultado == true){
        Funciones::imprimeJSON(200, "El pago ha sido registrado satisfactoriamente.", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
