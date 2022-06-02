<?php 
require_once "../models/Marca.php";

$marca=new Marca();

$id_marca=isset($_POST["id_marca"])? limpiarCadena($_POST["id_marca"]):"";
$nombre=isset($_POST["nombre"])? mb_strtoupper(limpiarCadena($_POST["nombre"])):"";
$descripcion=isset($_POST["descripcion"])? mb_strtoupper(limpiarCadena($_POST["descripcion"])):"";
$logo=isset($_POST["logo"])? limpiarCadena($_POST["logo"]):"";
$imagenAnterior = isset($_POST["imagenactual"])? limpiarCadena($_POST["imagenactual"]):"";

switch ($_GET["op"]){
	case '1':
		/* Subir una imagen al servidor, para que se pueda almacenar*/ 
		if (file_exists($_FILES['logo']['tmp_name']) || is_uploaded_file($_FILES['logo']['tmp_name']))
		{
			$ext = explode(".", $_FILES["logo"]["name"]);
			if ($_FILES['logo']['type'] == "image/jpg" ||
			 $_FILES['logo']['type'] == "image/jpeg" ||
			  $_FILES['logo']['type'] == "image/png")
			{
				if($imagenAnterior!="")
				{
					unlink("../files/marcas/".$imagenAnterior);
				}
				//$logo = round(microtime(true)) . '.' . end($ext);
				$logo = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["logo"]["tmp_name"], "../files/marcas/" . $logo);
			}
		}
		else 
		{
			//imagen actual
			$logo=$imagenAnterior;
		}
		if (empty($id_marca)){
		    $rspta=$marca->insertar($nombre,$descripcion,$logo);
		    echo $rspta ? "1:Marca registrada" : "0:Marca no se pudo registrar";
		}
		else {
			$rspta=$marca->editar($id_marca,$nombre,$descripcion,$logo);
			echo $rspta ? "1:Marca actualizada" : "0:Marca no se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$marca->desactivar($id_marca);
 		echo $rspta ? "1:Marca Desactivada" : "0:Marca no se puede desactivar";
 		break;
	break;

	case '3':
		$rspta=$marca->activar($id_marca);
 		echo $rspta ? "1:Marca activada" : "0:Marca no se puede activar";
 		break;
	break;

	case '4':
		$rspta=$marca->mostrar($id_marca);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case '0':
		$rspta=$marca->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->marca_nombre,
                "1"=>"<img src='../files/marcas/".$reg->marca_logo."' height='50px' width='50px' >",
 				"2"=>$reg->marca_descripcion,
 				"3"=>($reg->marca_condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
                "4"=>($reg->marca_condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id_marca.')"><i class="fa fa-pencil"></i></button>'.
				 	' <button class="btn btn-danger" onclick="desactivar('.$reg->id_marca.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->id_marca.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->id_marca.')"><i class="fa fa-check"></i></button>',
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
		$rspta = $marca->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->id_marca . '>' . $reg->marca_nombre . '</option>';
				}
		break;
}
?>