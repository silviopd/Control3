<?php

require_once '../negocio/SerieComprobante.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    $tipoComprobante = $_POST["p_tipoComprobante"];
    $serie = $_POST["p_serie"];
    $objSerie = new SerieComprobante();
    $resultado = $objSerie->cargarNroDocumento($tipoComprobante, $serie);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

