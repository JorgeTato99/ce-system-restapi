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

  // Se ejecuta la lectura y se guarda el resultado en la variable
  $result = $usuarioComun->read();

  // Se obtiene el número de resultados
  $num = $result->rowCount();

  // Validar su hay resultados y si el usuario es válido
  if($num > 0 && $validacionUsuario && $usuario->id_rol >= 1) {
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

    // Enviar resultados al JSON
    imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

  }else{ // Comprobación de Exepciones

    $ComprobarExepciones = comprobarExepciones($validacionUsuario, $usuario->id_rol, 1, $num, null);
    $usuarioComun->mensajeCapturista = $ComprobarExepciones['mensajeCapturista'];
    $usuarioComun->status = $ComprobarExepciones['status'];
    $usuarioComun->errorType = $ComprobarExepciones['errorType'];
    // Enviar resultados al JSON
    imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

  }