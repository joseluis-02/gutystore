<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Producto
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($producto_codigo,
                            $producto_descripcion,
                            $producto_foto,
                            $producto_categoria_id,
                            $producto_unidad_medida_id,
                            $producto_pais_id,
                            $producto_marca_id)
	{
		$sql="INSERT INTO producto (producto_codigo,
                                    producto_descripcion,
                                    producto_foto,
                                    producto_estado,
                                    producto_condicion,
                                    producto_categoria_id,
                                    producto_unidad_medida_id,
                                    producto_pais_id,
                                    producto_marca_id)
		VALUES ('$producto_codigo',
                 '$producto_descripcion',
                  '$producto_foto',
                   '0',
                    '1',
                     '$producto_categoria_id',
                     '$producto_unidad_medida_id',
                        '$producto_pais_id',
                      '$producto_marca_id')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id_producto,
                            $producto_codigo,
                            $producto_descripcion,
                            $producto_foto,
                            $producto_categoria_id,
                            $producto_unidad_medida_id,
                            $producto_pais_id,
                            $producto_marca_id)
	{
		$sql="UPDATE producto SET producto_codigo='$producto_codigo',
                    producto_descripcion='$producto_descripcion',
                    producto_foto='$producto_foto',
                    producto_categoria_id='$producto_categoria_id',
                    producto_unidad_medida_id='$producto_unidad_medida_id',
                    producto_pais_id='$producto_pais_id',
                    producto_marca_id='$producto_marca_id'
                    WHERE id_producto='$id_producto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($id_producto)
	{
		$sql="UPDATE producto SET producto_condicion='0' WHERE id_producto='$id_producto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($id_producto)
	{
		$sql="UPDATE producto SET producto_condicion='1' WHERE id_producto='$id_producto'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_producto)
	{
		$sql="SELECT   id_producto,
                        producto_codigo,
                        producto_descripcion,
                        producto_foto,
                        producto_estado,
                        producto_condicion,
                        producto_categoria_id,
                        producto_unidad_medida_id,
                        producto_pais_id,
                        producto_marca_id
        FROM producto WHERE id_producto='$id_producto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT p.id_producto,
                    p.producto_codigo,
                    p.producto_descripcion,
                    p.producto_foto,
                    p.producto_estado,
                    p.producto_condicion,
                    c.categoria_nombre as categoria,
                    um.unidad_medida_simbolo as unidad_medida,
                    pa.pais_nombre as pais,
                    ma.marca_nombre as marca
     FROM producto as p 
		inner join categoria as c on p.producto_categoria_id=c.id_categoria
        inner join unidad_medida as um on p.producto_unidad_medida_id=um.id_unidad_medida
        inner join pais as pa on p.producto_pais_id=pa.id_pais
        inner join marca as ma on p.producto_marca_id=ma.id_marca";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT p.producto_codigo,
        p.producto_descripcion,
        p.producto_foto,
        p.producto_estado,
        c.categoria_nombre as categoria,
        um.unidad_medida_simbolo as unidad_medida,
        pa.pais_nombre as pais,
        ma.marca_nombre as marca
FROM producto as p 
inner join categoria as c on p.producto_categoria_id=c.id_categoria
inner join unidad_medida as um on p.producto_unidad_medida_id=um.id_unidad_medida
inner join pais as pa on p.producto_pais_id=pa.id_pais
inner join marca as ma on p.producto_marca_id=ma.id_marca
WHERE p.producto_condicion='1'";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
	/*public function listarActivosVenta()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.categorianombre as categoria,a.articulocodigo,a.articulonombre,a.articulostock,(SELECT detalle_ingresoprecio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.articulodescripcion,a.articuloimagen,a.articulocondicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.articulocondicion='1'";
		return ejecutarConsulta($sql);		
	}*/
}

?>