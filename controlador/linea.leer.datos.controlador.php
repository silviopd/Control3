<?php
require_once '../negocio/Linea.clase.php';
require_once '../util/funciones/Funciones.clase.php';
if(!isset($_POST["p_codigoLinea"])){
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}

try {
    $objLinea = new Linea();
    $codigoLinea = $_POST["p_codigoLinea"];
    $resultado =  $objLinea->leerDatos($codigoLinea);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

