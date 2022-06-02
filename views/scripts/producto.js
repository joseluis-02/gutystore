var tabla;

//Función que se ejecuta al inicio
function init() {

    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

    //Cargamos los items al select categoria
    $.post("../controller/categoria.php?op=5", function(r) {
        $("#idcategoria").html(r);
        $('#idcategoria').selectpicker('refresh');
    });

    $.post("../controller/unidad_medida.php?op=5", function(r) {
        $("#idunidad_medida").html(r);
        $('#idunidad_medida').selectpicker('refresh');
    });

    $.post("../controller/pais.php?op=5", function(r) {
        $("#idpais").html(r);
        $('#idpais').selectpicker('refresh');
    });
    $.post("../controller/marca.php?op=5", function(r) {
        $("#idmarca").html(r);
        $('#idmarca').selectpicker('refresh');
    });
    $("#imagenmuestra").hide();
}

//Función limpiar
function limpiar() {
    $("#codigo").val("");
    $("#descripcion").val("");
    $("#foto").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#id_producto").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../controller/producto.php?op=0',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginación
        "order": [
                [0, "desc"]
            ] //Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../controller/producto.php?op=1",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            mensaje = datos.split(":");
            if (mensaje[0] == "1") {
                swal.fire(
                    'Mensaje de Confirmación',
                    mensaje[1],
                    'success'
                );
                mostrarform(false);
                tabla.ajax.reload();
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: mensaje[1],
                    footer: 'Verifique la información de Registro, en especial que la información no fué ingresada previamente a la Base de Datos.'
                });
            }
        }

    });
    limpiar();
}

function mostrar(id_producto) {
    $.post("../controller/producto.php?op=4", { id_producto: id_producto }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcategoria").val(data.producto_categoria_id);
        $('#idcategoria').selectpicker('refresh');
        $("#idunidad_medida").val(data.producto_unidad_medida_id);
        $('#idunidad_medida').selectpicker('refresh');
        $("#idpais").val(data.producto_pais_id);
        $('#idpais').selectpicker('refresh');
        $("#idmarca").val(data.producto_marca_id);
        $('#idmarca').selectpicker('refresh');
        $("#codigo").val(data.producto_codigo);
        $("#descripcion").val(data.producto_descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/productos/" + data.producto_foto);
        $("#imagenactual").val(data.producto_foto);
        $("#id_producto").val(data.id_producto);

    })
}

//Función para desactivar registros
function desactivar(id_producto) {
    swal.fire({
        title: 'Mensaje de Confirmación',
        text: "¿Desea desactivar el Registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Desactivar'
    }).then((result) => {
        if (result.value) {
            $.post("../controller/producto.php?op=2", { id_producto: id_producto }, function(e) {
                mensaje = e.split(":");
                if (mensaje[0] == "1") {
                    swal.fire(
                        'Mensaje de Confirmación',
                        mensaje[1],
                        'success'
                    );
                    tabla.ajax.reload();
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: mensaje[1],
                        footer: 'Verifique la información de Registro, en especial que la información no fué ingresada previamente a la Base de Datos.'
                    });
                }
            });
        }
    });
}

//Función para activar registros
function activar(id_producto) {
    swal.fire({
        title: 'Mensaje de Confirmación',
        text: "¿Desea activar el Registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Activar'
    }).then((result) => {
        if (result.value) {
            $.post("../controller/producto.php?op=3", { id_producto: id_producto }, function(e) {
                mensaje = e.split(":");
                if (mensaje[0] == "1") {
                    swal.fire(
                        'Mensaje de Confirmación',
                        mensaje[1],
                        'success'
                    );
                    tabla.ajax.reload();
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: mensaje[1],
                        footer: 'Verifique la información de Registro, en especial que la información no fué ingresada previamente a la Base de Datos.'
                    });
                }
            });
        }
    });
}

init();