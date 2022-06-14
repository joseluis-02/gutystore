<?php
session_start();
require_once "../models/Usuario.php";
$usuario=new Usuario();

require_once "seguridad.php";
$seguridad=new seguridad();

$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? mb_strtoupper(limpiarCadena($_POST["nombre"])):"";
$apellidop=isset($_POST["apellidop"])? mb_strtoupper(limpiarCadena($_POST["apellidop"])):"";
$apellidom=isset($_POST["apellidom"])? mb_strtoupper(limpiarCadena($_POST["apellidom"])):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? mb_strtoupper(limpiarCadena($_POST["num_documento"])):"";
$direccion=isset($_POST["direccion"])? mb_strtoupper(limpiarCadena($_POST["direccion"])):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$idrol=isset($_POST["idrol"])? limpiarCadena($_POST["idrol"]):"";

switch ($_GET["op"]){
	case '1':
		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
			}
		}
		//Hash SHA256 en la contraseña
		$clavehash=$seguridad->stringEncryption('encrypt', $clave);

		if (empty($idusuario)){
			$rspta=$usuario->insertar($nombre, $apellidop, $apellidom, $tipo_documento,$num_documento,$direccion,$email,$imagen,$login,$clavehash, $idrol);
			echo $rspta ? "1:Usuario registrado" : "0:No se pudieron registrar todos los datos del usuario";
		}
		else {
			$rspta=$usuario->editar($idusuario,$nombre, $apellidop, $apellidom, $tipo_documento,$num_documento,$direccion,$email,$imagen,$login,$clavehash, $idrol);
			echo $rspta ? "1:Usuario actualizado" : "0:Usuario no se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$usuario->desactivar($idusuario);
 		echo $rspta ? "1:Usuario Desactivado" : "0:Usuario no se puede desactivar";
	break;

	case '3':
		$rspta=$usuario->activar($idusuario);
 		echo $rspta ? "1:Usuario activado" : "0:Usuario no se puede activar";
	break;

	case '4':
		$rspta=$usuario->mostrar($idusuario);
 		echo json_encode($rspta);
	break;

	case '0':
		$rspta=$usuario->listar();
		//Vamos a declarar un array
		$data= Array();

		while ($reg=$rspta->fetch_object()){
			$tipo_documento="";
			switch ($reg->personatipo_documento){
				case '1':
					$tipo_documento="DNI";
					break;
				case '2':
					$tipo_documento="PASAPORTE";
					break;
				case '3':
					$tipo_documento="CEDULA DE IDENTIDAD";
					break;
			}
			$data[]=array(
				"0"=>($reg->usuariocondicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->personanombre." ".$reg->personaap." ".$reg->personaam,
				"2"=>$tipo_documento,
				"3"=>$reg->personanum_documento,
				"4"=>$reg->personaemail,
				"5"=>$reg->usuarionombre,
				"6"=>"<img src='../files/usuarios/".$reg->personaimagen."' height='50px' width='50px' >",
				"7"=>($reg->usuariocondicion)?'<span class="label bg-green">Activado</span>':
				'<span class="label bg-red">Desactivado</span>'
				);
		}
		$results = array(
			"sEcho"=>1, //Información para el datatables
			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			"aaData"=>$data);
		echo json_encode($results);			
	break;	

	case '5':
		$logina=$_POST['logina'];
	    $clavea=$_POST['clavea'];

	    //Hash SHA256 en la contraseña
		$clavehash=$seguridad->stringEncryption('encrypt', $clavea);

		$rspta=$usuario->verificar($logina, $clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch))
	    {
	        //Declaramos las variables de sesión
	        $_SESSION['idusuario']=$fetch->idusuario;
	        $_SESSION['personanombre']=$fetch->personanombre." ".$fetch->personaap." ".$fetch->personaam;
	        $_SESSION['personaimagen']=$fetch->personaimagen;
			$_SESSION['personaemail']=$fetch->personaemail;
	        $_SESSION['usuarionombre']=$fetch->usuarionombre;

	        //Obtenemos los permisos del usuario
	    	$marcados = $usuario->listarmarcados($fetch->idusuario);

	    	//Declaramos el array para almacenar todos los permisos marcados
			$valores=array();

			//Almacenamos los permisos marcados en el array
			while ($per = $marcados->fetch_object())
				{
					array_push($valores, $per->idpermiso);
				}

			//Determinamos los accesos del usuario
			in_array(1,$valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
			in_array(2,$valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
			in_array(3,$valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
			in_array(4,$valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
			in_array(5,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
			in_array(6,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
			in_array(7,$valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
			$reseteo = $usuario->editar_intentos($fetch->idusuario,0);
	    }
		else{
			$rspta=$usuario->verificar_intentos($logina);
		}
	    echo json_encode($fetch);
	break;

	case '6':
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

	break;
	case "7":		
		$prueba=$seguridad->stringEncryption('decrypt', $clave);
		echo $prueba;
	break;
	case '8':
		$rspta=$usuario->verificar_email($email);
		if(count($rspta)==0){
			echo "0";	
		}
		else{
			$idusuario = $rspta[0]; // porción1
			$usuarionombre = $rspta[1]; // porción2
			$rspta=$usuario->generar_link_temporal($email, $idusuario, $usuarionombre);
			echo $rspta;
		}
	break;
	case '9':
		$prueba=$seguridad->stringEncryption('encrypt', $clave);
		$rspta=$usuario->editar_clave($idusuario, $prueba);
		echo $rspta;
	break;
}
?>