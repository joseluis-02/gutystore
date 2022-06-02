<?php 
//Incluímos inicialmente la conexión a la base de datos
require_once "../config/Conexion.php";

Class Unidad_medida
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$simbolo)
	{
		$sql="INSERT INTO unidad_medida (unidad_medida_nombre, unidad_medida_simbolo, unidad_medida_condicion)
		VALUES ('$nombre','$simbolo','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id_unidad_medida,$nombre,$simbolo)
	{
		$sql="UPDATE unidad_medida SET unidad_medida_nombre='$nombre', unidad_medida_simbolo='$simbolo' 
		WHERE id_unidad_medida='$id_unidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($id_unidad_medida)
	{
		$sql="UPDATE unidad_medida SET unidad_medida_condicion='0' WHERE id_unidad_medida='$id_unidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($id_unidad_medida)
	{
		$sql="UPDATE unidad_medida SET unidad_medida_condicion='1' WHERE id_unidad_medida='$id_unidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_unidad_medida)
	{
		$sql="SELECT id_unidad_medida, unidad_medida_nombre, unidad_medida_simbolo, unidad_medida_condicion FROM unidad_medida WHERE id_unidad_medida='$id_unidad_medida'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT id_unidad_medida, unidad_medida_nombre, unidad_medida_simbolo, unidad_medida_condicion FROM unidad_medida";
		return ejecutarConsulta($sql);		
	}

	public function select()
	{
		$sql="SELECT id_unidad_medida, unidad_medida_nombre FROM unidad_medida WHERE (unidad_medida_condicion=1) ORDER BY unidad_medida_nombre ASC";
		return ejecutarConsulta($sql);		
	}
}

?>