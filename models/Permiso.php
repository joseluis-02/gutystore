<?php 
//Incluímos inicialmente la conexión a la base de datos
require_once "../config/Conexion.php";

Class Permiso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT idpermiso, permisonombre FROM permiso ORDER BY permisonombre ASC";
		return ejecutarConsulta($sql);		
	}

	public function select()
	{
		$sql="SELECT idpermiso, permisonombre FROM permiso ORDER BY permisonombre ASC";
		return ejecutarConsulta($sql);		
	}

	public function listarmarcados($idrol)
	{
		$sql="SELECT idpermiso FROM rol_permiso WHERE idrol='$idrol'";
		return ejecutarConsulta($sql);
	}
}

?>