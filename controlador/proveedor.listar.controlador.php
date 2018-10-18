<?php
require_once '../negocio/Proveedor.clase.php';
require_once '../util/funciones/Funciones.clase.php';
try {
//    $ruc = $_POST["ruc"];
//    $razonSocial = $_POST["razonSocial"];
//    $direccion = $_POST["direccion"];
//    $telefono = $_POST["telefono"];
//    $representanteLegal = $_POST["representanteLegal"];
//    
    $objProveedor = new Proveedor();
    $resultado = $objProveedor->cargarListaDatos();
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
//    Funciones::mensaje($ex->getMessage(), "e");
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
