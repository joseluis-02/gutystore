//Función que se ejecuta al inicio
function init() {
    /* $.ajax(
        'http://localhost:7882/gutyestore/controller/categoria.php?op=0', {
            success: function(data) {
                var lista = JSON.parse(data);
                //console.log(data);
                $("#lista").html('');
                for (var i = 0; i < lista.length; i++) {
                    var obj = lista[i];
                    var subcategorias = "";
                    obj.subcategorias.forEach(function(valor, indice, array) {
                        subcategorias += `<li class="list-group-item">${valor.categoria_nombre}</li>`;
                    });
                    var categoria = `
                    <li class="list-group-item active" aria-current="true">${obj.categoria_nombre}</li>
                    <ul class="list-group">
                      ${subcategorias}
                      </ul>
                    `;

                    // console.log(categoria);
                    //console.log(subcategorias);
                    $("#lista").append(categoria);
                }
            },
            error: function() {
                alert('error! al conectar al servidor');
            }
        }
    );
    //Cargamos los items al select categoria
    $.post("http://localhost:7882/gutyestore/controller/categoria.php?op=5", function(r) {
        $("#categoria_general").html(r);
        $("#categoria_general").change(function() {
            var estado = $("#categoria_general").val();
            mostrarSubCategoria(estado);
        });
    });

}

function mostrarSubCategoria(idcategoria) {
    $.post("http://localhost:7882/gutyestore/controller/categoria.php?op=6", { idcategoria: idcategoria }, function(data, status) {
        var lista = JSON.parse(data);
        // console.log(data);
        $("#categoria").html('');
        for (var i = 0; i < lista.length; i++) {
            var obj = lista[i];
            var subcategorias = 0;
            obj.subcategorias.forEach(function(valor, indice, array) {
                subcategorias++;
            });
            var categoria = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
            ${obj.categoria_nombre}
              <span class="badge badge-primary badge-pill">${subcategorias}</span>
            </li>
            `;

            // console.log(categoria);
            //console.log(subcategorias);
            $("#categoria").append(categoria);
        }
    });*/
    listar();
    mostrarform(false);
    $("#formulario_categoria").on("submit", function(e) {
        guardaryeditar(e);
    });
    //Cargamos los items al select categoria padre
    $.post("../controller/categoria.php?op=5", function(r) {
        $("#categoria_padre_id").html(r);
        //$('#categoria_padre_id').selectpicker('refresh');

    });
}
//Función limpiar
function limpiar() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#id_categoria").val("");
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
    tabla = $('#datatable_categoria').dataTable({
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
            url: '../controller/categoria.php?op=0',
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

function mostrar(id_categoria) {
    $.post("../controller/categoria.php?op=4", { id_categoria: id_categoria }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.categoria_nombre);
        $("#descripcion").val(data.categoria_descripcion);
        $("#id_categoria").val(data.id_categoria);
        $("#categoria_padre_id option[value=" + data.id_categoria + "]").attr("selected", true);
        //$("#categoria_padre_id").select(data.categoria_padre_id);

    })
}

//Función para desactivar registros
function desactivar(id_categoria) {
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
            $.post("../controller/categoria.php?op=2", { id_categoria: id_categoria }, function(e) {
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
                        footer: 'Revise la información cuidadosamente por favor'
                    });
                }
            });
        }
    });
}

//Función para activar registros
function activar(id_categoria) {
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
            $.post("../controller/categoria.php?op=3", { id_categoria: id_categoria }, function(e) {
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
                        footer: 'Revise la información cuidadosamente por favor'
                    });
                }
            });
        }
    });
}
//Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario_categoria")[0]);

    $.ajax({
        url: "../controller/categoria.php?op=1",
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
                    footer: 'Revise la información de registro...'
                });
            }
        }

    });
    limpiar();
}

init();