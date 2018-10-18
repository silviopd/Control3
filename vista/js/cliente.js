$("#txtdni").keypress(function (evento) {
    if (evento.which === 13) {
        evento.preventDefault();
        listar();
    } else {
        return validarNumeros(evento);
    }

});

$("#btnregresar").click(function () {
    document.location.href = "pago.listado.vista.php";
});

$("#cbolinea").change(function () {
    var numero = $("#cbolinea").val();
    cargarRecibos("#cborecibo", "seleccione", numero);
});

$("#cborecibo").change(function () {
    var numero = $("#cborecibo").val();
    cargardatosRecibo(numero);
});

function listar() {
    var dni = $("#txtdni").val();
    if (dni === "") {
        swal("Error", "Ingrese un numero de DNI", "error");
    }

    $.post("../controlador/cliente.listar.controlador.php",
            {dni: dni}
    ).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            $.each(datosJSON.datos, function (i, item) {
                $("#txtnombrecliente").val(item.nombre);
                $("#txtdireccion").val(item.direccion);
            });
            cargarLineas("#cbolinea", "seleccione", dni);
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}


function cargarLineas(p_nombreCombo, p_tipo, dni) {
    $.post("../controlador/lineas.cargar.controlador.php",
            {
                dni: dni
            }
    ).done(function (resultado) {
        var datosJSON = $.parseJSON(JSON.stringify(resultado));

        if (datosJSON.estado === 200) {
            var html = "";
            if (p_tipo === "seleccione") {
                html += '<option value="">Seleccione un numero</option>';
            } else {
                html += '<option value="0">Todos los numeros</option>';
            }

            $.each(datosJSON.datos, function (i, item) {
                html += '<option value="' + item.numero_linea_telefonica + '">' + item.numero_linea_telefonica + '</option>';
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

function cargarRecibos(p_nombreCombo, p_tipo, numeroTelefonico) {
    $.post("../controlador/recibo.listar.controlador.php",
            {
                numeroTelefonico: numeroTelefonico
            }
    ).done(function (resultado) {
        var datosJSON = $.parseJSON(JSON.stringify(resultado));

        if (datosJSON.estado === 200) {
            var html = "";
            if (p_tipo === "seleccione") {
                html += '<option value="">Seleccione un recibo</option>';
            } else {
                html += '<option value="0">Todos los recibos</option>';
            }

            $.each(datosJSON.datos, function (i, item) {
                html += '<option value="' + item.numero_recibo + '"> Recibo: ' + item.numero_recibo + '/ F. Venc: ' + item.fecha_vencimiento_deuda + '</option>';
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

function cargardatosRecibo(numeroRecibo) {
    $.post("../controlador/cargar.datos.recibo.controlador.php",
            {
                numeroRecibo: numeroRecibo
            }
    ).done(function (resultado) {
        var datosJSON = $.parseJSON(JSON.stringify(resultado));
        if (datosJSON.estado === 200) {
            $.each(datosJSON.datos, function (i, item) {
                $("#txtfechaVencimiento").val(item.fecha_vencimiento_deuda);
                $("#txtnumerorecibo").val(item.numero_recibo);
                $("#txtimporte").val(item.importe);
            });

        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}
//cargar.datos.recibo.controlador.php

$("#btnagregar").click(function () {
    if ($("#txtimporte").val().toString() === "") {
        swal("Error", "Existe un campo vacio.", "warning");
        return 0;
    }
    if ($("#txtfecha").val().toString() === "") {
        swal("Error", "Existe un campo vacio.", "warning");
        return 0;
    }

    var numeroRecibo = $("#txtnumerorecibo").val();
    var fechaVencimiento = $("#txtfechaVencimiento").val();
    var importe = $("#txtimporte").val();

    var fila = '<tr>' +
            '<td class="text-center" >' + numeroRecibo + '</td>' +
            '<td>' + fechaVencimiento + '</td>' +
            '<td class="text-right" >' + importe + '</td>' +
            '<td id="celiminar" align="center"> <a class="btn btn-danger btn-xs" href="#"> <i class="fa fa-close"></i> </a> </td>' +
            '</tr>';
    $("#detallepago").append(fila);
    calcularTotales();
});

$(document).on("click", "#celiminar", function () {
    var filaEliminar = $(this).parents().get(0);//capturar la fila que se desea eliminar
    swal({
        title: "Confirme",
        text: "¿Desea eliminar el registro seleccionado?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ff0000',
        confirmButtonText: 'Si',
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    },
            function (isConfirm) {
                if (isConfirm) { //el usuario hizo clic en el boton SI     
                    filaEliminar.remove();
                }
            });
});
var arrayDetalle = new Array();

function calcularTotales() {

    var neto = 0.0;
    $("#detallepago tr").each(function () {
        var importe = $(this).find("td").eq(2).html();
        neto = neto + parseFloat(importe);
    });
    $("#txtimportesubtotal").val(neto.toFixed(2));
}


$("#frmgrabar").submit(function (evento) {
    evento.preventDefault();
    swal({
        title: "Confirme",
        text: "¿Esta seguro de grabar la venta?",
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

                    /* Capturar todos los datos necesarios para grabar en el venta detalle */

                    arrayDetalle.splice(0, arrayDetalle.length);//limpiar el array
                    $("#detallepago tr").each(function () {
                        //recorremos cada fila de la tabla donde estan los articulos vendidos.
                        var numeroRecibo = $(this).find("td").eq(0).html();
                        var fechaVencimiento = $(this).find("td").eq(1).html();
                        var importe = $(this).find("td").eq(2).html();


                        var objDetalle = new Object();
                        objDetalle.numero_recibo = numeroRecibo;
                        objDetalle.fechaVencimiento = fechaVencimiento;
                        objDetalle.importe = importe;
                        arrayDetalle.push(objDetalle);//añadimos los datos al arreglo

                    });

                    var jsonDetalle = JSON.stringify(arrayDetalle);
                    $.post("../controlador/pago.agregar.controlador.php",
                            {p_datosFormulario: $("#frmgrabar").serialize(), p_datosJSONDetalle: jsonDetalle}
                    ).done(function (resultado) {
                        var datosJSON = resultado;
                        if (datosJSON.estado === 200) {
                            document.location.href = "pago.listado.vista.php";
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
