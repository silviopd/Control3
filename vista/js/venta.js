$(document).ready(function () {
    cargarComboTC("#cbotipocomp", "seleccione");
    cargarIgv();
});



$("#btnregresar").click(function () {
    document.location.href = "pago.listado.vista.php";
});

$("#txtcantidad").keypress(function (evento) {
    if (evento.which === 13) {
        evento.preventDefault();
        $("#btnagregar").click();
    } else {
        return validarNumeros(evento);
    }
});


$("#cbotipocomp").change(function () {
    var tc = $("#cbotipocomp").val();
    cargarComboSerie("#cboserie", "seleccione", tc);
});

$("#cboserie").change(function () {
    cargarNumeroComprobante();
    $("#txtnombrecliente").focus();
});

function setDescuentos(){
    
}

function cargarIgv() {
    $.post(
            "../controlador/igv.leer.controlador.php", {
                p_codigoParametro: 1
            }).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            var igv = datosJSON.datos;
            $("#txtigv").val(igv);
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}

$("#btnagregar").click(function () {
    if ($("#txtcodigoarticulo").val().toString() === "") {
        swal("Error", "Existe un campo vacio.", "warning");
        $("#txtarticulo").focus();
        return 0;
    }
    
    var codigoArticulo = $("#txtcodigoarticulo").val();
    var nombreArticulo = $("#txtarticulo").val();
    var precioVenta = $("#txtprecio").val();
    var cantidad = $("#txtcantidad").val();
    var stock = $("#txtstock").val();
    if (cantidad == false) {
        cantidad = 1;
    }
    if(stock < cantidad){
        swal("Error", "Stock insuficiente", "warning");
        limpiarDatosArticulo();
        return;
    }
    var importe = precioVenta * cantidad;

    var fila = '<tr>' +
            '<td class="text-center" >' + codigoArticulo + '</td>' +
            '<td>' + nombreArticulo + '</td>' +
            '<td class="text-right" >' + precioVenta + '</td>' +
            '<td class="text-right" >' + cantidad + '</td>' +
            '<td class="text-right" >' + importe + '</td>' +
            '<td id="celiminar" align="center"> <a class="btn btn-danger btn-xs" href="#"> <i class="fa fa-close"></i> </a> </td>' +
            '</tr>';
    $("#detalleventa").append(fila);
    calcularTotales();
    limpiarDatosArticulo();
});

function calcularTotales() {
    var subTotal = 0.0;
    var igv = 0.0;
    var neto = 0.0;
    var valorigv = 1 + (parseFloat($("#txtigv").val()) / 100);
    $("#detalleventa tr").each(function () {
        var importe = $(this).find("td").eq(4).html();
        neto = neto + parseFloat(importe);
    });
    subTotal = neto / valorigv;
    igv = neto - subTotal;


    $("#txtimporteneto").val(neto.toFixed(2));
    $("#txtimportesubtotal").val(subTotal.toFixed(2));
    $("#txtimporteigv").val(igv.toFixed(2));
}

function limpiarDatosArticulo() {
    $("#txtarticulo").val("");
    $("#txtprecio").val("");
    $("#txtcantidad").val("");
    $("#txtstock").val("");
    $("#txtarticulo").focus();
}

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
                    calcularTotales();
                    $("#txtarticulo").focus();
                }
            });
});

var arrayDetalle = new Array();


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
                    $("#detalleventa tr").each(function(){
                        //recorremos cada fila de la tabla donde estan los articulos vendidos.
                        var codigoArticulo = $(this).find("td").eq(0).html();
                        var cantidad = $(this).find("td").eq(3).html();
                        var precio = $(this).find("td").eq(2).html();
                        var importe = $(this).find("td").eq(4).html();
                        
                        
                        var objDetalle = new Object();
                        objDetalle.codigoArticulo = codigoArticulo;
                        objDetalle.cantidad = cantidad;
                        objDetalle.precio = precio;
                        objDetalle.importe = importe;
                        
                        arrayDetalle.push(objDetalle);//añadimos los datos al arreglo
                        
                    });
                    var jsonDetalle = JSON.stringify(arrayDetalle);
                    $.post(
                            "../controlador/venta.agregar.controlador.php",
                            {p_datosFormulario: $("#frmgrabar").serialize(), p_datosJSONDetalle: jsonDetalle}
                    ).done(function (resultado) {
                        var datosJSON = resultado;
                        if (datosJSON.estado === 200) {
                            swal("Exito", datosJSON.mensaje, "success");
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

