<?php 

   include_once '../../utilities/tools.php';

   class usuarioComun {

      // DB stuff
      private $conn;
      private $table = 'usuariosComunes';

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
      public $correo;
      public $fechaNacimiento;
      public $edad;
      public $sexo;
      public $curp;
      public $numeroCasa;
      public $numeroCelular;
      public $calle;
      public $numeroExterior;
      public $numeroInterior;
      public $colonia;
      public $estado;
      public $codigoPostal;

      // Constructor with DB
      public function __construct($db) {
            $this->conn = $db;
      }

      // Función para Leer todos los periodos Escolares
      public function read() {
            // Crear la query SQL
            $query = 'SELECT * FROM ' . $this->table;
            // Preparar la declaracion
            $stmt = $this->conn->prepare($query);

            // Validación
            try {
                  $stmt->execute();
                  $this->mensaje = 'Query para consultar a todos los Usuarios ejecutada de manera exitosa.';
                  $this->status = 1;
                  $this->mensajeCapturista = "Usuarios consultados de manera exitosa.";
                  return $stmt;
            }catch (Exception $e){
                  $this->errorType = $e;
                  $this->mensaje = 'Error al ejecutar la Query para consultar a todos los Usuarios.';
                  $this->mensajeCapturista = "Ocurrió un error interno al consultar a los Usuarios.";
                  $this->status = 0;
                  return false;
            }
      }

      // Función para Leer un Usuario
      public function read_single() {
            
            // Crear la query SQL
            $query = 'SELECT * FROM ' . $this->table . ' WHERE '.$this->nombreVariable." LIKE ?";
            // Preparar la declaracion
            $stmt = $this->conn->prepare($query);
            // Limpiar los datos
            $this->contenidoVariable = "%".$this->contenidoVariable."%";
            $this->contenidoVariable = limpiarVariable($this->contenidoVariable);
            // Vincular datos
            $stmt->bindParam(1, $this->contenidoVariable);

            // Validación
            try {
                  $stmt->execute();
                  $this->mensaje = 'Query para consultar Usuario(s) con '.$this->nombreVariable." (LIKE) '".$this->contenidoVariable."' ejecutada de manera exitosa.";
                  $this->status = 1;
                  return $stmt;
            }catch (Exception $e){
                  $this->errorType = $e;
                  $this->mensaje = 'Error al ejecutar la Query para consultar Usuario(s) con '.$this->nombreVariable." (LIKE) '".$this->contenidoVariable."'";
                  $this->status = 0;
                  return false;
            }

      }

      // Función para crear un Usuario
      public function create() {
            // Crear la query SQL
            $query = 'INSERT INTO ' . $this->table . ' SET id = :id, nombre = :nombre, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno, correo = :correo, fechaNacimiento = :fechaNacimiento, edad = :edad, sexo = :sexo, curp = :curp, numeroCasa = :numeroCasa, numeroCelular = :numeroCelular, calle = :calle, numeroExterior = :numeroExterior, numeroInterior = :numeroInterior, colonia = :colonia, estado = :estado, codigoPostal = :codigoPostal';

            // Preparar la declaracion
            $stmt = $this->conn->prepare($query);
            
            // Limpiar los datos
            $this->id = limpiarVariable($this->id);
            $this->nombre = limpiarVariable($this->nombre);
            $this->apellidoPaterno = limpiarVariable($this->apellidoPaterno);
            $this->apellidoMaterno = limpiarVariable($this->apellidoMaterno);
            $this->correo = limpiarVariable($this->correo);
            $this->fechaNacimiento = limpiarVariable($this->fechaNacimiento);
            $this->edad = limpiarVariable($this->edad);
            $this->sexo = limpiarVariable($this->sexo);
            $this->curp = limpiarVariable($this->curp);
            $this->numeroCasa = limpiarVariable($this->numeroCasa);
            $this->numeroCelular = limpiarVariable($this->numeroCelular);
            $this->calle = limpiarVariable($this->calle);
            $this->numeroExterior = limpiarVariable($this->numeroExterior);
            $this->numeroInterior = limpiarVariable($this->numeroInterior);
            $this->colonia = limpiarVariable($this->colonia);
            $this->estado = limpiarVariable($this->estado);
            $this->codigoPostal = limpiarVariable($this->codigoPostal);
            
            // Vincular datos
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellidoPaterno', $this->apellidoPaterno);
            $stmt->bindParam(':apellidoMaterno', $this->apellidoMaterno);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->bindParam(':fechaNacimiento', $this->fechaNacimiento);
            $stmt->bindParam(':edad', $this->edad);
            $stmt->bindParam(':sexo', $this->sexo);
            $stmt->bindParam(':curp', $this->curp);
            $stmt->bindParam(':numeroCasa', $this->numeroCasa);
            $stmt->bindParam(':numeroCelular', $this->numeroCelular);
            $stmt->bindParam(':calle', $this->calle);
            $stmt->bindParam(':numeroExterior', $this->numeroExterior);
            $stmt->bindParam(':numeroInterior', $this->numeroInterior);
            $stmt->bindParam(':colonia', $this->colonia);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':codigoPostal', $this->codigoPostal);
            
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
  
                    // Comprobamos si la entrada duplicada es la CURP
                    if(strpos($MensajeError,"correo") > 0){ // Correo Duplicado
                          $this->mensajeCapturista = "Error al registrar el Usuario, Correo: '".$this->correo."' duplicado."; 
                          $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, Correo duplicado.';     
                    }else if(strpos($MensajeError,"curp") > 0){ // CURP Duplicado
                          $this->mensajeCapturista = "Error al registrar el Usuario, CURP: '".$this->curp."' duplicado."; 
                          $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, CURP duplicado.';      
                    }else if(strpos($MensajeError,"numeroCelular") > 0){ // Numero de Celular Duplicado
                          $this->mensajeCapturista = "Error al registrar el Usuario, Número de Celular: '".$this->numeroCelular."' duplicado."; 
                          $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, numeroCelular duplicado.';      
                    }else if(strpos($MensajeError,"PRIMARY") > 0){ // ID Duplicado
                          $this->mensajeCapturista = "Error al registrar el Usuario, ID duplicado, contacte con el servício Técnico."; 
                          $this->mensaje = 'Error al ejecutar la Query para crear un nuevo Usuario, ID duplicado.';   
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

      // Función para Actualizar los datos de un Usuario
      public function update() {

            // Crear la query SQL
            $query = 'UPDATE ' . $this->table . ' SET id = :id, nombre = :nombre, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno, correo = :correo, fechaNacimiento = :fechaNacimiento, edad = :edad, sexo = :sexo, curp = :curp, numeroCasa = :numeroCasa, numeroCelular = :numeroCelular, calle = :calle, numeroExterior = :numeroExterior, numeroInterior = :numeroInterior, colonia = :colonia, estado = :estado, codigoPostal = :codigoPostal WHERE '.$this->nombreVariable.' = :contenidoVariable';
            
            // Preparar la declaracion
            $stmt = $this->conn->prepare($query);
            
            // Limpiar los datos
            $this->id = limpiarVariable($this->id);
            $this->nombre = limpiarVariable($this->nombre);
            $this->apellidoPaterno = limpiarVariable($this->apellidoPaterno);
            $this->apellidoMaterno = limpiarVariable($this->apellidoMaterno);
            $this->correo = limpiarVariable($this->correo);
            $this->fechaNacimiento = limpiarVariable($this->fechaNacimiento);
            $this->edad = limpiarVariable($this->edad);
            $this->sexo = limpiarVariable($this->sexo);
            $this->curp = limpiarVariable($this->curp);
            $this->numeroCasa = limpiarVariable($this->numeroCasa);
            $this->numeroCelular = limpiarVariable($this->numeroCelular);
            $this->calle = limpiarVariable($this->calle);
            $this->numeroExterior = limpiarVariable($this->numeroExterior);
            $this->numeroInterior = limpiarVariable($this->numeroInterior);
            $this->colonia = limpiarVariable($this->colonia);
            $this->estado = limpiarVariable($this->estado);
            $this->codigoPostal = limpiarVariable($this->codigoPostal);
            
            // Vincular datos
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellidoPaterno', $this->apellidoPaterno);
            $stmt->bindParam(':apellidoMaterno', $this->apellidoMaterno);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->bindParam(':fechaNacimiento', $this->fechaNacimiento);
            $stmt->bindParam(':edad', $this->edad);
            $stmt->bindParam(':sexo', $this->sexo);
            $stmt->bindParam(':curp', $this->curp);
            $stmt->bindParam(':numeroCasa', $this->numeroCasa);
            $stmt->bindParam(':numeroCelular', $this->numeroCelular);
            $stmt->bindParam(':calle', $this->calle);
            $stmt->bindParam(':numeroExterior', $this->numeroExterior);
            $stmt->bindParam(':numeroInterior', $this->numeroInterior);
            $stmt->bindParam(':colonia', $this->colonia);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':codigoPostal', $this->codigoPostal);

            // Limpiar los datos del contenidoVariable
            $this->contenidoVariable = limpiarVariable($this->contenidoVariable);
            // Vincular datos del contenidoVariable
            $stmt->bindParam(':contenidoVariable', $this->contenidoVariable);
            
            // Validación
            try {
                  $stmt->execute();
                  $this->mensajeCapturista = "Usuario actualizado con éxito.";
                  $this->mensaje = 'Query para actualizar Usuario ejecutada de manera exitosa. ('.$this->nombreVariable.": '".$this->contenidoVariable."')";
                  $this->status = 1;
            }catch (Exception $e){
                  $this->errorType = $e;
                  $this->mensajeCapturista = "Error interno al intentar actualizar el Usuario.";
                  $this->mensaje = 'Error ejecutar la Query para Actualizar el Usuario. ('.$this->nombreVariable.": '".$this->contenidoVariable."')";
                  $this->status = 0;
            }
      }

      // Función para Eliminar un Usuario
      public function delete() {

            // INICIO Comprobar la existencia del Usuario
            // Crear la query SQL
            $query = 'SELECT * FROM ' . $this->table . ' WHERE '.$this->nombreVariable.' IN (?)';
            // Preparar la declaracion
            $stmt = $this->conn->prepare($query);
            // Limpiar los datos
            $this->contenidoVariable = limpiarVariable($this->contenidoVariable);
            // Vincular datos
            $stmt->bindParam(1, $this->contenidoVariable);
            // Validación
            try {
                  $stmt->execute();
                  $this->mensaje = 'Query para consultar Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."' ejecutada de manera exitosa.";
                  $this->mensajeCapturista = 'Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."' consultado de manera exitosa.";
                  $this->status = 1;
            }catch (Exception $e){
                  $this->errorType = $e;
                  $this->mensaje = 'Error al ejecutar la Query para consultar el Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."'";
                  $this->mensajeCapturista = 'Error al consultar el Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."'";
                  $this->status = 0;
            }
            // FIN Comprobar la existencia del Usuario

            // Fetch al resultado de la consulta
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se comprueba si el fetch tiene resultados, osea, si el Usuario consultado existe
            if($row['id'] !== NULL){ // En caso de que el Usuario si exista
                  // Crear la query SQL
                  $query = 'DELETE FROM ' . $this->table . ' WHERE '.$this->nombreVariable.' = ?';
                  // Preparar la declaracion
                  $stmt = $this->conn->prepare($query);
                  // Limpiar los datos
                  $this->contenidoVariable = limpiarVariable($this->contenidoVariable);
                  // Vincular datos
                  $stmt->bindParam(1, $this->contenidoVariable);

                  // Validación
                  try {
                        $stmt->execute();
                        $this->mensaje2 = 'Query para eliminar Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."' ejecutada de manera exitosa.";
                        $this->mensajeCapturista = 'Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."' eliminado de manera exitosa.";
                        $this->status = 1;
                  }catch (Exception $e){
                        $this->errorType = $e;
                        $MensajeError = $e->errorInfo[2];

                        // Comprobamos si hay una entrada duplicada
                        if(strpos($MensajeError,"a foreign key constraint fails") !== NULL){
                              $this->mensajeCapturista = 'Error desconocido al registrar el Usuario.';
                              $this->mensaje2 = 'Error desconocido al ejecutar la Query para eliminar el Usuario';
                        }else{
                              $this->mensajeCapturista = 'Error desconocido al registrar el Usuario.';
                              $this->mensaje2 = 'Error desconocido al ejecutar la Query para eliminar el Usuario';
                        }
                        
                        $this->status = 0;
                  }
            }else{ // En caso de que el Usuario no exista
                  $this->mensaje2 = 'No se puede ejecutar la Query para eliminar el Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."' porque dicho Usuario no existe.";
                  $this->mensajeCapturista = 'No se puede eliminar el Usuario con '.$this->nombreVariable." '".$this->contenidoVariable."' porque no existe.";
            }
      }
      
}