$(document).ready(function () {
    listar();
});


$("#myModal").on("shown.bs.modal", function () {
    $("#txtdescripcion").focus();
});

$("#btnagregar").click(function () {
    $("#txttipooperacion").val("agregar");
    $("#txtcodigo").val("");
    $("#txtdescripcion").val("");
    $("#titulomodal").text("Agregar nueva Linea.");
});

function listar() {
    $.post("../controlador/linea.listar.controlador.php", {}).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            var html = "";

            html += '<small>';
            html += '<table id="tabla-listado" class="table table-bordered table-striped">';
            html += '<thead>';
            html += '<tr style="background-color: #ededed; height:25px;">';
            html += '<th>N</th>';
            html += '<th>CODIGO</th>';
            html += '<th>NOMBRE DE LINEA</th>';
            html += '<th style="text-align: center">OPCIONES</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            //Detalle
            $.each(datosJSON.datos, function (i, item) {
                html += '<tr>';
                html += '<td>' + i + '</td>';
                html += '<td align="center">' + item.codigo_linea + '</td>';
                html += '<td>' + item.descripcion + '</td>';
                html += '<td align="center">';
                html += '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal" onclick="leerDatos(' + item.codigo_linea + ')"><i class="fa fa-pencil"></i></button>';
                html += '&nbsp;&nbsp;';
                if (item.estado) {
                    html += '<button type="button" disabled="true" id="tblbtneliminar' + item.codigo_linea + '" class="btn btn-danger btn-xs" onclick="eliminar(' + item.codigo_linea + ')"><i class="fa fa-close"></i></button>';
                } else {
                    html += '<button type="button" id="tblbtneliminar' + item.codigo_linea + '" class="btn btn-danger btn-xs" onclick="eliminar(' + item.codigo_linea + ')"><i class="fa fa-close"></i></button>';
                }
                html += '</td>';
                html += '</tr>';
//alert(item.estado);
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

function eliminar(codigoLinea) {
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
                            "../controlador/linea.eliminar.controlador.php",
                            {
                                codigoLinea: codigoLinea
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
    }, function (isConfirm) {
        if (isConfirm) { //el usuario hizo clic en el boton SI     
            $.post(
                    "../controlador/linea.agregar.editar.controlador.php",
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

function leerDatos(codigoLinea) {
    $.post(
            "../controlador/linea.leer.datos.controlador.php",
            {p_codigoLinea: codigoLinea}
    ).done(function (resultado) {
        var datosJSON = resultado;

        if (datosJSON.estado === 200) {
            //asignar datos
            $.each(datosJSON.datos, function (i, item) {
                $("#txttipooperacion").val("editar");
                $("#txtcodigo").val(item.codigo_linea);
                $("#txtdescripcion").val(item.descripcion);
                $("#titulomodal").text("Editar articulo.");
            });
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}
