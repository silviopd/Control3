<?php
require_once '../negocio/Proveedor.clase.php';
require_once '../util/funciones/Funciones.clase.php';
if (!isset($_POST["p_rucProveedor"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
try {
    $objProveedor = new Proveedor();
    $rucProveedor = $_POST["p_rucProveedor"];
    $resultado = $objProveedor->leerDatos($rucProveedor);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}