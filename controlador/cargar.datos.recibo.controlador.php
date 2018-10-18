<?php
require_once '../negocio/Cliente.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    $numeroRecibo = $_POST["numeroRecibo"];
    $objCliente = new Cliente();
    $resultado = $objCliente->cargarDatosRecibos($numeroRecibo);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
//    Funciones::mensaje($ex->getMessage(), "e");
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
