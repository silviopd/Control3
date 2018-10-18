<?php
require_once '../negocio/Proveedor.clase.php';
require_once '../util/funciones/Funciones.clase.php';
if(!isset($_POST["p_datosFormulario"])){
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
$datosFormulario = $_POST["p_datosFormulario"];
parse_str($datosFormulario, $datosFormularioArray);
try {
    $objProveedor = new Proveedor();
    $objProveedor->setRucProveedor($datosFormularioArray["txtrucproveedor"]);
    $objProveedor->setRazonSocial($datosFormularioArray["txtrazonsocial"]);
    $objProveedor->setDireccion($datosFormularioArray["txtdireccion"]);
    $objProveedor->setTelefono($datosFormularioArray["txttelefono"]);
    $objProveedor->setRepresentanteLegal($datosFormularioArray["txtrepresentantelegal"]);
    if($datosFormularioArray["txttipooperacion"]=="agregar"){
        $resultado = $objProveedor->agregar();
        if($resultado == true){
            Funciones::imprimeJSON(200, "Grabado con exito.", "");
        }
    }else{
        $objProveedor->setRucProveedor($datosFormularioArray["txtrucproveedor"]);
        $resultado = $objProveedor->editar();
        if($resultado == true){
            Funciones::imprimeJSON(200, "Grabado con exito.", "");
        }
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
exit();