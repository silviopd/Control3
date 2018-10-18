function cargarComboTC(p_nombreCombo, p_tipo) {
    $.post("../controlador/tipo.comprobante.cargar.combo.controlador.php"
            ).done(function (resultado) {
        var datosJSON = $.parseJSON(JSON.stringify(resultado));

        if (datosJSON.estado === 200) {
            var html = "";
            if (p_tipo === "seleccione") {
                html += '<option value="">Seleccione un tipo de comprobante</option>';
            } else {
                html += '<option value="0">Todas los tipos de comprobante</option>';
            }

            $.each(datosJSON.datos, function (i, item) {
                html += '<option value="' + item.codigo_tipo_comprobante + '">' + item.descripcion + '</option>';
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

function cargarComboSerie(p_nombreCombo, p_tipo, p_tipoComprobante) {
    $.post("../controlador/serie.cargar.combo.controlador.php",
            {
                p_tipoComprobante: p_tipoComprobante
            }
    ).done(function (resultado) {
        var datosJSON = $.parseJSON(JSON.stringify(resultado));

        if (datosJSON.estado === 200) {
            var html = "";
            if (p_tipo === "seleccione") {
                html += '<option value="">Seleccione una serie</option>';
            } else {
                html += '<option value="0">Todas las series</option>';
            }

            $.each(datosJSON.datos, function (i, item) {
                html += '<option value="' + item.numero_serie + '">' + item.numero_serie + '</option>';
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


function cargarNumeroComprobante() {
    var ser = $("#cboserie").val();
    var tc = $("#cbotipocomp").val();
    $.post("../controlador/serie.comprobante.obtener.numero.controlador.php",
            {
                p_tipoComprobante: tc,
                p_serie: ser
            }
    ).done(function (resultado) {
        var datosJSON = resultado;
        if (datosJSON.estado === 200) {
            var numeroComprobante = datosJSON.datos.numero;
            $("#txtnrodoc").val(numeroComprobante);
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });
}