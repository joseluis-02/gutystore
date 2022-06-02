
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <title>gutyestore</title>
    <!--FontAwesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!--Estilos Bootstrap css-->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
     <link rel="stylesheet" href="../public/plugins/select2/css/bootstrap-select.min.css">
     <!--Estilos css del plugin dataTable-->
     <link rel="stylesheet" type="text/css" href="../public/plugins/DataTable/css/jquery.dataTables.min.css"/>
     <link rel="stylesheet" type="text/css" href="../public/plugins/DataTable/css/responsive.dataTables.min.css"/>

     <!--Select2-->
     <link rel="stylesheet" href="../public/plugins/select2/css/select2.min.css">
     <!--Estilos del plugin sweetalert2-->
     <link href="../public/plugins/sweetAlert2/css/sweetalert2.min.css" rel="stylesheet" />
     <!--Estilos header-->
     
      <!--Jquery 3.5.1 -->
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
   

</head>
<body >
        <!--<nav class="navbar navbar-expand-sm navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="./inicio.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mis productos</a>
                    </li>
                   
                </ul>
                <div class="navbar-text dropdown">
                <a class=""  id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false" href="#"><img style="width: 40px; border-radius:50%;" src="https://cdn.pixabay.com/photo/2020/06/10/02/22/caricature-5280770__340.jpg" alt=""></a>
               <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#">Perfil</a>
                  <a class="dropdown-item" href="#">Configuración</a>
                  <a class="dropdown-item" href="#">Salir</a>
                </div>
                </div>
               
            </div>
        </nav>-->


        
     <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <a class="navbar-brand" href="#">
            <img style="border-radius:50%;" src="https://cdn.pixabay.com/photo/2020/06/10/02/22/caricature-5280770__340.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            <span style="color:#OD5C75;">GUTYE</span><span style="color:#A5D1E1">STORE</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

       <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
               <a class="nav-link" href="../../gutyestore/views/inicio.php">Inicio<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/producto.php">Productos</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/unidad_medida.php">Medidas</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/pais.php">Paises</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/marca.php">Marcas</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/permiso.php">Permisos</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/rol.php">Roles</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/color.php">Colores</a>
              </li>
              <li class="nav-item">
               <a class="nav-link" href="../../gutyestore/views/categoria.php">Categoria</a>
              </li>
         </ul>
      </div>
     </nav>
     <div id="root" class="container-sm">