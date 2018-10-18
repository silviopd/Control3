$(document).ready(function () {
    listar();
});


$("#btnagregar").click(function () {
    document.location.href = "pago.vista.php";
});

$("#btnfiltrar").click(function () {
    listar();
});


function listar() {
    var fecha1 = $("#txtfecha1").val();
    var fecha2 = $("#txtfecha2").val();
    var tipo = $("#rbtipo:checked").val();

    $.post("../controlador/pago.listar.controlador.php",
            {fecha1: fecha1, fecha2: fecha2, tipo: tipo}
    ).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            var html = "";

            html += '<small>';
            html += '<table id="tabla-listado" class="table table-bordered table-striped">';
            html += '<thead>';
            html += '<tr style="background-color: #ededed; height:25px;">';
            html += '<th style="text-align: center">OPCIONES</th>';
            html += '<th>N. PAGO</th>';
            html += '<th>FECHA</th>';
            html += '<th>CLIENTE</th>';
            html += '<th>N. LINEA TELEF.</th>';
            html += '<th>TOTAL</th>';
            html += '<th>ESTADO</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            //Detalle
            $.each(datosJSON.datos, function (i, item) {
                if (item.estado === "ACTIVO") {
                    html += '<tr>';
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-danger btn-xs" onclick="anular(' + item.num_pago + ')"><i class="fa fa-close"></i></button>';
                    html += '</td>';
                } else {
                    html += '<tr style="text-decoration:line-through; color: red" >';
                    html += '<td align="center">';
                    html += '</td>';
                }     
                html += '<td>' + item.num_pago + '</td>';
                html += '<td align="center">' + item.fecha_pago + '</td>';
                html += '<td>' + item.cliente + '</td>';
                html += '<td>' + item.numero_linea_telefonica + '</td>';
                html += '<td>' + item.total + '</td>';
                html += '<td>' + item.estado + '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';
            html += '</small>';

            $("#listado").html(html);

            $('#tabla-listado').dataTable({
                "aaSorting": [[1, "desc"]]
            });
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}

function anular(p_numero_pago) {
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
                    $.post("../controlador/pago.anular.controlador.php",
                            {p_numero_pago: p_numero_pago}
                    ).done(function (resultado) {
                        var datosJSON = resultado;
                        if (datosJSON.estado === 200) {
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