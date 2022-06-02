<?php 
require_once "../models/Pais.php";

$pais=new Pais();

$id_pais=isset($_POST["id_pais"])? limpiarCadena($_POST["id_pais"]):"";
$nombre=isset($_POST["nombre"])? mb_strtoupper(limpiarCadena($_POST["nombre"])):"";
$prefijo=isset($_POST["prefijo"])? mb_strtoupper(limpiarCadena($_POST["prefijo"])):"";

switch ($_GET["op"]){
	case '1':
		if (empty($id_pais)){
			$rspta=$pais->insertar($nombre,$prefijo);
			echo $rspta ? "1:Unidad de medida registrada" : "0:Ups! No se pudo registrar";
		}
		else {
			$rspta=$pais->editar($id_pais,$nombre,$prefijo);
			echo $rspta ? "1:Unidad medida actualizada" : "0:No se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$pais->desactivar($id_pais);
 		echo $rspta ? "1:Pais estado desactivada" : "0:No se puede desactivar";
 		break;
	break;

	case '3':
		$rspta=$pais->activar($id_pais);
 		echo $rspta ? "1:Pais estado activada" : "0:No se puede activar";
 		break;
	break;

	case '4':
		$rspta=$pais->mostrar($id_pais);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case '0':
		$rspta=$pais->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->pais_nombre,
 				"1"=>$reg->pais_prefijo,
 				"2"=>($reg->pais_condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
				"3"=>($reg->pais_condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id_pais.')"><i class="fa fa-pencil"></i></button>'.
				 	' <button class="btn btn-danger" onclick="desactivar('.$reg->id_pais.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->id_pais.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->id_pais.')"><i class="fa fa-check"></i></button>'
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
		$rspta = $pais->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->id_pais . '>' . $reg->pais_nombre . '</option>';
				}
		break;
}
?>