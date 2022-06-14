var tabla;

//Función que se ejecuta al inicio
function init() {

    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

    $("#imagenmuestra").hide();
    $.post("../controller/rol.php?op=5", function(r) {
        $("#idrol").html(r);
        $('#idrol').selectpicker('refresh');
    });
}

//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#apellidop").val("");
    $("#apellidom").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#email").val("");
    $("#login").val("");
    $("#clave").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#idusuario").val("");
    $.post("../controller/rol.php?op=5", function(r) {
        $("#idrol").html(r);
        $('#idrol').selectpicker('refresh');
    });
    $("#tipo_documento").val(1);
    $('#tipo_documento').selectpicker('refresh');
    $("#imagenmuestra").hide();
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
            url: '../controller/usuario.php?op=0',
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
        url: "../controller/usuario.php?op=1",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            console.log(datos);
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

function mostrar(idusuario) {
    $.post("../controller/usuario.php?op=4", { idusuario: idusuario }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.personanombre);
        $("#apellidop").val(data.personaap);
        $("#apellidom").val(data.personaam);
        $("#tipo_documento").val(data.personatipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.personanum_documento);
        $("#direccion").val(data.personadireccion);
        $("#email").val(data.personaemail);
        $("#login").val(data.usuarionombre);
        $.post("../controller/usuario.php?op=7", { clave: data.usuarioclave }, function(r) {
            $("#clave").val(r);
        });
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/usuarios/" + data.personaimagen);
        $("#imagenactual").val(data.personaimagen);
        $("#idusuario").val(data.idusuario);
        $.post("../controller/rol.php?op=5", function(r) {
            $("#idrol").html(r);
            $("#idrol").val(data.idrol);
            $('#idrol').selectpicker('refresh');
        });

    });
}

//Función para desactivar registros
function desactivar(idusuario) {
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
            $.post("../controller/usuario.php?op=2", { idusuario: idusuario }, function(e) {
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
function activar(idusuario) {
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
            $.post("../controller/usuario.php?op=3", { idusuario: idusuario }, function(e) {
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