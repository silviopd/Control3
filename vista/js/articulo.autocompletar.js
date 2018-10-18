$("#txtarticulo").autocomplete({
    source: "../controlador/articulo.autocompletar.controlador.php",
    minLength: 2, //Filtrar desde que colocamos 2 o mas caracteres
    focus: f_enfocar_registro,
    select: f_seleccionar_registro
});

function f_enfocar_registro(event, ui){
    var registro = ui.item.value;
    $("#txtarticulo").val(registro.nombre);
    event.preventDefault();
}

function f_seleccionar_registro(event, ui){
    var registro = ui.item.value;
    $("#txtarticulo").val(registro.nombre);
    $("#txtprecio").val(registro.precio);
    $("#txtcodigoarticulo").val(registro.codigo);
    $("#txtstock").val(registro.stock);
    $("#txtcantidad").focus();
    
    event.preventDefault();
}