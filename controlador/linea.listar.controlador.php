<?php
require_once '../negocio/Linea.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    $objLinea = new Linea();
    $resultado = $objLinea->cargarListaDatos();
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
//    Funciones::mensaje($ex->getMessage(), "e");
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
