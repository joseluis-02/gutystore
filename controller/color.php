<?php 
require_once "../models/Color.php";

$color=new Color();

$id_color=isset($_POST["id_color"])? limpiarCadena($_POST["id_color"]):"";
$valor=isset($_POST["color_valor"])? limpiarCadena($_POST["color_valor"]):"";
$nombre=isset($_POST["color_nombre"])? mb_strtoupper(limpiarCadena($_POST["color_nombre"])):"";

switch ($_GET["op"]){
	case '1':
		if (empty($id_color)){
			$rspta=$color->insertar($valor,$nombre);
			echo $rspta ? "1:Color registrada" : "0:Ups! No se pudo registrar";
		}
		else {
			$rspta=$color->editar($id_color,$valor,$nombre);
			echo $rspta ? "1:Color actualizada" : "0:No se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$color->desactivar($id_color);
 		echo $rspta ? "1:Color estado desactivada" : "0:No se puede desactivar";
 		break;
	break;

	case '3':
		$rspta=$color->activar($id_color);
 		echo $rspta ? "1:Color estado activada" : "0:No se puede activar";
 		break;
	break;

	case '4':
		$rspta=$color->mostrar($id_color);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case '0':
		$rspta=$color->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$reg->color_nombre,
 				"1"=>$reg->color_valor,
 				"2"=>($reg->color_condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
				"3"=>($reg->color_condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id_color.')"><i class="fa fa-pencil"></i></button>'.
				 	' <button class="btn btn-danger" onclick="desactivar('.$reg->id_color.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->id_color.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->id_color.')"><i class="fa fa-check"></i></button>'
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
		$rspta = $color->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->id_color . '>' . $reg->color_nombre . '</option>';
				}
		break;
}
?>