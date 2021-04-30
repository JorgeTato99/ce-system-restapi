<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  header("Cache-Control: no-cache, must-revalidate");
  
  include_once '../../config/database.php';
  include_once '../../models/usuarioComun.php';
  include_once '../../models/usuarios.php';

  // Se decodifica el JSON en la variable "data" | Se instancia y se conecta a la BD
  include_once '../../utilities/leerJSONyDB.php';
  // Instanciar y validar usuario
  include_once '../../utilities/validacionUsuario.php';
  // Variables de Fecha y Hora
  include_once '../../utilities/fechaHora.php';

  // Instanciar un nuevo Periodo Escolar
  $usuarioComun = new usuarioComun($db);

  // Se verifica que tipo de datos trae el JSON
  $control = true; // Variable de control para validar los datos del JSON
  if($data->curp !== NULL){
    $usuarioComun->curp = $data->curp;
    $usuarioComun->nombreVariable = 'curp';
    $usuarioComun->contenidoVariable = $data->curp;
  }else if($data->correo !== NULL){
    $usuarioComun->correo = $data->correo;
    $usuarioComun->nombreVariable = 'correo';
    $usuarioComun->contenidoVariable = $data->correo;
  }else if($data->nombre !== NULL){
    $usuarioComun->nombre = $data->nombre;
    $usuarioComun->nombreVariable = 'nombre';
    $usuarioComun->contenidoVariable = $data->nombre;
  }else if($data->numeroCelular !== NULL){
    $usuarioComun->numeroCelular = $data->numeroCelular;
    $usuarioComun->nombreVariable = 'numeroCelular';
    $usuarioComun->contenidoVariable = $data->numeroCelular;
  }else if($data->id !== NULL){
    $usuarioComun->id = $data->id;
    $usuarioComun->nombreVariable = 'id';
    $usuarioComun->contenidoVariable = $data->id;
  }else{
    // En caso de que los datos del JSON no sean ni un id, matricula o curp, entonces el control es falso
    $control = false;
  }

  // Comprobamos que el control sea válido
  if($control && $validacionUsuario && $usuario->id_rol >= 1){
    // Se ejecuta la lectura y se guarda el resultado en la variable
    $result = $usuarioComun->read_single();

    if($result !== false){
      // Get row count
      $num = $result->rowCount();
    }else{
      $num = -1;
    }

    // Check if any Periodo Escolar
    if($num > 0) {
      // Periodo Escolar array
      $usuarioComun_arr = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $usuarioComun_item = array(
          // Datos Generales
          'id' => $id,
          'nombre' => $nombre,
          'apellidoPaterno' => $apellidoPaterno,
          'apellidoMaterno' => $apellidoMaterno,
          'correo' => $correo,
          'fechaNacimiento' => $fechaNacimiento,
          'edad' => $edad,
          'sexo' => $sexo,
          'curp' => $curp,
          'numeroCasa' => $numeroCasa,
          'numeroCelular' => $numeroCelular,
          'calle' => $calle,
          'numeroExterior' => $numeroExterior,
          'numeroInterior' => $numeroInterior,
          'colonia' => $colonia,
          'estado' => $estado,
          'codigoPostal' => $codigoPostal,
        );

        // Push to "data"
        array_push($usuarioComun_arr, $usuarioComun_item);

      }

      // Turn to JSON & output
      $usuarioComun->mensajeCapturista = "Usuario(s) consultado(s) de manera exitosa.";
      imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

    }else {
      // No Periodos Escolares
      $usuarioComun->mensajeCapturista = "No se obtuvieron resultados al consultar Periodo(s) Escolar(es) con los parámetros indicados.";
      $usuarioComun->mensajeCapturista = "Usuario(s) consultado(s) de manera exitosa.";
      imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);
    }
  }else{ // Comprobación de Exepciones

    $ComprobarExepciones = comprobarExepciones($validacionUsuario, $usuario->id_rol, 1, $num, $control);
    $usuarioComun->mensajeCapturista = $ComprobarExepciones['mensajeCapturista'];
    $usuarioComun->status = $ComprobarExepciones['status'];
    $usuarioComun->errorType = $ComprobarExepciones['errorType'];
    
    // Enviar resultados al JSON
    imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

  }