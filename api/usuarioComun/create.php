<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
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

  if($validacionUsuario && $usuario->id_rol >= 1){
    // Datos Generales
    if($data->id){
      $usuarioComun->id = $data->id;
    }
    $usuarioComun->nombre = $data->nombre;
    $usuarioComun->apellidoPaterno = $data->apellidoPaterno;
    $usuarioComun->apellidoMaterno = $data->apellidoMaterno;
    $usuarioComun->correo = $data->correo;
    $usuarioComun->fechaNacimiento = $data->fechaNacimiento;
    $usuarioComun->edad = $data->edad;
    $usuarioComun->sexo = $data->sexo;
    $usuarioComun->curp = $data->curp;
    $usuarioComun->numeroCasa = $data->numeroCasa;
    $usuarioComun->numeroCelular = $data->numeroCelular;
    $usuarioComun->calle = $data->calle;
    $usuarioComun->numeroExterior = $data->numeroExterior;
    $usuarioComun->numeroInterior = $data->numeroInterior;
    $usuarioComun->colonia = $data->colonia;
    $usuarioComun->estado = $data->estado;
    $usuarioComun->codigoPostal = $data->codigoPostal;

    // Crear usuarioComun
    $usuarioComun->create();

    // Enviar resultados al JSON
    imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

  }else{ // Comprobaci??n de Exepciones

    $usuarioComun->mensajeCapturista = "Error Desconocido.";
    $usuarioComun->status = "0";
    $usuarioComun->errorType = "Error Completamente Desconocido.";
    // Enviar resultados al JSON
    imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

  }

