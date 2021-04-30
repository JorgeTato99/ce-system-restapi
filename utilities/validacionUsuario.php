<?php 

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