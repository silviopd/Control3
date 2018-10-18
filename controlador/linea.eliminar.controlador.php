<?php
require_once '../negocio/Linea.clase.php';
require_once '../util/funciones/Funciones.clase.php';
if (!isset($_POST["codigoLinea"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
try {
    $objLinea = new Linea();
    $codigoLinea = $_POST["codigoLinea"];
    $resultado = $objLinea->eliminar($codigoLinea);
    if($resultado == true){
//        Se ha eliminado satisfactoriamente
        Funciones::imprimeJSON(200, "El registro se ha eliminado.", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
