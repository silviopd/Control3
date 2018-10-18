
$("#txtnombrecliente").autocomplete({
    source: "../controlador/cliente.autocompletar.controlador.php",
    minLength: 2, //Filtrar desde que colocamos 2 o mas caracteres
    focus: f_enfocar_registro,
    select: f_seleccionar_registro
});

function f_enfocar_registro(event, ui){
    var registro = ui.item.value;
    $("#txtnombrecliente").val(registro.nombre);
    event.preventDefault();
}

function f_seleccionar_registro(event, ui){
    var registro = ui.item.value;
    $("#txtnombrecliente").val(registro.nombre);
    $("#txtcodigocliente").val(registro.codigo);
    $("#lbldireccioncliente").val(registro.direccion);
    $("#lbltelefonocliente").val(registro.telefono);
    
    
    event.preventDefault();
}
/*FIN: BUSQUEDA DE CLIENTES*/