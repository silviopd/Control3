<?php

require_once '../negocio/Articulo.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (!isset($_POST["codigoArticulo"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
try {
    $objArti = new Articulo();
    $codigoArticulo = $_POST["codigoArticulo"];
    $resultado = $objArti->eliminar($codigoArticulo);
    if($resultado == true){
//        Se ha eliminado satisfactoriamente
        Funciones::imprimeJSON(200, "El registro se ha eliminado.", "");
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
