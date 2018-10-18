<?php
require_once '../negocio/Proveedor.clase.php';
require_once '../util/funciones/Funciones.clase.php';
if (!isset($_POST["rucProveedor"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
try {
    $objProveedor = new Proveedor();
    $rucProveedor = $_POST["rucProveedor"];
    $resultado = $objProveedor->eliminar($rucProveedor);
    if($resultado == true){
//        Se ha eliminado satisfactoriamente
        Funciones::imprimeJSON(200, "El registro se ha eliminado.", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
