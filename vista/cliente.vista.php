<?php
//require_once 'sesion.validar.vista.php';
require_once '../util/funciones/definiciones.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo C_NOMBRE_SOFTWARE; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <?php
        include 'estilos.vista.php';
        ?>

    </head>
    <body class="skin-green layout-top-nav">
        <!-- Site wrapper -->
        <div class="wrapper">

            <?php
            include 'cabecera.vista.php';
            ?>

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 class="text-bold text-black" style="font-size: 20px;">Mantenimiento de cliente</h1>
                </section>

                <section class="content">
                    <!-- INICIO del formulario modal -->
                    <small>
                        <form id="frmgrabar">
                            <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="titulomodal">Título de la ventana</h4>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="txttipooperacion" id="txttipooperacion" class="form-control">
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <p>Código cliente<input type="text" name="txtcodigocliente" id="txtcodigocliente" class="form-control input-sm text-center text-bold" placeholder="" readonly=""></p>
                                                </div>
                                                <div class = "col-xs-4">
                                                    <p>Documento de identidad <font color = "red">*</font>
                                                    <input type="text" name="txtnrodocumentoidentidad" id="txtnrodocumentoidentidad" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                            </div>
                                            <div class = "row">
                                                <div class = "col-xs-4">
                                                    <p>Nombres <font color = "red">*</font>
                                                    <input type="text" name="txtnombres" id="txtnombres" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                                <div class = "col-xs-4"> 
                                                    <p>Apellido Paterno <font color = "red">*</font>
                                                    <input type="text" name="txtapellidopaterno" id="txtapellidopaterno" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                                <div class = "col-xs-4">
                                                    <p>Apellido Materno <font color = "red">*</font>
                                                    <input type="text" name="txtapellidomaterno" id="txtapellidomaterno" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                            </div>
                                            <p>Direccion <font color = "red">*</font>
                                            <input type="text" name="txtdireccion" id="txtdireccion" class="form-control input-sm" placeholder="" required=""><p>
                                            <div class = "row">
                                                <div class = "col-xs-4">
                                                    <p>Telefono Fijo <font color = "red">*</font>
                                                    <input type="text" name="txttelefonofijo" id="txttelefonofijo" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                                <div class = "col-xs-4"> 
                                                    <p>Telefono Movil 1 <font color = "red">*</font>
                                                    <input type="text" name="txttelefonomovil1" id="txttelefonomovil1" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                                <div class = "col-xs-4">
                                                    <p>Telefono Movil 2 <font color = "red">*</font>
                                                    <input type="text" name="txttelefonomovil2" id="txttelefonomovil2" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                            </div>

                                            <div class = "row">
                                                <div class = "col-xs-6">
                                                    <p>Email <font color = "red">*</font>
                                                    <input type="text" name="txtemail" id="txtemail" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                                <div class = "col-xs-6"> 
                                                    <p>Direccion Web <font color = "red">*</font>
                                                    <input type="text" name="txtdireccionweb" id="txtdireccionweb" class="form-control input-sm" placeholder="" required=""><p>
                                                </div>
                                            </div>

                                            <div class = "row">
                                                <div class = "col-xs-4">
                                                    <p>Departamento <font color = "red">*</font>
                                                        <select class="form-control input-sm" name="cbodepartamentomodal" id="cbodepartamentomodal" required="" ></select>
                                                    </p>
                                                </div>
                                                <div class = "col-xs-4"> 
                                                    <p>Provincia <font color = "red">*</font>
                                                        <select class="form-control input-sm" name="cboprovinciamodal" id="cboprovinciamodal" required="" ></select>
                                                    </p>
                                                </div>
                                                <div class = "col-xs-4"> 
                                                    <p>Distrito <font color = "red">*</font>
                                                        <select class="form-control input-sm" name="cbodistritomodal" id="cbodistritomodal" required="" ></select>
                                                    </p>
                                                </div>
                                            </div>
                                            <p>
                                                <font color = "red">* Campos obligatorios</font>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success" aria-hidden="true"><i class="fa fa-save"></i> Grabar</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal" id="btncerrar"><i class="fa fa-close"></i> Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </small>
                    <!-- FIN del formulario modal -->

                    <div class="row">
                        <div class="col-xs-3">
                            <select id="cbodepartamento" class="form-control input-sm"></select>
                        </div>
                        <div class="col-xs-3">
                            <select id="cboprovincia" class="form-control input-sm"></select>
                        </div>
                        <div class="col-xs-3">
                            <select id="cbodistrito" class="form-control input-sm"></select>
                        </div>
                        <div class="col-xs-3">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" id="btnagregar"><i class="fa fa-copy"></i> Agregar nuevo cliente</button>
                        </div>
                    </div>
                    <p>
                    <div class="box box-success">
                        <div class="box-body">
                            <div id="listado">
                            </div>
                        </div>
                    </div>
                    </p>
                </section>
            </div>
        </div><!-- ./wrapper -->
        <?php
        include 'scripts.vista.php';
        ?>

        <!--JS-->
        <script src="js/cliente.js" type="text/javascript"></script>

    </body>
</html>