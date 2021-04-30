<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  header("Cache-Control: no-cache, must-revalidate");

  include_once '../../config/database.php';
  include_once '../../models/periodoEscolar.php';
  include_once '../../models/usuarios.php';

  // Varibale donde se recibe el JSON
  $data = json_decode(file_get_contents("php://input"));

  // Instanciar una nueva Database y conectarse
  $database = new Database();
  $db = $database->connect();

  ////////////////////////////////////// VALIDACION USUARIO //////////////////////////////////////////
  // Instanciar un nuevo periodoEscolar
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

  // Instanciar un nuevo Periodo Escolar
  $periodoEscolar = new periodoEscolar($db);

  if($validacionUsuario && $usuario->id_rol >= 1){
    // Datos Generales
    if($data->id){
      $periodoEscolar->id = $data->id;
    }
    $periodoEscolar->fechaInicio = $data->fechaInicio;
    $periodoEscolar->fechaFin = $data->fechaFin;
    $periodoEscolar->costoInscripcionPrepa = $data->costoInscripcionPrepa;
    $periodoEscolar->costoInscripcionUni = $data->costoInscripcionUni;
    $periodoEscolar->costoColegiaturaPrepa = $data->costoColegiaturaPrepa;
    $periodoEscolar->costoColegiaturaUni = $data->costoColegiaturaUni;
    $periodoEscolar->nombreCorto = $data->nombreCorto;

    // Crear periodoEscolar
    $periodoEscolar->create();

    // Salida JSON
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'resultadoQuery1' => $periodoEscolar->mensaje, 'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType)
    );

  }else if($validacionUsuario !== true){ // En caso de que el usuario sea inválido

    $periodoEscolar->mensajeCapturista = "No se puedo validar el usuario y/o contraseña ingresados.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "No se obtuvieron un Nombre de Usuario y Contraseña correctos desde el archivo JSON o directamente no había dichos datos, esto puede indicar que alguien accedió a la funcionalidad sin loguearse desde una cuenta válida, lo que puede ser inidicio de un intento de hacking.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolars_arr)
    );

  }else if($usuario->id_rol < 1){ // En caso de que el usuario no tenga los permisos necesarios

    $periodoEscolar->mensajeCapturista = "No cuenta con los permisos necesarioa para realizar esta acción.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "Esta acción la está intentando realizar un usuario válido dentro de la base de datos, sin embargo dicho usuario no tiene los permisos necesarios, lo cual puede indicar que hay un error de programación, mas no es un indicio como tal de un intento de Hacking.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolars_arr)
    );

  }else{ // Error desconocido

    $periodoEscolar->mensajeCapturista = "Error Desconocido.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "Error Completamente Desconocido.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolars_arr)
    );

  }

