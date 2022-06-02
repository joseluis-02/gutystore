<?php 
//Incluímos inicialmente la conexión a la base de datos
require_once "../config/Conexion.php";

Class Categoria
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($categoria_padre_id,$nombre,$descripcion)
	{
		$sql="INSERT INTO categoria (categoria_padre_id, categoria_nombre, categoria_descripcion,categoria_condicion)
		VALUES ('$categoria_padre_id','$nombre','$descripcion','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id_categoria,$categoria_padre_id,$nombre,$descripcion)
	{
		$sql="UPDATE categoria SET categoria_nombre='$nombre',categoria_padre_id='$categoria_padre_id', categoria_descripcion='$descripcion' 
		WHERE id_categoria='$id_categoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($id_categoria)
	{
		$sql="UPDATE categoria SET categoria_condicion='0' WHERE id_categoria='$id_categoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($id_categoria)
	{
		$sql="UPDATE categoria SET categoria_condicion='1' WHERE id_categoria='$id_categoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_categoria)
	{
		$sql="SELECT id_categoria,categoria_padre_id, categoria_nombre,categoria_descripcion,categoria_condicion FROM categoria WHERE id_categoria='$id_categoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT id_categoria,categoria_padre_id, categoria_nombre,categoria_descripcion,categoria_condicion FROM categoria";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros
	public function listarSubCategorias($categoria_padre_id)
	{
		$sql="SELECT id_categoria,categoria_padre_id, categoria_nombre,categoria_descripcion,categoria_condicion FROM categoria WHERE categoria_padre_id=$categoria_padre_id";
		return ejecutarConsulta($sql);		
	}
	public function sub_categorias($id_categoria)
    {

      $sql = "SELECT id_categoria,categoria_padre_id, categoria_nombre,categoria_descripcion,categoria_condicion FROM categoria WHERE categoria_padre_id=$id_categoria";
      $result = ejecutarConsulta($sql);

      $categorias = array();

      while($row = $result->fetch_assoc())
      {
           $categorias[] = array(
             'id_categoria' => $row['id_categoria'],
             'categoria_padre_id' => $row['categoria_padre_id'],
             'categoria_nombre' => $row['categoria_nombre'],
			 'categoria_descripcion' => $row['categoria_descripcion']
             //'subcategoria' => sub_categorias($row['id_categoria']),
           );
      }
      return $categorias;
    }
	

	public function select()
	{
		$sql="SELECT id_categoria,categoria_padre_id, categoria_nombre FROM categoria WHERE (categoria_condicion=1) ORDER BY categoria_nombre ASC";
		return ejecutarConsulta($sql);		
	}
}

?>