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

  // Instanciar un nuevo Alumno
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

  // Comprobamos que el control sea válido
  if($control && $validacionUsuario && $usuario->id_rol >= 1){
    // Actualizar alumno
    $usuarioComun->update();

    // Salida JSON
    imprimeJSON(2, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, null, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, null);

  }else{ // Comprobación de Exepciones

    $ComprobarExepciones = comprobarExepciones($validacionUsuario, $usuario->id_rol, 1, null, $control);
    $usuarioComun->mensajeCapturista = $ComprobarExepciones['mensajeCapturista'];
    $usuarioComun->status = $ComprobarExepciones['status'];
    $usuarioComun->errorType = $ComprobarExepciones['errorType'];
    
    // Enviar resultados al JSON
    imprimeJSON(1, $usuario->nombreUsuario, $usuario->id_rol, $fechaActual, $horaActual, $usuarioComun->mensajeCapturista, $num, $usuarioComun->mensaje, null, $usuarioComun->status, $usuarioComun->errorType, $usuarioComun_arr);

  }