$(document).ready(function () {
    cargarComboLinea("#cbolinea", "todos");
    cargarComboLinea("#cbolineamodal", "seleccione");
    listar();
});

$("#cbolinea").change(function () {
    listar();
});

function listar() {
    var codigoLinea = $("#cbolinea").val();
    if (codigoLinea === null) {
        codigoLinea = 0;
    }
    $.post("../controlador/categoria.listar.controlador.php", {
        codigoLinea: codigoLinea
    }).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            var html = ""
            html += '<small>';
            html += '<table id="tabla-listado" class="table table-bordered table-striped">';
            html += '<thead>';
            html += '<tr style="background-color: #ededed; height:25px;">';
            html += '<th>N</th>';
            html += '<th>CODIGO</th>';
            html += '<th>NOMBRE DE CATEGORIA</th>';
            html += '<th>LINEA</th>';
            html += '<th style="text-align: center">OPCIONES</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            //Detalle
            $.each(datosJSON.datos, function (i, item) {
                html += '<tr>';
                html += '<td>' + i + '</td>';
                html += '<td align="center">' + item.codigo_categoria + '</td>';
                html += '<td>' + item.descripcion + '</td>';
                html += '<td>' + item.linea + '</td>';
                html += '<td align="center">';
                html += '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal" onclick="leerDatos(' + item.codigo_categoria + ')"><i class="fa fa-pencil"></i></button>';
                html += '&nbsp;&nbsp;';
                if (item.estado) {
                    html += '<button type="button" disabled="true" id="tblbtneliminar' + item.codigo_categoria + '" class="btn btn-danger btn-xs" onclick="eliminar(' + item.codigo_categoria + ')"><i class="fa fa-close"></i></button>';
                } else {
                    html += '<button type="button" id="tblbtneliminar' + item.codigo_categoria + '" class="btn btn-danger btn-xs" onclick="eliminar(' + item.codigo_categoria + ')"><i class="fa fa-close"></i></button>';
                }
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';
            html += '</small>';

            $("#listado").html(html);

            $('#tabla-listado').dataTable({
                "aaSorting": [[2, "asc"]]
            });

        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}

function cargarComboLinea(p_nombreCombo, p_tipo) {
    $.post(
            "../controlador/linea.cargar.combo.controlador.php"
            ).done(function (resultado) {
        var datosJSON = resultado;

        if (datosJSON.estado === 200) {
            var html = "";
            if (p_tipo === "seleccione") {
                html += '<option value="">Seleccione una linea</option>';
            } else {
                html += '<option value="0">Todas las lineas</option>';
            }


            $.each(datosJSON.datos, function (i, item) {
                html += '<option value="' + item.codigo_linea + '">' + item.descripcion + '</option>';
            });

            $(p_nombreCombo).html(html);
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}

function eliminar(codigoCategoria) {
    swal({
        title: "Confirme",
        text: "¿Esta seguro de eliminar el registro seleccionado?",
        showCancelButton: true,
        confirmButtonColor: '#d93f1f',
        confirmButtonText: 'Si',
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true,
        imageUrl: "../imagenes/eliminar.png"
    },
            function (isConfirm) {
                if (isConfirm) {
                    $.post(
                            "../controlador/categoria.eliminar.controlador.php",
                            {
                                codigoCategoria: codigoCategoria
                            }
                    ).done(function (resultado) {
                        var datosJSON = resultado;
                        if (datosJSON.estado === 200) { //ok
                            listar();
                            swal("Exito", datosJSON.mensaje, "success");
                        }

                    }).fail(function (error) {
                        var datosJSON = $.parseJSON(error.responseText);
                        swal("Error", datosJSON.mensaje, "error");
                    });
                }
            });
}

$("#frmgrabar").submit(function (evento) {
    evento.preventDefault();
    swal({
        title: "Confirme",
        text: "¿Esta seguro de grabar los datos ingresados?",
        showCancelButton: true,
        confirmButtonColor: '#3d9205',
        confirmButtonText: 'Si',
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true,
        imageUrl: "../imagenes/pregunta.png"
    },
            function (isConfirm) {
                if (isConfirm) { //el usuario hizo clic en el boton SI     
                    //procedo a grabar
                    $.post(
                            "../controlador/categoria.agregar.editar.controlador.php",
                            {p_datosFormulario: $("#frmgrabar").serialize()}
                    ).done(function (resultado) {
                        var datosJSON = resultado;

                        if (datosJSON.estado === 200) {
                            swal("Exito", datosJSON.mensaje, "success");
                            $("#btncerrar").click(); //cerrar ventana
                            listar();//refrescar los datos

                        } else {
                            swal("Mensaje del sistema", resultado, "warning");
                        }

                    }).fail(function (error) {
                        var datosJSON = $.parseJSON(error.responseText);
                        swal("Error", datosJSON.mensaje, "error");
                    });
                }
            });
});

function leerDatos(codigoCategoria) {
    $.post(
            "../controlador/categoria.leer.datos.controlador.php",
            {p_codigoCategoria: codigoCategoria}
    ).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            //asignar datos
            $.each(datosJSON.datos, function (i, item) {
                $("#txttipooperacion").val("editar");
                $("#txtcodigo").val(item.codigo_categoria);
                $("#txtdescripcion").val(item.descripcion);
                $("#cbolineamodal").val(item.codigo_linea);
                $("#titulomodal").text("Editar Categoria.");
            });
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}

$("#btnagregar").click(function () {
    $("#txttipooperacion").val("agregar");
    $("#txtcodigo").val("");
    $("#txtdescripcion").val("");

    $("#titulomodal").text("Agregar nueva categoria.");
});

$("#myModal").on("shown.bs.modal", function () {
    $("#txtdescripcion").focus();
});