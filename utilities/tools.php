<?php 
    function limpiarVariable($Variable){
        if($Variable === NULL){
              return $Variable;
        }else{
              $Variable = htmlspecialchars(strip_tags($Variable));
              $Variable = trim($Variable);
              return $Variable;
        }
    } // Fin limpiarVariable

    function imprimeJSON($Caso, $usuario, $rol, $fecha, $hora, $mensaje, $numeroResultados, $resQuery1, $resQuery2, $status, $error, $data){
      switch($Caso){

            case 0;
                  echo json_encode(
                        array('usuario' => $usuario, 'rol' => $rol, 'fechaConsulta' => $fecha, 'horaConsulta' => $hora, 'message' => $mensaje, 'resultadoQuery1' => $resQuery1, 'resultadoQuery2' => $resQuery2, 'status' => $status, 'error' => $error)
                  );
                  break;
            case 1:
                  echo json_encode(
                        array('usuario' => $usuario, 'rol' => $rol, 'fechaConsulta' => $fecha, 'horaConsulta' => $hora, 'message' => $mensaje, 'numeroResultados' => $numeroResultados, 'resultadoQuery1' => $resQuery1, 'status' => $status, 'error' => $error, 'data' => $data)
                  );
                  break;
            case 2:
                  echo json_encode(
                        array('usuario' => $usuario, 'rol' => $rol, 'fechaConsulta' => $fecha, 'horaConsulta' => $hora, 'message' => $mensaje, 'resultadoQuery1' => $resQuery1, 'status' => $status, 'error' => $error)
                  );
                  break;

      } // Fin switch
      
    } // Fin imprimeJSON

    function comprobarExepciones($validacionUsuario, $rol, $rolMinimo, $numeroResultados, $controlJSON){

          $mensajeCapturista = null;
          $status = "0";
          $errorType = null;

          if($validacionUsuario !== true){ // En caso de que el usuario sea inválido

                $mensajeCapturista = "No se puedo validar el usuario y/o contraseña ingresados.";
                $errorType = "No se obtuvieron un Nombre de Usuario y Contraseña correctos desde el archivo JSON o directamente no había dichos datos, esto puede indicar que alguien accedió a la funcionalidad sin loguearse desde una cuenta válida, lo que puede ser inidicio de un intento de hacking.";

                return array('mensajeCapturista' => $mensajeCapturista, 'status' => $status, 'errorType' => $errorType);

          }else if($rol < $rolMinimo){ // En caso de que el usuario no tenga los permisos necesarios

                $mensajeCapturista = "No cuenta con los permisos necesarioa para realizar esta acción.";
                $errorType = "Esta acción la está intentando realizar un usuario válido dentro de la base de datos, sin embargo dicho usuario no tiene los permisos necesarios, lo cual puede indicar que hay un error de programación, mas no es un indicio como tal de un intento de Hacking.";

                return array('mensajeCapturista' => $mensajeCapturista, 'status' => $status, 'errorType' => $errorType);

          }else if($numeroResultados !== null && $numeroResultados < 1){ // En caso de que no hayan resultados en la base de datos

                $mensajeCapturista = "No existen en la base de datos.";
                $errorType = "La base de datos está vacía.";

                return array('mensajeCapturista' => $mensajeCapturista, 'status' => $status, 'errorType' => $errorType);

          }else if($controlJSON !== null && $controlJSON == false){ // En caso de que el archivo JSON esté corrupto

                $mensajeCapturista = "Error Critico al recibir la solicitud. Contacte con el soporte técnico.";
                $errorType = "No se recibió un dato válido para ejecutar la Query desde el archivo JSON, lo cual puede indicar un error inesperado, un error desconocido de los archivos, o alguien está intentando ingresar manualmente una solicitud POST de manera externa, lo que puede ser inidicio de un intento de Hacking.";

          }else{ // Error desconocido

                $mensajeCapturista = "Error Desconocido, contacte con el soporte técnico.";
                $errorType = "Error Desconocido, contacte con el soporte técnico.";

                return array('mensajeCapturista' => $mensajeCapturista, 'status' => $status, 'errorType' => $errorType);
          }

    } // Fin comprobarExepciones