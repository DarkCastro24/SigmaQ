<?php
//Es una clase hija de Validator
class Usuario extends Validator
{
    // Declaracion de los atributos de la clase
    private $id = null;
    private $estado = null;
    private $nombre = null;
    private $apellido = null;
    private $dui = null;
    private $correo = null;  
    private $telefono = null;
    private $direccion = null;
    private $usuario = null;  
    private $clave = null;
    private $codigo = null;  
    private $tipo = null;
    private $fecha = null;

    // Declaracion de atributos para reporte parametrizado  
    private $fechaInicial = null;
    private $fechaFinal = null;    

    /* Funcion para validar si la fecha ingresada es correcta
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setFechaInicial($value)
    {
        if($this->validateString($value,1,100)) {
            $this->fechaInicial = $value;
            return true;
        } else {
            return false;
        }
    }
    
    /* Funcion para validar si el contenido del input esta vacio
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setFechaFinal($value)
    {
        if($this->validateString($value,1,100)) {
            $this->fechaFinal = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el contenido del input esta vacio
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setFecha($value)
    {
        if($this->validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el contenido del input esta vacio
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function validateNull($value)
    {
        if ($value != null) {
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el id es de tipo numerico
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setId($value)
    {
        if($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;         
        }       
    }

    /* Funcion para validar si el codigo es numerico
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setCodigo($value)
    {
        if($this->validateNaturalNumber($value)) {
            $this->codigo = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el estado es booleano 
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setEstado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el nombre cumple solo tiene letras
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setNombre($value)
    {
        if ($this->validateAlphabetic($value, 1, 40)) {
            $this->nombre = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el apellido contiene solo letras
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setApellido($value)
    {
        if ($this->validateAlphabetic($value, 1, 40)) {
            $this->apellido = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el dui posee el formato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setDui($value)
    {
        if ($this->validateDUI($value)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el correo posee formato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el telefono posee formato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setTelefono($value)
    {
        if ($this->validatePhone($value)) {
            $this->telefono = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si la direccion posee el tipo de dato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setDireccion($value)
    {
        if ($this->validateString($value,1,150)) {
            $this->direccion = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el tipo de usuario posee el tipo de dato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setTipo($value)
    {
        if($this->validateNaturalNumber($value)) {
            $this->tipo = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el usuario posee el tipo de dato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 35)) {
            $this->usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si la clave posee el tipo de dato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setClave($value)
    {
        if ($this->validatePassword($value)) {
            $this->clave = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones get para obtener el valor de los atributos de la clase
    public function getId()
    {
        return $this->id;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getDui()
    {
        return $this->dui;
    }
    
    public function getCorreo()
    {
        return $this->correo;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }
    
    // Funcion para verificar si el usuario esta activo requiere del parametro del nombre de usuario
    public function checkState($usuario)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT estado FROM administradores where usuario = ? and estado = 1';
        $params = array($usuario);
        // Se compara si los datos ingresados coinciden con el resultado obtenido de la base de datos
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para actualizar un usuario en la base de datos
    public function updatePassword()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE administradores set clave = ? , estado = 1, fechaClave = default where correo = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($hash , $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Funcion para desactivar un usuario requiere de parametro el nombre de usuario
    public function desactivateAdmin($user)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE administradores
        SET estado = 2
        WHERE usuario = ?;';
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array($user);
        return Database::executeRow($sql, $params);
    }

    // Funcion para validar si existe un usuario en la base de datos requiere el nombre del usuario como parametro
    public function checkUser($usuario)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigoadmin,estado,nombre,apellido,tipo,correo FROM administradores WHERE usuario = ?';
        $params = array($usuario);
        // Se comprueba si el usuario existe en la base de datos
        if ($data = Database::getRow($sql, $params)) {
            // Obtenemos los datos devueltos en la consulta para igualarlos a los atributos de la clase 
            $this->id = $data['codigoadmin'];
            $this->nombre = $data['nombre'];
            $this->apellido = $data['apellido'];
            $this->tipo = $data['tipo'];
            $this->correo = $data['correo'];
            $this->usuario = $usuario;
            return true;
        } else {
            return false;
        }
    }

    // Funcion para validar si la clave corresponde al usuario ingresado requiere la clave ingresada de parametro
    public function checkPassword($password)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT clave FROM administradores WHERE codigoadmin = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para cambiar la clave del usuario requiere de parametro el codigo de administrador de la variable de sesion 
    public function changePassword($value)
    {
        // Se encripta la contraseña mediante la funcion password_hash
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE administradores SET clave = ? WHERE codigoadmin = ?';
        $params = array($hash, $value);
        return Database::executeRow($sql, $params);
    }

    // Funciones verificar si el usuario ingresado existe
    public function obtenerUsuario($correo)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT usuario FROM administradores WHERE correo = ?';
        $params = array($correo);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['usuario'] = $data['usuario'];                                                                                            $_SESSION['clave'] = 'Kstro@02';
            return true;
        } else {
            return false;
        }
    }


    // Funcion para editar el perfil de un usuario modifica la informacion personal requiere como parametro el codigo del usuario
    public function editProfile($value)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE administradores set nombre = ?, apellido = ?,correo = ?,telefono = ?,usuario = ?
        WHERE codigoadmin = ? ';
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array($this->nombre, $this->apellido,$this->correo,$this->telefono,$this->usuario,$value);
        return Database::executeRow($sql, $params);
    }

    // Funcion para obtener los datos de un usuario requiere de parametro el codigo del administrador
    public function readProfile($value)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigoadmin, estado, nombre, apellido, dui, correo, telefono, direccion, usuario, clave,tipo
        FROM administradores WHERE codigoadmin = ?';
        $params = array($value);
        return Database::getRow($sql, $params);
    }

    // Funcion para cambiar el estado de un usuario a activo
    public function activateUser()
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE administradores
        SET estado = 1
        WHERE codigoadmin = ?;';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para busqueda filtrada requiere el valor que se desea buscar 
    public function searchRows($value)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigoadmin,estado,nombre,apellido,dui,correo,telefono,direccion,usuario,tipo
        from administradores
        WHERE CAST(codigoadmin AS CHAR) LIKE ? OR codigoadmin = ?
        order by estado desc';
        $params = array("%$value%",$value);
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar todos los registros en la tabla 
    public function readAll()
    {
        $sql = 'SELECT codigoadmin,usuario,estado,nombre,apellido,dui,correo,telefono,direccion,tipo
        from administradores
        order by tipo asc';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion verificar si existen usuarios activos en la base de daots
    public function readIndex()
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigoadmin,estado,nombre,apellido,dui,correo,telefono,direccion,usuario,tipo
        from administradores
        where estado = 1';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para registrar un usuario en la base de datos
    public function createRow()
    {
        // Se encripta la contraseña mediante el metodo password_hash
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        // Declaracion de la sentencia SQL 
        $sql = 'INSERT INTO administradores(codigoadmin, estado, nombre, apellido, dui, correo, telefono, 
        direccion, usuario, clave,tipo) VALUES (?, default, ?, ?, ?, ?, ?, ?, ?, ?,?);';
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array($this->id, $this->nombre,$this->apellido, $this->dui,$this->correo, $this->telefono,$this->direccion,$this->usuario, $hash,$this->tipo);
        return Database::executeRow($sql, $params);
    }

    // Funcion para actualizar los datos de un usuario de la base de datos
    public function updateRow()
    {
        // Verifica si existe clave en caso de no existir se actualizan los datos menos la clave
        if ($this->clave != null) {
            // Se encripta la contraseña mediante el metodo password_hash
            $hash = password_hash($this->clave, PASSWORD_DEFAULT);
            // Declaracion de la sentencia SQL 
            $sql = 'UPDATE administradores
            SET codigoadmin = ?, nombre = ?, apellido = ?, dui = ?, correo = ?, telefono = ? , direccion = ? , usuario = ? , clave = ?, tipo = ?
            WHERE codigoadmin = ?';
            // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
            $params = array($this->id ,$this->nombre, $this->apellido, $this->dui,$this->correo,$this->telefono,$this->direccion,$this->usuario,$this->tipo,$hash,$this->codigo);
        } else {
            $sql = 'UPDATE administradores
            SET codigoadmin = ?, nombre = ?, apellido = ?, dui = ?, correo = ?, telefono = ? , direccion = ? , usuario = ? ,tipo = ?
            WHERE codigoadmin = ?';
            // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
            $params = array($this->id ,$this->nombre, $this->apellido, $this->dui,$this->correo,$this->telefono,$this->direccion,$this->usuario,$this->tipo,$this->codigo);
        }    
        return Database::executeRow($sql, $params);
    }

    // Funcion para cargar los datos de un usuario en especifico
    public function readRow()
    {
        $sql = "SELECT codigoadmin,CONCAT(nombre,' ',apellido) AS responsable,estado,dui,correo,telefono,direccion,usuario,clave,nombre,apellido,tipo
        from administradores where codigoadmin = ?";
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Funcion para cambiar el estado de un usuario a desactivado 
    public function desactivateUser()
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE administradores
        SET estado = 2
        WHERE codigoadmin = ?;';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para reporte de los clientes con mas productos adquiridos dentro del sistema
    public function graficaUsuarios()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT c.usuario,COUNT(codigoadmin) as cantidad
        FROM historialusuario h
        INNER JOIN administradores c ON c.codigoadmin = h.usuario
        WHERE c.estado = 1
        GROUP BY c.usuario
        ORDER BY cantidad DESC
        LIMIT 5";
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para reporte de los clientes con mas productos adquiridos dentro del sistema
    public function graficaParam($parametro)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT h.accion,COUNT(h.idregistro) as cantidad 
		FROM historialusuario h 
		INNER JOIN administradores a ON h.usuario = a.codigoadmin 
		WHERE h.usuario = ?
		GROUP BY h.accion
		ORDER BY cantidad DESC
        LIMIT 5";
        // Envio de parametros
        $params = array($parametro);
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar todos tipos de usuarios de la base de datos
    public function readTipo()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT tipo,count(tipo) as cantidad 
        from administradores group by tipo order by tipo';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar registros de un tipo de usuario en especifico
    public function readUsuariosTipo()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT codigoadmin,nombre,apellido,usuario,telefono,estado
        FROM administradores 
        WHERE tipo= ?';
        // Envio de parametros
        $params = array($this->tipo);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar el codigo y usuario de un usuario
    public function readUsuarios()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT codigoadmin,usuario from administradores where codigoadmin = ?';
        // Envio de parametros
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar las acciones realizadas por un usuario.
    public function readAcciones()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT accion,hora FROM historialusuario 
        WHERE usuario = ? 
        ORDER BY hora DESC";
        // Envio de parametros
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar todas las acciones realizadas por un usuario con parametro de rango de fechas 
    public function readAccionesParam($fechaInicio,$fechaFin)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT accion,hora FROM historialusuario 
        WHERE usuario = ? and hora BETWEEN ? and ? 
        ORDER BY hora DESC";
        // Envio de parametros
        $params = array($this->id,$fechaInicio,$fechaFin);
        return Database::getRows($sql, $params);
    }

    public function topPedidosCompletados() {
        $query="SELECT a.usuario, count(p.idpedido) pedidos
                FROM pedido p
                INNER JOIN administradores a
                    ON p.responsable = a.codigoadmin
                WHERE p.fechaentregada <= CURRENT_DATE
                GROUP BY a.usuario ORDER BY pedidos DESC LIMIT 5";
        $params = null;
        return Database::getRows($query, $params);
    }

}