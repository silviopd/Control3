<?php
require_once '../negocio/Categoria.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
    
    if(!isset($_POST["codigoLinea"] )){
        Funciones::imprimeJSON(500, "Faltan parametros", "");
//        El error 500 en http significa que ha ocurrido un error.
        exit;
    }
    
    $codigoLinea = $_POST["codigoLinea"];
    $objCategoria = new Categoria();
    $resultado = $objCategoria->cargarListaDatos($codigoLinea);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
//    Funciones::mensaje($ex->getMessage(), "e");
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

