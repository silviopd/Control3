<?php
require_once '../negocio/Categoria.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if(!isset($_POST["p_datosFormulario"])){
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
$datosFormulario = $_POST["p_datosFormulario"];
parse_str($datosFormulario, $datosFormularioArray);
try {
    $objCategoria = new Categoria();
    $objCategoria->setDescripcion($datosFormularioArray["txtdescripcion"]);
    $objCategoria->setCodigoLinea($datosFormularioArray["cbolineamodal"]);
    
    if($datosFormularioArray["txttipooperacion"]=="agregar"){
        $resultado = $objCategoria->agregar();
        if($resultado == true){
            Funciones::imprimeJSON(200, "Grabado con exito.", "");
        }
    }else{
        $objCategoria->setCodigoCategoria($datosFormularioArray["txtcodigo"]);
        $resultado = $objCategoria->editar();
        if($resultado == true){
            Funciones::imprimeJSON(200, "Grabado con exito.", "");
        }
    }
} catch (Exception $ex) {
    Funciones::imprimeJSON(500, $ex->getMessage(), "");
}
exit();
