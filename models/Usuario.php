<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
require "../public/phpmailer/Exception.php";
require "../public/phpmailer/PHPMailer.php";
require "../public/phpmailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $apellidop, $apellidom, $tipo_documento,$num_documento,$direccion,$email,$imagen,$login,$clave, $idrol)
	{
		$sql="INSERT INTO persona (personanombre, personaap, personaam, personatipo_documento, personanum_documento, personadireccion, personaemail, personaimagen)
		VALUES ('$nombre','$apellidop','$apellidom','$tipo_documento','$num_documento', '$direccion', '$email', '$imagen')";
		$idpersona=ejecutarConsulta_retornarID($sql);
		$sqlu="INSERT INTO usuario (usuarionombre,usuarioclave,idpersona,usuariocondicion,idrol)
		VALUES ('$login','$clave','$idpersona','1','$idrol')";
		return ejecutarConsulta($sqlu);		
	}

	//Implementamos un método para editar registros
	public function editar($idusuario,$nombre, $apellidop, $apellidom, $tipo_documento,$num_documento,$direccion,$email,$imagen,$login,$clave, $idrol)
	{
		$idpersona=$this->idpersona_usuario($idusuario);
		$sql="UPDATE persona SET personanombre='$nombre', personaap='$apellidop', personaam='$apellidom',personatipo_documento='$tipo_documento',personanum_documento='$num_documento',personadireccion='$direccion',personaemail='$email',personaimagen='$imagen' WHERE idpersona='$idpersona'";
		ejecutarConsulta($sql);

		$sqlu="UPDATE usuario SET usuarionombre='$login',usuarioclave='$clave',idrol='$idrol' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sqlu);

	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idusuario)
	{
		$sql="UPDATE usuario SET usuariocondicion='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idusuario)
	{
		$sql="UPDATE usuario SET usuariocondicion='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idusuario)
	{
		$sql="SELECT * FROM persona p, usuario u WHERE p.idpersona=u.idpersona AND idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM persona p, usuario u WHERE p.idpersona=u.idpersona";
		return ejecutarConsulta($sql);		
	}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT idusuario,personanombre, personaap, personaam,personatipo_documento,personanum_documento,personaemail,personaimagen,usuarionombre FROM usuario u, persona p WHERE p.idpersona=u.idpersona AND u.usuarionombre='$login' AND usuarioclave='$clave' AND u.usuariocondicion='1'"; 
    	return ejecutarConsulta($sql);  
    }

	//Implementar un método para mostrar los datos de un registro a modificar
	public function idpersona_usuario($idusuario)
	{
		$sql="SELECT p.idpersona FROM persona p, usuario u WHERE p.idpersona=u.idpersona AND idusuario='$idusuario'";
		$idpersona = ejecutarConsultaSimpleFila($sql);
		return $idpersona["idpersona"];
	}

	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idusuario)
	{
		$sql="SELECT * FROM usuario u, rol r, rol_permiso p WHERE u.idrol=r.idrol AND r.idrol=p.idrol AND u.idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
	//verifica el email, si este existe para permitir el reseteo de contraseña
	public function verificar_email($email){
		$mensaje = 0;
		$resultadox = Array();
		$sql="SELECT idusuario, usuarionombre FROM usuario u, persona p WHERE (p.idpersona=u.idpersona)AND(p.personaemail = '$email')";
		$resultado = ejecutarConsultaSimpleFila($sql);
		array_push($resultadox,$resultado["idusuario"],$resultado["usuarionombre"]);
		return $resultadox;
	}
	//Generamos un link temporal para el reseteo de contraseñas, lo guardamos en la tabla reseteopass
	public function generar_link_temporal($email, $idusuario, $username){
		$cadena = $idusuario.$username.rand(1,9999999).date('Y-m-d');		
		require_once "../ajax/seguridad.php";
		$seguridad=new seguridad();
		$token=$seguridad->stringEncryption('encrypt', $cadena);
		$idusuarios=$seguridad->stringEncryption('encrypt', $idusuario);
		$enlace = 'http://'.$_SERVER["SERVER_NAME"].'/proyectoupds/vistas/restablecer.php?idusuario='.$idusuarios.'&token='.$token;
		$sql="INSERT INTO reseteopass (idusuario, token, creado) VALUES($idusuario,'$token',NOW());";
		$r=ejecutarConsulta($sql);
		$resultado=$this->enviar_email($email, $enlace);
		return 1;
	}
	//Implementamos un método para editar registros
	public function editar_intentos($idusuario,$intentos)
	{
		$sql="UPDATE usuario SET usuariointentos='$intentos' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
	//Función para verificar el acceso al sistema
	public function verificar_intentos($login)
    {
		$sql="SELECT idusuario, usuariointentos FROM usuario WHERE usuarionombre='$login'";
		$resultado = ejecutarConsultaSimpleFila($sql); 
		$idusuario=$resultado["idusuario"];
		$num_intentos=(int)$resultado["usuariointentos"]+1;
		if($num_intentos>=15){
			$respuesta=$this->editar_intentos($idusuario,$num_intentos);
			$respuesta=$this->desactivar($idusuario);
		}
		else{$respuesta=$this->editar_intentos($idusuario,$num_intentos);}
	}

	//Implementamos un método para resetear la contraseña a través del sistema
	public function editar_clave($idusuario, $clave)
	{
		$sqlx="UPDATE usuario SET usuarioclave='$clave' WHERE idusuario='$idusuario'";
		$rspta=ejecutarConsulta($sqlx);
		$sqld="DELETE FROM reseteopass WHERE idusuario='$idusuario'";
		ejecutarConsulta($sqld);
		$activa=$this->activar($idusuario);
		return $idusuario;
	}

	public function enviar_email($email, $link){

		$mail = new PHPMailer(true);

		try {
			//Configurar server
			$mail->SMTPDebug = 0;                     
			$mail->isSMTP();                                          
			$mail->Host       = 'smtp.gmail.com';                  
			$mail->SMTPAuth   = true;                             
			$mail->Username   = 'gutyejl@gmail.com';                   
			$mail->Password   = '';                               
			$mail->SMTPSecure = 'tls';        
			$mail->Port       = 587;                                    
			//Email de origen y de destino
			$mail->setFrom('gutyejl@gmail.com', 'Gutyestore');
			$mail->addAddress($email, 'Usuario');     // Add a recipient
			// Contenido
			$mail->isHTML(true);                                  // Set email format to HTML
			$subject = "Recuperar contraseña";
			$subject = "=?UTF-8?B?".base64_encode($subject)."=?=";
			$mail->Subject = $subject;
			$mail->Body    ='<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">			
				<title>Contraseñas</title>
			
				<style>
					.center {
					margin: auto;
					text-align: center;
					width: 50%;
					border: 1px solid #F5B041;
					padding: 10px;
					}
					
					.button {
					background-color: orange;
					border: none;
					color: white;
					padding: 10px 15px;
					text-align: center;
					text-decoration: none;
					display: inline-block;
					font-size: 16px;
					margin: 4px 2px;
					cursor: pointer;
					}
				</style>
			</head>
			
			<body>
				<div class="center">			
					<img src="https://www.upds.edu.bo/wp-content/uploads/2020/10/upds_logo-1-1-1.png" alt="Gobierno Municipal" style="width:130px;height:60px;">
					<p><b>¡Importante! </b>Hemos recibido una solicitud para la recuperación de contraseñas de su cuenta, si usted no hubiera realizado esta solicitud, 
					no debe ingresar al link que proporcionamos en este mensaje, e informar al administrador de sistemas sobre actividad sospechosa con su información.</p>
					<p>Por tanto, si desea recuperar o cambiar su contraseña, ingrese al link que se proporciona a continuación, y modifique su contraseña.</p>
					<a href="'.$link.'" > <button class="button">Reestablecer Contraseña</button> </a>
				</div>
			</body>
			</html>';
			$mail->send();
			echo 'Mensaje enviado';
		} catch (Exception $e) {
			echo "Mensaje no enviado: {$mail->ErrorInfo}";
		}
	}
}

?>