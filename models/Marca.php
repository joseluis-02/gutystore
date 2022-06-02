<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Marca
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$descripcion,$logo)
	{
		$sql="INSERT INTO marca (marca_nombre,marca_descripcion, marca_logo, marca_condicion)
		VALUES ('$nombre', '$descripcion', '$logo', '1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id_marca,$nombre,$descripcion,$logo)
	{
		$sql="UPDATE marca SET marca_nombre='$nombre',marca_descripcion='$descripcion',marca_logo='$logo' WHERE id_marca='$id_marca'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($id_marca)
	{
		$sql="UPDATE marca SET marca_condicion='0' WHERE id_marca='$id_marca'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($id_marca)
	{
		$sql="UPDATE marca SET marca_condicion='1' WHERE id_marca='$id_marca'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_marca)
	{
		$sql="SELECT id_marca,marca_nombre,marca_descripcion,marca_logo,marca_condicion FROM marca WHERE id_marca='$id_marca'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT id_marca,marca_nombre,marca_descripcion,marca_logo,marca_condicion FROM marca";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT id_marca,marca_nombre,marca_descripcion,marca_logo,marca_condicion FROM marca WHERE marca_condicion='1'";
		return ejecutarConsulta($sql);		
	}
    //Implementar lista para select
    public function select()
	{
		$sql="SELECT id_marca, marca_nombre FROM marca WHERE (marca_condicion=1) ORDER BY marca_nombre ASC";
		return ejecutarConsulta($sql);		
	}
}

?>