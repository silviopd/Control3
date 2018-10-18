<?php
require_once '../datos/Conexion.clase.php';
class Sesion extends Conexion{

    private $email;
    private $clave;
    private $recordarUsuario;

    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function getRecordarUsuario() {
        return $this->recordarUsuario;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setRecordarUsuario($recordarUsuario) {
        $this->recordarUsuario = $recordarUsuario;
    }

    public function iniciarSesion() {
        try {
            $sql = "select
                            p.apellido_paterno,
                            p.apellido_materno,
                            p.nombres,
                            u.clave,
                            u.estado,
                            u.codigo_usuario,
                            c.descripcion as cargo
                    from
                            personal p 
                            inner join usuario u on (p.dni = u.dni_usuario)
                            inner join cargo c on  (p.codigo_cargo = c.codigo_cargo)
                    where
                            p.email = :p_email;";
//            Creamos una sentencia
            $sentencia = $this->dblink->prepare($sql);
//            Vinculamos el parametro p_email con el valor del atributo
            $sentencia->bindParam(":p_email", $this->getEmail());
//            Ejecutamos la sentencia
            $sentencia->execute();
            
            $resultado = $sentencia->fetch();
            
            if($resultado["clave"] == md5($this->getClave())){
//                aqui entra cuando la clave es correcta
                if($resultado["estado"] == "I"){
                    // usuario inactivo, No puede ingresar
                    return 0;
                }else{
                    // usuario activo, Si puede ingresar
                    session_name("sistemacomercial1");
                    session_start();
                    $_SESSION["s_nombre_usuario"] = $resultado["apellido_paterno"]." ".$resultado["apellido_materno"]." ".$resultado["nombres"];
                    $_SESSION["s_cargo"] = $resultado["cargo"];
                    $_SESSION["s_codigo_usuario"] = $resultado["codigo_usuario"];
                    if($this->getRecordarUsuario() == "S"){
//                        el usuario marco el check
                        setcookie("loginusuario", $this->getEmail(), 0 , "/");
                    }  else {
                        setcookie("loginusuario", "", 0 , "/");
                    }
                    return 1;
                }
            }else{
//                la clave ingresada por el ususario es diferente a la grabada en la BD
                return 2;
            }
                
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
