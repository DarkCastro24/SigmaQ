<?php
//Es una clase hija de Validator
class Cliente extends Validator
{
    // Declaracion de los atributos de la clase
    private $id = null;
    private $estado = null;
    private $empresa = null;
    private $telefono = null;
    private $correo = null;  
    private $usuario = null;  
    private $clave = null;  
    private $codigo = null;  

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
    public function validateNull($value)
    {
        if ($value != null) {
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para validar si el contenido del input esta vacio
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

    /* Funcion para validar si el valor del codigo es numerico
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

    /* Funcion para validar si el nombre de la empresa es de tipo String 
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */ 
    public function setEmpresa($value)
    {
        if ($this->validateString($value, 1, 40)) {
            $this->empresa = $value;
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

    /* Funcion para validar si el usuario posee el tipo de dato correcto
    *  Parámetro: valor del input  
    *  Retorna un valor tipo booleano
    */
    public function setUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
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

    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }
    
    public function getCorreo()
    {
        return $this->correo;
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
        $sql = 'SELECT estado FROM clientes where usuario = ? and estado = 1';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para desactivar un cliente por superar el limite de intentos permitidos
    public function desactivateClient($usuario)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE clientes SET estado = 2 WHERE usuario = ?;';
        $params = array($usuario);
        return Database::executeRow($sql, $params);
    }

    // Funcion para validar si existe un usuario en la base de datos requiere el nombre del usuario como parametro
    public function checkUser($usuario)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigocliente,estado,empresa,correo FROM clientes WHERE usuario = ?';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['codigocliente'];
            $this->empresa = $data['empresa'];
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
        $sql = 'SELECT clave FROM clientes WHERE codigocliente = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para busqueda filtrada requiere el valor que se desea buscar 
    public function searchRows($value)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigocliente,estado,empresa,telefono,correo,usuario,clave,intentos 
        from clientes
        WHERE CAST(codigocliente AS CHAR) LIKE ? OR codigocliente = ?
        order by codigocliente';
        $params = array("%$value%",$value);
        return Database::getRows($sql, $params);
    }

    // Funcion verificar si existen usuarios activos en la base de datos
    public function readIndex()
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT codigocliente,estado,empresa,telefono,correo,usuario,clave,intentos 
        from clientes
        where estado = 1';
        $params = null;
        return Database::getRows($sql, $params);
    }
    
    // Funcion para cargar todos los registros en la tabla 
    public function readAll()
    {
        $sql = 'SELECT codigocliente,usuario,estado,empresa,telefono,correo,clave,intentos 
        from clientes 
        order by codigocliente';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para cambiar el estado de un usuario a activo
    public function activateUser()
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE clientes
        SET estado = 1
        WHERE codigocliente = ?;';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para registrar un usuario en la base de datos
    public function createRow()
    {
        // Encriptacion de la clave mediante el metodo password_hash
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        // Declaracion de la sentencia SQL 
        $sql = 'INSERT INTO clientes(codigocliente, estado, empresa, telefono, correo, usuario, clave, intentos)
            VALUES (?, default, ?, ?, ?, ?, ?, default)';
        $params = array($this->id,$this->empresa, $this->telefono,$this->correo,$this->usuario, $hash);
        return Database::executeRow($sql, $params);
    }

    // Funcion para actualizar un usuario en la base de datos
    public function updatePassword()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE clientes set clave = ? , estado = 1, fechaClave = default where correo = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($hash , $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Funcion para actualizar los datos de un cliente de la base de datos
    public function updateRow()
    {
        // Verifica si existe clave en caso de no existir se actualizan los datos menos la clave
        if ($this->clave != null) {
            // Se encripta la contraseña mediante el metodo password_hash
            $hash = password_hash($this->clave, PASSWORD_DEFAULT);
            // Declaracion de la sentencia SQL 
            $sql = 'UPDATE clientes
            SET codigocliente = ?, empresa= ?, telefono = ?, correo = ?, usuario = ?, clave = ?
            WHERE codigocliente = ?';
            $params = array($this->id ,$this->empresa, $this->telefono, $this->correo,$this->usuario,$hash,$this->codigo);
        } else {
            $sql = 'UPDATE clientes
            SET codigocliente = ?, empresa= ?, telefono = ?, correo = ?, usuario = ?
            WHERE codigocliente = ?';
            $params = array($this->id ,$this->empresa, $this->telefono, $this->correo,$this->usuario,$this->codigo);
        }    
        return Database::executeRow($sql, $params);
    }

    // Funcion para cargar los datos de un cliente en especifico
    public function readRow()
    {
        $sql = 'SELECT codigocliente,usuario, estado,empresa,telefono,correo,clave,intentos 
        from clientes 
        where codigocliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Funcion para actualizar los datos de un usuario de la base de datos
    public function editProfile($codigo)
    {
        $sql = 'UPDATE public.clientes
        SET telefono = ?, correo = ?
        WHERE codigocliente=?;';
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array( $this->telefono, $this->correo,$codigo);
        return Database::executeRow($sql, $params);
    }

    // Funcion para cambiar la clave del usuario requiere de parametro el codigo de administrador de la variable de sesion 
    public function changePassword($value)
    {
        // Se encripta la contraseña mediante la funcion password_hash
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE clientes SET clave = ? WHERE codigocliente = ?';
        $params = array($hash, $value);
        return Database::executeRow($sql, $params);
    }

    // Funcion para cargar los datos de un cliente en especifico
    public function readProfile($value)
    {
        $sql = 'SELECT codigocliente,usuario, estado,empresa,telefono,correo,clave,intentos 
        from clientes 
        where codigocliente = ?';
        $params = array($value);
        return Database::getRow($sql, null);
    }

    // Funcion para cambiar el estado de un cliente a desactivado 
    public function desactivateUser()
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE clientes
        SET estado = 2
        WHERE codigocliente = ?;';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para reporte de los 5 clientes con mas acciones realizadas.
    public function graficaClientes()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT c.usuario,COUNT(codigocliente) as cantidad
        FROM historialcliente h
        INNER JOIN clientes c ON c.codigocliente = h.usuario
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
		FROM historialcliente h 
		INNER JOIN clientes c ON h.usuario = c.codigocliente
		WHERE h.usuario = ?
		GROUP BY h.accion
		ORDER BY cantidad DESC
        LIMIT 5";
        // Envio de parametros
        $params = array($parametro);
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar los estados de la base de datos
    public function readEstado()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT ec.estado,count(ec.estado) as cantidad 
        from clientes c 
		INNER JOIN estadoCliente ec ON ec.idestado = c.estado 
		group by ec.estado order by estado asc';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar registros de un estado en especifico
    public function readUsuariosEstado()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT codigocliente,empresa,usuario,telefono,correo,estado
        FROM clientes
        WHERE estado = ? 
        ORDER BY codigocliente';
        // Envio de parametros
        $params = array($this->estado);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar el codigo y usuario de un cliente
    public function readClientes()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT codigocliente,usuario from clientes where codigocliente = ?';
        // Envio de parametros
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar las acciones realizadas por un cliente en el sistema
    public function readAccionesCliente()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT accion,hora FROM historialcliente
		WHERE usuario = ? ORDER BY hora DESC';
        // Envio de parametros
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar los datos de las acciones realizadas por el cliente.
    public function readAccionesParam($fechaInicio,$fechaFin)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT accion,hora FROM historialcliente
        WHERE usuario = ? and hora BETWEEN ? and ? 
        ORDER BY hora DESC";
        // Envio de parametros
        $params = array($this->id,$fechaInicio,$fechaFin);
        return Database::getRows($sql, $params);
    }

    //Función para cargar los pedidos pertenecientes a un cliente
    public function readClientePedidos() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT p.idpedido, p.responsable, CONCAT(a.nombre, ' ', a.apellido) as nombre_responsable, p.cliente, cl.usuario, p.pos, p.oc, p.cantidadsolicitada, p.descripcion, p.codigo, p.cantidadenviada, p.fechaentregada, p.fechaconfirmadaenvio, p.comentarios, p.fecharegistro, p.estado
                FROM pedido p
                INNER JOIN administradores a
                    ON p.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON p.cliente = cl.codigocliente
                WHERE cliente = ? AND p.estado = true
                ORDER BY p.estado DESC";
        $params = array($this->cliente);
        return Database::getRows($query, $params);
    }

    //Función para obtener los pedidos semanales de un usuario
    public function pedidosSemanales() {
        //Creamos la sentencia SQL
        $query="SELECT to_date( CONCAT(EXTRACT(YEAR FROM fechaentregada), '-', EXTRACT (WEEK FROM fechaentregada)) , 'iyyy-iw' ) as semana, COUNT(idpedido) numero_pedidos
                FROM pedido
                WHERE cliente = ?
                GROUP BY semana ORDER BY semana DESC LIMIT 12";
        //Le pasamos los parámetros
        $params = array($this->id);
        return Database::getRows($query, $params);
    }

}