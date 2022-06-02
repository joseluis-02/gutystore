<?php 
require_once "../models/Unidad_medida.php";

$unidad_medida=new Unidad_medida();

$id_unidad_medida=isset($_POST["id_unidad_medida"])? limpiarCadena($_POST["id_unidad_medida"]):"";
$nombre=isset($_POST["nombre"])? mb_strtoupper(limpiarCadena($_POST["nombre"])):"";
$simbolo=isset($_POST["simbolo"])? mb_strtoupper(limpiarCadena($_POST["simbolo"])):"";

switch ($_GET["op"]){
	case '1':
		if (empty($id_unidad_medida)){
			$rspta=$unidad_medida->insertar($nombre,$simbolo);
			echo $rspta ? "1:Unidad de medida registrada" : "0:Ups! No se pudo registrar";
		}
		else {
			$rspta=$unidad_medida->editar($id_unidad_medida,$nombre,$simbolo);
			echo $rspta ? "1:Unidad medida actualizada" : "0:No se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$unidad_medida->desactivar($id_unidad_medida);
 		echo $rspta ? "1:Unidad de medida desactivada" : "0:No se puede desactivar";
 		break;
	break;

	case '3':
		$rspta=$unidad_medida->activar($id_unidad_medida);
 		echo $rspta ? "1:Unidad de medida activada" : "0:No se puede activar";
 		break;
	break;

	case '4':
		$rspta=$unidad_medida->mostrar($id_unidad_medida);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case '0':
		$rspta=$unidad_medida->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->unidad_medida_nombre,
 				"1"=>$reg->unidad_medida_simbolo,
 				"2"=>($reg->unidad_medida_condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
				"3"=>($reg->unidad_medida_condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id_unidad_medida.')"><i class="fa fa-pencil"></i></button>'.
				 	' <button class="btn btn-danger" onclick="desactivar('.$reg->id_unidad_medida.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->id_unidad_medida.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->id_unidad_medida.')"><i class="fa fa-check"></i></button>'
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
		$rspta = $unidad_medida->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->id_unidad_medida . '>' . $reg->unidad_medida_nombre . '</option>';
				}
		break;
}
?>