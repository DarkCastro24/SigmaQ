<?php
//Es una clase hija de Validator
class Pedidos extends Validator 
{
    //Declaración de atributos 
    private $idpedido = null;
    private $responsable = null;
    private $cliente = null;
    private $pos = null;
    private $oc = null;
    private $cantidadsolicitada = null;
    private $descripcion = null;
    private $codigo = null;
    private $cantidadenviada = null;
    private $fechaentrega = null;
    private $fechaconfirmadaenvio = null;
    private $comentarios = null;
    private $fecharegistro = null;

    //Funciones para asignar valores a los atributos
    public function setIdPedido($idPedido)
    {
        if($this->validateNaturalNumber($idPedido)) {
            $this->idpedido = $idPedido;
            return true;
        } else {
            return false;
        }
    }

    public function setResponsable($idResponsable)
    {
        if($this->validateNaturalNumber($idResponsable)) {
            $this->responsable = $idResponsable;
            return true;
        } else {
            return false;
        }
    }

    public function setCliente($idCliente)
    {
        if($this->validateNaturalNumber($idCliente)) {
            $this->cliente = $idCliente;
            return true;
        } else {
            return false;
        }
    }

    public function setPos($pos)
    {
        if($this->validateNaturalNumber($pos)) {
            $this->pos = $pos;
            return true;
        } else {
            return false;
        }
    }

    public function setOc($oc)
    {
        if($this->validateNaturalNumber($oc)) {
            $this->oc = $oc;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidadSolicitada($cantidadSolicitada)
    {
        if($this->validateNaturalNumber($cantidadSolicitada)) {
            $this->cantidadsolicitada = $cantidadSolicitada;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcion($descripcion) 
    {
        if($this->validateAlphaNumeric($descripcion, 1, 200)) {
            $this->descripcion = $descripcion;
            return true;
        } else {
            return false;
        }
    }

    public function setCodigo($codigo)
    {
        if($this->validateNaturalNumber($codigo)) {
            $this->codigo = $codigo;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidadEnviada($cantidadenviada)
    {
        if($this->validateNaturalNumber($cantidadenviada)) {
            $this->cantidadenviada = $cantidadenviada;
            return true;
        } else {
            return false;
        }
    }

    public function setFechaEntrega($fechaEntrega)
    {
        if($this->validateDate($fechaEntrega)) {
            $this->fechaentrega = $fechaEntrega;
            return true;
        } else {
            return false;
        }
    }

    public function setFechaConfirmadaEnvio($fechaconfirmadaenvio) 
    {
        if($this->validateDate($fechaconfirmadaenvio)) {
            $this->fechaconfirmadaenvio = $fechaconfirmadaenvio;
            return true;
        } else {
            return false;
        }
    }

    public function setComentarios($comentarios) 
    {
        if($this->validateAlphaNumeric($comentarios, 1, 150)) {
            $this->comentarios = $comentarios;
            return true;
        } else {
            return false;
        }
    }

    //Functiones para obtener los valores de los atributos
    public function getIdPedido() 
    {
        return $this->idpedido;
    }

    public function getResponsable() 
    {
        return $this->responsable;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    //Funciones para realizar los mantenimientos a la tabla
    
    //Función para insertar un registro para el índice
    public function insertPedido() 
    {
        $query="INSERT INTO pedido(responsable, cliente, pos, oc, cantidadsolicitada, descripcion, codigo, cantidadenviada, fechaentregada, fechaconfirmadaenvio, comentarios, fecharegistro) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, current_date)";
        $params=array($this->responsable, $this->cliente, $this->pos, $this->oc, $this->cantidadsolicitada, $this->descripcion, $this->codigo, $this->cantidadenviada, $this->fechaentrega, $this->fechaconfirmadaenvio, $this->comentarios);
        return Database::executeRow($query, $params);
    }

    //Función para eliminar un registro
    public function deleteAll() 
    {
        $query="DELETE FROM pedido";
        return Database::executeRow($query, null);
    }

    //Función para mostrar los índices
    public function readPedidos() 
    {
        $query="SELECT p.idpedido, p.responsable, CONCAT(a.nombre, ' ', a.apellido) as nombre_responsable, p.cliente, cl.usuario, p.pos, p.oc, p.cantidadsolicitada, p.descripcion, p.codigo, p.cantidadenviada, p.fechaentregada, p.fechaconfirmadaenvio, p.comentarios, p.fecharegistro, p.estado
                FROM pedido p
                INNER JOIN administradores a
                    ON p.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON p.cliente = cl.codigocliente
                ORDER BY p.estado DESC";
        $params=null;
        return Database::getRows($query, $params);
    }

    //Función para mostrar los clientes
    public function readClientes() 
    {
        $query="SELECT codigocliente,usuario,estado,empresa,telefono,correo,clave,intentos 
                from clientes 
                order by codigocliente";
        $params=null;
        return Database::getRows($query, $params);
    }

    // Metodo para cargar el codigo y usuario de un cliente
    public function readOneClientes()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT codigocliente,usuario from clientes where codigocliente = ?';
        // Envio de parametros
        $params = array($this->cliente);
        return Database::getRows($sql, $params);
    }

    // Función para listar los pedidos y ordenarlos por responsable
    public function readPedidosResponsable() 
    {
        $query="SELECT p.idpedido, p.responsable, CONCAT(a.nombre, ' ', a.apellido) as nombre_responsable, p.cliente, cl.usuario, p.pos, p.oc, p.cantidadsolicitada, p.descripcion, p.codigo, p.cantidadenviada, p.fechaentregada, p.fechaconfirmadaenvio, p.comentarios, p.fecharegistro, p.estado
                FROM pedido p
                INNER JOIN administradores a
                    ON p.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON p.cliente = cl.codigocliente
                    WHERE p.responsable = a.codigoadmin AND p.responsable = ?
                ORDER BY p.responsable";
        $params=array($this->responsable);
        return Database::getRows($query, $params);
    }

    //Función para mostrar un registro
    public function readOnePedido() 
    {
        $query="SELECT p.idpedido, p.responsable, a.codigoadmin, cl.codigocliente, CONCAT(a.nombre, ' ', a.apellido) as nombre_responsable, p.cliente, cl.usuario, p.pos, p.oc, p.cantidadsolicitada, p.descripcion, p.codigo, p.cantidadenviada, p.fechaentregada, p.fechaconfirmadaenvio, p.comentarios, p.fecharegistro, p.estado
                FROM pedido p
                INNER JOIN administradores a
                    ON p.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON p.cliente = cl.codigocliente
                WHERE p.idpedido = ?
                ORDER BY p.estado DESC";
        $params=array($this->idpedido);
        return Database::getRows($query, $params);
    }

    //Función para actualizar un registros
    public function updatePedido() 
    {
        $query="UPDATE pedido 
                SET responsable = ?, pos = ?, oc = ?, cantidadsolicitada = ?, descripcion = ?,"./*codigo = ?, */ " cantidadenviada = ?, fechaentregada = ?, fechaconfirmadaenvio = ?, comentarios = ?
                WHERE idpedido = ?";
        $params=array($this->responsable, $this->pos, $this->oc, $this->cantidadsolicitada, $this->descripcion, /*$this->codigo, */$this->cantidadenviada, $this->fechaentrega, $this->fechaconfirmadaenvio, $this->comentarios, $this->idpedido);
        return Database::executeRow($query, $params);
    }

    //Función para eliminar un registro
    public function desablePedido() 
    {
        $query="UPDATE pedido SET estado=false WHERE idpedido = ?";
        $params=array($this->idpedido);
        return Database::executeRow($query, $params);
    }

    //Función para activar un registro
    public function enablePedido() 
    {
        $query="UPDATE pedido SET estado=true WHERE idpedido = ?";
        $params=array($this->idpedido);
        return Database::executeRow($query, $params);
    }

    //Función para realizar una búsqueda en los registros
    public function searchRows($value) 
    {
        $query="SELECT p.idpedido, p.responsable, CONCAT(a.nombre, ' ', a.apellido) as nombre_responsable, p.cliente, cl.usuario, p.pos, p.oc, p.cantidadsolicitada, p.descripcion, p.codigo, p.cantidadenviada, p.fechaentregada, p.fechaconfirmadaenvio, p.comentarios, p.fecharegistro, p.estado
                FROM pedido p
                INNER JOIN administradores a
                    ON p.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON p.cliente = cl.codigocliente
                WHERE CONCAT(a.nombre, ' ', a.apellido) ILIKE ? OR cl.usuario ILIKE ? OR cl.empresa ILIKE ? OR cl.correo ILIKE ?
                ORDER BY p.estado DESC";
        $params=array("%$value%","%$value%","%$value%","%$value%");
        return Database::getRows($query, $params);
    }

    // Función para buscar un registro en el sitio público
    public function searchPedidosPublico($value)
    {
        $sql = "SELECT p.idpedido, p.responsable, CONCAT(a.nombre, ' ', a.apellido) as nombre_responsable, p.cliente, cl.usuario, p.pos, p.oc, p.cantidadsolicitada, p.descripcion, p.codigo, p.cantidadenviada, p.fechaentregada, p.fechaconfirmadaenvio, p.comentarios, p.fecharegistro, p.estado
        FROM pedido p
        INNER JOIN administradores a
            ON p.responsable = a.codigoadmin
        INNER JOIN clientes cl
            ON p.cliente = cl.codigocliente
        WHERE CONCAT(a.nombre, ' ', a.apellido) LIKE ? OR CAST(p.codigo AS CHAR) LIKE ? AND cliente = ?
        ORDER BY p.estado DESC";
        $params = array("%$value%","%$value%", $_SESSION['codigocliente']);
        return Database::getRows($sql, $params);
    }

    //Función para realizar validar el codigo del pedido
    public function checkCode() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT * FROM pedido WHERE codigo = ?";
        $params=array($this->codigo);
        return Database::getRow($query, $params);
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

    //Función para leer los datos del responsable de cierto cliente
    public function readResponsableInfo() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT ad.nombre, ad.apellido, telefono, correo
                FROM pedido pe
                INNER JOIN administradores ad
                    ON ad.codigoadmin = pe.responsable
                WHERE cliente = ? AND ad.estado = true";
        $params = array($this->cliente);
        return Database::getRows($query, $params);
    }

    //Función para obtener la sumatoria de cantidad enviada en los últimos 12 meses
    public function cantidadEnviadaMensual() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT EXTRACT(MONTH FROM fechaconfirmadaenvio) as mes, sum(cantidadenviada) cantidadMensual
                FROM pedido
                GROUP BY mes ORDER BY mes ASC LIMIT 12";
        $params= null;
        return Database::getRows($query, $params);
    }

    //Función para obtener los 5 clientes que más pedidos han realizado
    public function clientesTop() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT c.usuario, count(p.cliente) pedidos
            FROM pedido p
            INNER JOIN clientes c
            ON c.codigocliente = p.cliente
        GROUP BY usuario ORDER BY pedidos DESC LIMIT 5";
        $params=null;
        return Database::getRows($query, $params);
    }

    //Función para listar los reponsables
    public function readResponsables() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT a.codigoadmin,CONCAT(nombre,' ',apellido) AS responsable
        FROM administradores a INNER JOIN pedido p ON a.codigoadmin = p.responsable GROUP BY a.codigoadmin,responsable";
        $params= null;
        return Database::getRows($query, $params);
    }
}
