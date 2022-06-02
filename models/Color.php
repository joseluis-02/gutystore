<?php 
//Incluímos inicialmente la conexión a la base de datos
require_once "../config/Conexion.php";

Class Color
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($valor,$nombre)
	{
		$sql="INSERT INTO color (color_valor, color_nombre, color_condicion)
		VALUES ('$valor','$nombre','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id_color,$valor,$nombre)
	{
		$sql="UPDATE color SET color_valor='$valor', color_nombre='$nombre' 
		WHERE id_color='$id_color'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar 
	public function desactivar($id_color)
	{
		$sql="UPDATE color SET color_condicion='0' WHERE id_color='$id_color'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar
	public function activar($id_color)
	{
		$sql="UPDATE color SET color_condicion='1' WHERE id_color='$id_color'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_color)
	{
		$sql="SELECT id_color, color_valor, color_nombre,color_condicion FROM color WHERE id_color='$id_color'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT id_color, color_valor, color_nombre,color_condicion FROM color";
		return ejecutarConsulta($sql);		
	}

	public function select()
	{
		$sql="SELECT id_color, color_nombre FROM color WHERE (color_condicion=1) ORDER BY color_nombre ASC";
		return ejecutarConsulta($sql);		
	}
}

?>