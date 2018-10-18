<?php
require_once '../negocio/Configuracion.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    $objConf = new Configuracion();
    $codigoParametro = $_POST["p_codigoParametro"];
    $resultado = $objConf->leerIgv($codigoParametro);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}