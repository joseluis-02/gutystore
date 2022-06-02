<?php 
require_once "../models/Producto.php";

$producto=new Producto();

$id_producto=isset($_POST["id_producto"])? limpiarCadena($_POST["id_producto"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$descripcion=isset($_POST["descripcion"])? mb_strtoupper(limpiarCadena($_POST["descripcion"])):"";
$foto=isset($_POST["foto"])? limpiarCadena($_POST["foto"]):"";
$categoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$unidad_medida=isset($_POST["idunidad_medida"])? limpiarCadena($_POST["idunidad_medida"]):"";
$pais=isset($_POST["idpais"])? limpiarCadena($_POST["idpais"]):"";
$marca=isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$imagenAnterior = isset($_POST["imagenactual"])? limpiarCadena($_POST["imagenactual"]):"";

switch ($_GET["op"]){
	case '1':
		/* Subir una imagen al servidor, para que se pueda almacenar*/ 
		if (file_exists($_FILES['foto']['tmp_name']) || is_uploaded_file($_FILES['foto']['tmp_name']))
		{
			$ext = explode(".", $_FILES["foto"]["name"]);
			if ($_FILES['foto']['type'] == "image/jpg" ||
			 $_FILES['foto']['type'] == "image/jpeg" ||
			  $_FILES['foto']['type'] == "image/png")
			{
				if($imagenAnterior!="")
				{
					unlink("../files/productos/".$imagenAnterior);
				}
				//$logo = round(microtime(true)) . '.' . end($ext);
				$foto = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["foto"]["tmp_name"], "../files/productos/" . $foto);
			}
		}
		else 
		{
			//imagen actual
			$foto=$imagenAnterior;
		}
		if (empty($id_producto)){
		    $rspta=$producto->insertar($codigo,$descripcion,$foto,$categoria,$unidad_medida,$pais,$marca);
		    echo $rspta ? "1:Producto registrado" : "0:Producto no se pudo registrar";
		}
		else {
			$rspta=$producto->editar($id_producto,$codigo,$descripcion,$foto,$categoria,$unidad_medida,$pais,$marca);
			echo $rspta ? "1:Producto actualizada" : "0:Producto no se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$producto->desactivar($id_producto);
 		echo $rspta ? "1:Producto Desactivado" : "0:Producto no se puede desactivar";
 		break;
	break;

	case '3':
		$rspta=$producto->activar($id_producto);
 		echo $rspta ? "1:Producto activado" : "0:Producto no se puede activar";
 		break;
	break;

	case '4':
		$rspta=$producto->mostrar($id_producto);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case '0':
		$rspta=$producto->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->producto_codigo,
 				"1"=>$reg->producto_descripcion,
 				"2"=>"<img src='../files/productos/".$reg->producto_foto."' height='50px' width='50px' >",
                "3"=>($reg->producto_estado)?'<span class="label bg-green">Disponible</span>':
 				'<span class="label bg-red">Agotado</span>',
                 "4"=>$reg->categoria,
                 "5"=>$reg->unidad_medida,
                 "6"=>$reg->pais,
                 "7"=>$reg->marca,
 				"8"=>($reg->producto_condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
                 "9"=>($reg->producto_condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id_producto.')"><i class="fa fa-pencil"></i></button>'.
				 ' <button class="btn btn-danger" onclick="desactivar('.$reg->id_producto.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->id_producto.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->id_producto.')"><i class="fa fa-check"></i></button>'
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
		$rspta=$producto->listarActivos();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
                "0"=>$reg->producto_codigo,
                "1"=>$reg->producto_descripcion,
                "2"=>$reg->producto_foto,
               "3"=>($reg->producto_estado)?'<span class="label bg-green">Disponible</span>':
                '<span class="label bg-red">Agotado</span>',
                "4"=>$reg->categoria,
                "5"=>$reg->unidad_medida,
                "6"=>$reg->pais,
                "7"=>$reg->marca,
                );
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
}
?>