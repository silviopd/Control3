<?php
require_once '../negocio/Cliente.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    $dni = $_POST["dni"];
    $objCliente = new Cliente();
    $resultado = $objCliente->listar($dni);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
//    Funciones::mensaje($ex->getMessage(), "e");
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
