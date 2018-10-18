<?php 
    session_name("sistemacomercial1");
    session_start();
    
    if(!isset($_SESSION["s_nombre_usuario"])){
//        Esto se cumple cuando el usuario no ha iniciado sesion
        header("location:index.php");
        exit;
    }
    
    $nombreUsusario = ucwords(strtolower($_SESSION["s_nombre_usuario"]));
    $cargoUsuario = $_SESSION["s_cargo"];
    $codigoUsuario = $_SESSION["s_codigo_usuario"];
    if(file_exists("../imagenes/".$codigoUsuario.".png")){
        $fotoUsuario = $codigoUsuario.".png";
    }else{
        $fotoUsuario = "sin-foto.jpg";
    }