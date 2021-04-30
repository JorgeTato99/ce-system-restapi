<?php 
   
   include_once '../../utilities/tools.php';

   class Usuarios {
      // DB stuff
      private $conn;
      private $table = 'usuarios';

      // Status stuff
      public $mensaje;
      public $mensaje2;
      public $mensaje3;
      public $mensajeCapturista;
      public $status;
      public $errorType;

      // Variables Auxiliares
      public $nombreVariable;
      public $contenidoVariable;

      // Datos Generales
      public $id;
      public $nombre;
      public $apellidoPaterno;
      public $apellidoMaterno;
      public $numeroCelular;
      public $nombreUsuario;
      public $correo;
      public $password;
      public $id_rol;

      // Constructor with DB
      public function __construct($db) {
            $this->conn = $db;
      }

      // Get Alumnos
      public function read() {
            // Crear la query SQL
            $query = 'SELECT * FROM ' . $this->table;
            // Preparar la declaracion
            $stmt = $this->conn->prepare($query);

            // Validación
            try {
                  $stmt->execute();
                  $this->mensaje = 'Query para consultar a todos los Periodos Escolares ejecutada de manera exitosa.';
                  $this->status = 1;
                  $this->mensajeCapturista = "Periodos Escolares consultados de manera exitosa.";
                  return $stmt;
            }catch (Exception $e){
                  $this->errorType = $e;
                  $this->mensaje = 'Error al ejecutar la Query para consultar a todos los Periodos Escolares.';
                  $this->mensajeCapturista = "Ocurrió un error interno al consultar a los Periodos Escolares.";
                  $this->status = 0;
                  return false;
            }
      }

      // Get Single Alumno by matricula
      public function read_single() {
            // Create query
            $query = 'SELECT * FROM ' . $this->table . " WHERE nombreUsuario = :nombreUsuario AND password = :password";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind ID
            $stmt->bindParam(":nombreUsuario", $this->nombreUsuario);
            $stmt->bindParam(":password", $this->password);
            
            // Validación
            try {
                $stmt->execute();
                return $stmt;
            }catch (Exception $e){
                $this->errorType = $e;
                $this->status = 0;
                return false;
            }
      }

      // Validacion de Usuario
      public function validarUsuario() {
        // Create query
        $query = 'SELECT * FROM ' . $this->table . " WHERE nombreUsuario = :nombreUsuario AND password = :password";
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind ID
        $stmt->bindParam(":nombreUsuario", $this->nombreUsuario);
        $stmt->bindParam(":password", $this->password);
        
        // Validación
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Datos Generales
            $this->nombreUsuario = $row['nombreUsuario'];
            $this->id_rol = $row['id_rol'];
            $this->status = 1;

            if($this->nombreUsuario && $this->id_rol){
                $this->mensajeCapturista = "Validación del usuario ".$this->nombreUsuario." exitosa.";
                return true;
            }else{
                $this->mensajeCapturista = "Claves de Acceso Incorrectas";
                return false;
            }
            
        }catch (Exception $e){
            $this->errorType = $e;
            $this->status = 0;
            $this->mensajeCapturista = "Error al hacer la ejecución de la consulta";
            return false;
        }
  }

      // Create Post
      public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' SET id = :id, nombre = :nombre, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno, numeroCelular = :numeroCelular, nombreUsuario = :nombreUsuario, correo = :correo, password = :password, id_rol = :id_rol';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Limpiar los datos
            $this->id = limpiarVariable($this->id);
            $this->nombre = limpiarVariable($this->nombre);
            $this->apellidoPaterno = limpiarVariable($this->apellidoPaterno);
            $this->apellidoMaterno = limpiarVariable($this->apellidoMaterno);
            $this->numeroCelular = limpiarVariable($this->numeroCelular);
            $this->nombreUsuario = limpiarVariable($this->nombreUsuario);
            $this->correo = limpiarVariable($this->correo);
            $this->password = limpiarVariable($this->password);
            $this->id_rol = limpiarVariable($this->id_rol);

            // Vincular datos
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellidoPaterno', $this->apellidoPaterno);
            $stmt->bindParam(':apellidoMaterno', $this->apellidoMaterno);
            $stmt->bindParam(':numeroCelular', $this->numeroCelular);
            $stmt->bindParam(':nombreUsuario', $this->nombreUsuario);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':id_rol', $this->id_rol);
            
            // Validación
            try {
                  $stmt->execute();
                  $this->mensajeCapturista = 'Usuario Registrado de manera Exitosa.';
                  $this->mensaje = 'Query para crear un nuevo Usuario ejecutada con éxito.';
                  $this->status = 1;
            }catch (Exception $e){
                  $this->errorType = $e;
                  $MensajeError = $e->errorInfo[2];
  
                  // Comprobamos si hay una entrada duplicada
                  if(strpos($MensajeError,"Duplicate entry") !== NULL){
  
                        // Comprobamos cual es la entrada Duplicada
                        if(strpos($MensajeError,"correo") > 0){
                              $this->mensajeCapturista = "Error al registrar el Usuario, Correo: '".$this->correo."' duplicado."; 
                              $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, Correo duplicado.';     
                        }else if(strpos($MensajeError,"PRIMARY") > 0){ // ID Duplicado
                              $this->mensajeCapturista = "Error al registrar el Usuario, ID duplicado, contacte con el servício Técnico."; 
                              $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, ID duplicado.';   
                        }else if(strpos($MensajeError,"nombreUsuario") > 0){ // ID Duplicado
                              $this->mensajeCapturista = "Error al registrar el Usuario, Nombre de Usuario: '".$this->nombreUsuario."' duplicado."; 
                              $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, Nombre de Usuario duplicado.';    
                        }else if(strpos($MensajeError,"numeroCelular") > 0){ // ID Duplicado
                              $this->mensajeCapturista = "Error al registrar el Usuario, Número de Celular: '".$this->numeroCelular."' duplicado."; 
                              $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, Número de Celular duplicado.';    
                        }else{ // Otra entrada duplicada
                              $this->mensajeCapturista = 'Error al registrar el Usuario, alguna entrada está duplicada, contacte con el servício Técnico en caso de presentar más inconvenientes.';
                              $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, alguna entrada está duplicada.';   
                        }
  
                  }else{
                    $this->mensajeCapturista = 'Error desconocido al registrar el Usuario.';
                    $this->mensaje = 'Error desconocido al ejecutar la Query para crear un nuevo Usuario.';
                  }
                  
                  $this->status = 0;
            }
      }

      // Update Post
      public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . ' SET id = :id, nombre = :nombre, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno, numeroCelular = :numeroCelular, nombreUsuario = :nombreUsuario, correo = :correo, password = :password, id_rol = :id_rol WHERE id = :id';
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Limpiar los datos
            $this->id = limpiarVariable($this->id);
            $this->nombre = limpiarVariable($this->nombre);
            $this->apellidoPaterno = limpiarVariable($this->apellidoPaterno);
            $this->apellidoMaterno = limpiarVariable($this->apellidoMaterno);
            $this->numeroCelular = limpiarVariable($this->numeroCelular);
            $this->nombreUsuario = limpiarVariable($this->nombreUsuario);
            $this->correo = limpiarVariable($this->correo);
            $this->password = limpiarVariable($this->password);
            $this->id_rol = limpiarVariable($this->id_rol);

            // Vincular datos
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellidoPaterno', $this->apellidoPaterno);
            $stmt->bindParam(':apellidoMaterno', $this->apellidoMaterno);
            $stmt->bindParam(':numeroCelular', $this->numeroCelular);
            $stmt->bindParam(':nombreUsuario', $this->nombreUsuario);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':id_rol', $this->id_rol);
            
            // Execute query
            if($stmt->execute()) {
                  return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
      }

      // Delete Alumno
      public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            // Bind data
            $stmt->bindParam(':id', $this->id);
            // Execute query
            if($stmt->execute()) {
                  return true;
            }else{
                  // Print error if something goes wrong
                  printf("Error: %s.\n", $stmt->error);
                  return false;
            }
      }
      
   }