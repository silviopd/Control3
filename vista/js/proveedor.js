$(document).ready(function () {
    listar();
});

$("#btnagregar").click(function () {
    $("#txttipooperacion").val("agregar");
    $("#txtrucproveedor").val("");
    $("#txtrazonsocial").val("");
    $("#txtdireccion").val("");
    $("#txttelefono").val("");
    $("#txtrepresentantelegal").val("");
    $("#titulomodal").text("Agregar nuevo proveedor.");
});

function listar() {
    $.post("../controlador/proveedor.listar.controlador.php", {}).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            var html = "";

            html += '<small>';
            html += '<table id="tabla-listado" class="table table-bordered table-striped">';
            html += '<thead>';
            html += '<tr style="background-color: #ededed; height:25px;">';
            html += '<th>N</th>';
            html += '<th>RUC</th>';
            html += '<th>RAZON SOCIAL</th>';
            html += '<th>DIRECCION</th>';
            html += '<th>TELEFONO</th>';
            html += '<th>REPRESENTANTE LEGAL</th>';
            html += '<th style="text-align: center">OPCIONES</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            //Detalle
            $.each(datosJSON.datos, function (i, item) {
                html += '<tr>';
                html += '<td>' + i + '</td>';
                html += '<td align="center">' + item.ruc_proveedor + '</td>';
                html += '<td>' + item.razon_social + '</td>';
                html += '<td>' + item.direccion + '</td>';
                html += '<td>' + item.telefono + '</td>';
                html += '<td>' + item.representante_legal + '</td>';
                html += '<td align="center">';
                html += '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal" onclick="leerDatos(' + item.ruc_proveedor + ')"><i class="fa fa-pencil"></i></button>';
                html += '&nbsp;&nbsp;';
                html += '<button type="button" class="btn btn-danger btn-xs" onclick="eliminar(' + item.ruc_proveedor + ')"><i class="fa fa-close"></i></button>';
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

function eliminar(rucProveedor) {
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
    },function (isConfirm) {
        if (isConfirm) {
            $.post(
                    "../controlador/proveedor.eliminar.controlador.php",
                    {rucProveedor: rucProveedor}
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
                if (isConfirm) { 
                    $.post(
                            "../controlador/proveedor.agregar.editar.controlador.php",
                            {p_datosFormulario: $("#frmgrabar").serialize()}
                    ).done(function (resultado) {
                        var datosJSON = resultado;
                        if (datosJSON.estado === 200) {
                            swal("Exito", datosJSON.mensaje, "success");
                            $("#btncerrar").click();
                            listar();
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

function leerDatos(rucProveedor) {
    $.post(
            "../controlador/proveedor.leer.datos.controlador.php",
            {p_rucProveedor: rucProveedor}
    ).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            $.each(datosJSON.datos, function (i, item) {
                $("#txttipooperacion").val("editar");
                $("#txtrucproveedor").val(item.ruc_proveedor);
                $("#txtrazonsocial").val(item.razon_social);
                $("#txttelefono").val(item.telefono);
                $("#txtdireccion").val(item.direccion);
                $("#txtrepresentantelegal").val(item.representante_legal);
                $("#titulomodal").text("Editar proveedor.");
            });
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });

}
