<?php

require_once '../negocio/SerieCOmprobante.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    $p_tipoComprobante = $_POST["p_tipoComprobante"];
    $obj = new SerieComprobante();
    $resultado = $obj->cargarSerie($p_tipoComprobante);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
