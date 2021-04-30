<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  
  include_once '../../config/database.php';
  include_once '../../models/usuarios.php';

  // Varibale donde se recibe el JSON
  $data = json_decode(file_get_contents("php://input"));

  // Instanciar una nueva Database y conectarse
  $database = new Database();
  $db = $database->connect();

  /////////////////////////////////////// VALIDACION USUARIO //////////////////////////////////////////
  // Instanciar un nuevo Alumno
  $usuario = new Usuarios($db);
  $usuario->nombreUsuario = $data->nombreUsuario;
  $usuario->password = $data->password;

  // Comprobamos la validez de las claves
  
  $validacionUsuario; // Variable de Validación, por defecto es true
  if($usuario->validarUsuario() === true){
    $validacionUsuario = true;
  }else{
    $validacionUsuario = false;
  }
  ////////////////////////////////////// FIN VALIDACION USUARIO //////////////////////////////////////////

  // Variables de Fecha y Hora
  $tiempoTotal = time();
  $fechaActual = date("d/M/Y",$tiempoTotal);
  $horaActual = date("H:i:s",$tiempoTotal);

  if($usuario->validarUsuario() === true){
    echo json_encode(
        array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $usuario->mensajeCapturista, 'status' => $usuario->status, 'error' => null)
      ); 
  }else{
    echo json_encode(
        array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $usuario->mensajeCapturista, 'status' => $usuario->status, 'error' => $usuario->errorType)
      ); 
  }
  
  