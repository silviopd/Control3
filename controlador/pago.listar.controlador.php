<?php

require_once '../negocio/Pago.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    if (!isset($_POST["fecha1"]) || !isset($_POST["fecha2"]) || !isset($_POST["tipo"])) {
        Funciones::imprimeJSON(500, "Faltan parametros", "");
        exit;
    }
    $fecha1 = $_POST["fecha1"];
    $fecha2 = $_POST["fecha2"];
    $tipo = $_POST["tipo"];

    $objPago = new Pago();
    $resultado = $objPago->listar($fecha1, $fecha2, $tipo);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

