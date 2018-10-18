<?php
//
//session_name("sistemacomercial1");
//session_start();
require_once '../negocio/Venta.clase.php';
require_once '../util/funciones/Funciones.clase.php';
require_once '../vista/sesion.validar.vista.php';

if (!isset($_POST["p_datosFormulario"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}

$datosFormulario = $_POST["p_datosFormulario"];
parse_str($datosFormulario, $datosFormularioArray);

$datosJSONDetalle = $_POST["p_datosJSONDetalle"];


//print_r($datosFormularioArray);
try {
    $objVenta = new Venta();
    $objVenta->setCodigo_tipo_comprobante($datosFormularioArray["cbotipocomp"]);
    $objVenta->setNumero_serie($datosFormularioArray["cboserie"]);
    $objVenta->setNumero_documento($datosFormularioArray["txtnrodoc"]);
    $objVenta->setCodigo_cliente($datosFormularioArray["txtcodigocliente"]);
    $objVenta->setFecha_venta($datosFormularioArray["txtfec"]);
    $objVenta->setPorcentaje_igv($datosFormularioArray["txtigv"]);
    $objVenta->setSub_total($datosFormularioArray["txtimportesubtotal"]);
    $objVenta->setIgv($datosFormularioArray["txtimporteigv"]);
    $objVenta->setTotal($datosFormularioArray["txtimporteneto"]);
    $objVenta->setCodigo_usuario($codigoUsuario);
    
    $objVenta->setDetalleVenta($datosJSONDetalle);
    
    $resultado = $objVenta->agregar();
    
    
    
    if($resultado == true){
        Funciones::imprimeJSON(200, "La venta ha sido registrada satisfactoriamente.", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
