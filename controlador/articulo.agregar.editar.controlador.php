<?php

require_once '../negocio/Articulo.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if(!isset($_POST["p_datosFormulario"])){
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}

$datosFormulario = $_POST["p_datosFormulario"];

parse_str($datosFormulario, $datosFormularioArray);
//
//print_r($datosFormularioArray); // convertir todos los datos que llegan concatenados a un array

try {
    $objArticulo = new Articulo();
    $objArticulo->setNombre($datosFormularioArray["txtnombre"]);
    $objArticulo->setPrecioVenta($datosFormularioArray["txtprecio"]);
    $objArticulo->setCodigoCategoria($datosFormularioArray["cbocategoriamodal"]);
    $objArticulo->setCodigoMarca($datosFormularioArray["cbomarcamodal"]);
    if($datosFormularioArray["txttipooperacion"]=="agregar"){
        $resultado = $objArticulo->agregar();
        if($resultado == true){
            Funciones::imprimeJSON(200, "Grabado con exito.", "");
        }
    }else{
        $objArticulo->setCodigoArticulo($datosFormularioArray["txtcodigo"]);
        $resultado = $objArticulo->editar();
        if($resultado == true){
            Funciones::imprimeJSON(200, "Grabado con exito.", "");
        }
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
//las dos siguientes lineas sirven para ver si los datos estan llegando al formulario
//echo $datosFormulario;
exit();
//hasta aqui...

//PDO::begingTransaction() in <b>/Applications/XAMPP/xamppfiles/htdocs/Comercial/negocio/Articulo.clase.php</b> on line <b>85</b><br />

