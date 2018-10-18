<?php
//require_once 'sesion.validar.vista.php';
require_once '../util/funciones/definiciones.php';
//date_default_timezone_set("America/Lima");
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
        <!-- AutoCompletar-->
        <link href="../util/jquery/jquery.ui.css" rel="stylesheet">

    </head>
    <body class="skin-green layout-top-nav">
        <!-- Site wrapper -->
        <div class="wrapper">
            <?php
            include 'cabecera.vista.php';
            ?>

            <form id="frmgrabar">
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1 class="text-bold text-black" style="font-size: 20px;">Registrar nueva venta</h1>
                        <ol class="breadcrumb">
                            <button type="button" class="btn btn-danger btn-sm" id="btnregresar">Regresar</button>
                            <button type="submit" class="btn btn-primary btn-sm">Registrar la venta</button>
                        </ol>
                    </section>
                    <small>
                        <section class="content">
                            <div class="box box-success">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div class="form-group">
                                                <label>DNI</label>
                                                <input type="text" class="form-control input-sm" id="txtdni" name="txtdni"/>
                                            </div>
                                        </div>
                                    </div><!-- /row -->
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control input-sm" readonly="" id="txtnombrecliente" name="txtnombrecliente">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Direccion</label>
                                                <input type="text" class="form-control input-sm" readonly="" id="txtdireccion" name="txtdireccion" >
                                            </div>
                                        </div>
                                    </div>

                                    <!-- /row -->
                                </div>
                            </div>


                            <div class="box box-success">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <label>Fecha</label>
                                                <input type="date" class="form-control input-sm" id="txtfecha" name="txtfecha" value="<?php echo date('Y-m-d'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <label>Seleccione el numero telefonico</label>
                                            <select id="cbolinea" class="form-control input-sm"></select>
                                        </div>
                                        <div class="col-xs-3">
                                            <label>Seleccione un recibo</label>
                                            <select id="cborecibo" class="form-control input-sm"></select>
                                        </div>
                                        <div class="col-xs-1">
                                            <div class="form-group">
                                                <label>Importe</label>
                                                <input type="text" class="form-control input-sm" id="txtimporte" readonly="" />
                                                <input type="hidden" name="txtfechaVencimiento" id="txtfechaVencimiento" class="form-control">
                                                <input type="hidden" name="txtnumerorecibo" id="txtnumerorecibo" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <br>
                                                <button type="button" class="btn btn-danger btn-sm" id="btnagregar">Agregar al detalle</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table id="tabla-listado" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>N. Recibo</th>
                                                        <th>Fecha Vencimiento</th>
                                                        <th style="text-align: right">Importe Pagado</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detallepago">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-success">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">TOTAL PAGADO:</span>
                                                <input type="text" class="form-control text-right text-bold" id="txtimportesubtotal" name="txtimportesubtotal" readonly="" style="width: 100px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </section>
                    </small>
                </div>
            </form>
        </div><!-- ./wrapper -->
        <?php
        include 'scripts.vista.php';
        ?>

        <!-- AutoCompletar -->
        <script src="../util/jquery/jquery.ui.js"></script>

        <!--JS-->
        <script src="js/cliente.js" type="text/javascript"></script>
        <script src="js/util.js"></script>
    </body>
</html>