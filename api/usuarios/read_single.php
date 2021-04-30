<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');
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

  // Instanciar un nuevo Periodo Escolar
  $periodoEscolar = new periodoEscolar($db);

  // Se verifica que tipo de datos trae el JSON
  $control = true; // Variable de control para validar los datos del JSON
  if($data->nombreCorto !== NULL){
    $periodoEscolar->nombreCorto = $data->nombreCorto;
    $periodoEscolar->nombreVariable = 'nombreCorto';
    $periodoEscolar->contenidoVariable = $data->nombreCorto;
  }else if($data->id !== NULL){
    $periodoEscolar->id = $data->id;
    $periodoEscolar->nombreVariable = 'id';
    $periodoEscolar->contenidoVariable = $data->id;
  }else{
    // En caso de que los datos del JSON no sean ni un id, matricula o curp, entonces el control es falso
    $control = false;
  }

  // Comprobamos que el control sea válido
  if($control && $validacionUsuario && $usuario->id_rol >= 1){
    // Se ejecuta la lectura y se guarda el resultado en la variable
    $result = $periodoEscolar->read_single();

    if($result !== false){
      // Get row count
      $num = $result->rowCount();
    }else{
      $num = -1;
    }

    // Check if any Periodo Escolar
    if($num > 0) {
      // Periodo Escolar array
      $periodoEscolar_arr = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $periodoEscolar_item = array(
          // Datos Generales
          'id' => $id,
          'fechaInicio' => $fechaInicio,
          'fechaFin' => $fechaFin,
          'costoInscripcionPrepa' => $costoInscripcionPrepa,
          'costoInscripcionUni' => $costoInscripcionUni,
          'costoColegiaturaPrepa' => $costoColegiaturaPrepa,
          'costoColegiaturaUni' => $costoColegiaturaUni,
          'nombreCorto' => $nombreCorto,
        );

        // Push to "data"
        array_push($periodoEscolar_arr, $periodoEscolar_item);

      }

      // Turn to JSON & output
      $periodoEscolar->mensajeCapturista = "Periodo(s) Escolar(es) consultado(s) de manera exitosa.";
      echo json_encode(
        array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolar_arr)
      );

    } else {
      // No Periodos Escolares
      $periodoEscolar->mensajeCapturista = "No se obtuvieron resultados al consultar Periodo(s) Escolar(es) con los parámetros indicados.";
      echo json_encode(
        array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolar_arr)
      );
    }
  }else if($validacionUsuario !== true){ // En caso de que el usuario sea inválido

    $periodoEscolar->mensajeCapturista = "No se puedo validar el usuario y/o contraseña ingresados.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "No se obtuvieron un Nombre de Usuario y Contraseña correctos desde el archivo JSON o directamente no había dichos datos, esto puede indicar que alguien accedió a la funcionalidad sin loguearse desde una cuenta válida, lo que puede ser inidicio de un intento de hacking.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolar_arr)
    );

  }else if($control !== true){

    $periodoEscolar->mensajeCapturista = "Error Critico al hacer la consulta: Intente recargar la página.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "No se recibió un dato válido para ejecutar la Query desde el archivo JSON, lo cual puede indicar un error inesperado, un error desconocido de los archivos, o alguien está intentando ingresar manualmente una solicitud POST de manera externa, lo que puede ser inidicio de un intento de Hacking.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolar_arr)
    );
    
  }else if($usuario->id_rol < 1){ // En caso de que el usuario no tenga los permisos necesarios

    $periodoEscolar->mensajeCapturista = "No cuenta con los permisos necesarioa para realizar esta acción.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "Esta acción la está intentando realizar un usuario válido dentro de la base de datos, sin embargo dicho usuario no tiene los permisos necesarios, lo cual puede indicar que hay un error de programación, mas no es un indicio como tal de un intento de Hacking.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolar_arr)
    );

  }else{ // Error desconocido

    $periodoEscolar->mensajeCapturista = "Error Desconocido.";
    $periodoEscolar->status = "0";
    $periodoEscolar->errorType = "Error Completamente Desconocido.";
    echo json_encode(
      array('usuario' => $usuario->nombreUsuario, 'rol' => $usuario->id_rol, 'fechaConsulta' => $fechaActual, 'horaConsulta' => $horaActual, 'message' => $periodoEscolar->mensajeCapturista, 'numeroResultados' => $num, 'resultadoQuery1' => $periodoEscolar->mensaje,'status' => $periodoEscolar->status, 'error' => $periodoEscolar->errorType, 'data' => $periodoEscolar_arr)
    );

  }