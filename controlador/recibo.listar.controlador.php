<?php

require_once '../negocio/Cliente.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    $numeroTelefonico = $_POST["numeroTelefonico"];
    $objCliente = new Cliente();
    $resultado = $objCliente->cargarRecibos($numeroTelefonico);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
//    Funciones::mensaje($ex->getMessage(), "e");
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

