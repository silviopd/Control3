<?php
require_once '../negocio/Categoria.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (!isset($_POST["p_codigoCategoria"])) {
    Funciones::imprimeJSON(500, "Faltan parametros lol", "");
    exit();
}

try {
    $objCategoria = new Categoria();
    $codigoCategoria = $_POST["p_codigoCategoria"];
    $resultado = $objCategoria->leerDatos($codigoCategoria);
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}

