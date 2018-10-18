<?php

require_once '../negocio/Categoria.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (!isset($_POST["codigoCategoria"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
try {
    $objCategoria = new Categoria();
    $codigoArticulo = $_POST["codigoCategoria"];
    $resultado = $objCategoria->eliminar($codigoArticulo);
    if($resultado == true){
//        Se ha eliminado satisfactoriamente
        Funciones::imprimeJSON(200, "El registro se ha eliminado.", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

