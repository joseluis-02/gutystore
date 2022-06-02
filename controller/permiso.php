<?php 
require_once "../models/Permiso.php";

$permiso=new Permiso();

switch ($_GET["op"]){

	case '0':
		$rspta=$permiso->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->permisonombre,
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
	case '1':
		$rspta = $permiso->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idpermiso . '>' . $reg->permisonombre . '</option>';
				}
		break;
	case "2":
		$rspta = $permiso->listar();
		$id=$_GET['id'];
		$marcados = $permiso->listarmarcados($id);
		$valores=array();
		while ($per = $marcados->fetch_object())
			{
				array_push($valores, $per->idpermiso);
			}
		while ($reg = $rspta->fetch_object())
			{
				$sw=in_array($reg->idpermiso,$valores)?'selected="selected"':'';
				echo '<option value=' . $reg->idpermiso . ' '.$sw.'>'.$reg->permisonombre.'</option>';
			}
	break;
}
?>