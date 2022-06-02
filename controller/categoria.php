<?php 
require_once "../models/Categoria.php";

$categoria=new Categoria();

$id_categoria=isset($_POST["id_categoria"])? limpiarCadena($_POST["id_categoria"]):"";
$categoria_padre_id=isset($_POST["categoria_padre_id"])? limpiarCadena($_POST["categoria_padre_id"]):"";
$nombre=isset($_POST["nombre"])? mb_strtoupper(limpiarCadena($_POST["nombre"])):"";
$descripcion=isset($_POST["descripcion"])? mb_strtoupper(limpiarCadena($_POST["descripcion"])):"";

switch ($_GET["op"]){
	case '1':
		if (empty($id_categoria)){
			$rspta=$categoria->insertar($categoria_padre_id,$nombre,$descripcion);
			echo $rspta ? "1:Categoría registrada" : "0:Categoría no se pudo registrar";
		}
		else {
			$rspta=$categoria->editar($id_categoria,$categoria_padre_id,$nombre,$descripcion);
			echo $rspta ? "1:Categoría actualizada" : "0:Categoría no se pudo actualizar";
		}
	break;

	case '2':
		$rspta=$categoria->desactivar($id_categoria);
 		echo $rspta ? "1:Categoría Desactivada" : "0:Categoría no se puede desactivar";
 		break;
	break;

	case '3':
		$rspta=$categoria->activar($id_categoria);
 		echo $rspta ? "1:Categoría activada" : "0:Categoría no se puede activar";
 		break;
	break;

	case '4':
		$rspta=$categoria->mostrar($id_categoria);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case '0':
		/*$rspta=$categoria->listar();
 		//Vamos a declarar un array
     $categorias = array();
     
     while($row = $rspta->fetch_assoc())
     {
     $categorias[] = array(
     'id_categoria' => $row['id_categoria'],
     'categoria_padre_id' => $row['categoria_padre_id'],
     'categoria_nombre' => $row['categoria_nombre'],
     'categoria_descripcion' => $row['categoria_descripcion'],
     'categoria_condicion' => $row['categoria_condicion'],
     'subcategorias' => $categoria->sub_categorias($row['id_categoria']),
     );
     }
     echo json_encode($categorias);*/


	 $rspta=$categoria->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->categoria_nombre,
 				"1"=>$reg->categoria_descripcion,
 				"2"=>($reg->categoria_condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
				"3"=>($reg->categoria_condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id_categoria.')"><i class="fa fa-pencil"></i></button>'.
				 	' <button class="btn btn-danger" onclick="desactivar('.$reg->id_categoria.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->id_categoria.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->id_categoria.')"><i class="fa fa-check"></i></button>'
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
		$rspta = $categoria->select();
		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->id_categoria . '>' . $reg->categoria_nombre . '</option>';
				}
		break;
  case '6':
      $rspta=$categoria->listarSubCategorias($idcategoria);
       //Vamos a declarar un array
     $categorias = array();
     
     while($row = $rspta->fetch_assoc())
     {
     $categorias[] = array(
     'id_categoria' => $row['id_categoria'],
     'categoria_padre_id' => $row['categoria_padre_id'],
     'categoria_nombre' => $row['categoria_nombre'],
     'categoria_descripcion' => $row['categoria_descripcion'],
     'categoria_condicion' => $row['categoria_condicion'],
     'subcategorias' => $categoria->sub_categorias($row['id_categoria']),
     );
     }
     echo json_encode($categorias);
       break;
    break;
}
?>