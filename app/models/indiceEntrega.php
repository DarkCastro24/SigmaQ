<?php
//Es una clase hija de Validator
class Indice extends Validator 
{
    //Declaración de atributos 
    private $idindice = null;
    private $responsable = null;
    private $cliente = null;
    private $organizacion = null;
    private $indice = null;
    private $totalcompromiso = null;
    private $cumplidos = null;
    private $nocumplidos = null;
    private $noconsiderados = null;
    private $incumnoentregados = null;
    private $incumporcalidad = null;
    private $incumporfecha = null;
    private $incumporcantidad = null;

    //Funciones para asignar valores a los atributos
    public function setIdIndice($idIndice)
    {
        if($this->validateNaturalNumber($idIndice)) {
            $this->idindice = $idIndice;
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

    public function setOrganizacion($organizacion) 
    {
        if($this->validateAlphaNumeric($organizacion, 1, 20)) {
            $this->organizacion = $organizacion;
            return true;
        } else {
            return false;
        }
    }

    public function setIndice($indice) 
    {
        if($this->validateMoney($indice)) {
            $this->indice = $indice;
            return true;
        } else {
            return false;
        }
    }

    public function setTotalCompromiso($compromiso)
    {
        if($this->validateNaturalNumber($compromiso)) {
            $this->totalcompromiso = $compromiso;
            return true;
        } else {
            return false;
        }
    }

    public function setCumplidos($cumplidos)
    {
        if($this->validateNaturalNumber($cumplidos)) {
            $this->cumplidos = $cumplidos;
            return true;
        } else {
            return false;
        }
    }

    public function setNoCumplidos($noCumplidos)
    {
        if($this->validateNaturalNumber($noCumplidos)) {
            $this->nocumplidos = $noCumplidos;
            return true;
        } else {
            return false;
        }
    }
    
    public function setNoConsiderados($noConsiderados)
    {
        if($this->validateNaturalNumber($noConsiderados)) {
            $this->noconsiderados = $noConsiderados;
            return true;
        } else {
            return false;
        }
    }

    public function setIncumNoEntregados($incumNoEntregados) 
    {
        if($this->validateAlphaNumeric($incumNoEntregados, 1, 5)) {
            $this->incumnoentregados = $incumNoEntregados;
            return true;
        } else {
            return false;
        }
    }

    public function setIncumPorCalidad($incumPorCalidad) 
    {
        if($this->validateAlphaNumeric($incumPorCalidad, 1, 5)) {
            $this->incumporcalidad = $incumPorCalidad;
            return true;
        } 
        else {
            return false;
        }
    }

    public function setIncumPorFecha($incumPorFecha) 
    {
        if($this->validateAlphaNumeric($incumPorFecha, 1, 5)) {
            $this->incumporfecha = $incumPorFecha;
            return true;
        } else {
            return false;
        }
    }

    public function setIncumPorCantidad($incumPorCantidad) 
    {
        if($this->validateAlphaNumeric($incumPorCantidad, 1, 5)) {
            $this->incumporcantidad = $incumPorCantidad;
            return true;
        } else {
            return false;
        }
    }
    
    //Functiones para obtener los valores de los atributos
    public function getIdIndice() 
    {
        return $this->idindice;
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
    public function insertIndice() 
    {
        $query="INSERT INTO indiceentregas(responsable, cliente, organizacion, indice, totalcompromiso, cumplidos, nocumplidos, noconsiderados, incumnoentregados, incumporcalidad, incumporfecha, incumporcantidad) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $params = array($this->responsable, $this->cliente, $this->organizacion, $this->indice, $this->totalcompromiso, $this->cumplidos, $this->nocumplidos, $this->noconsiderados, $this->incumnoentregados, $this->incumporcalidad, $this->incumporfecha, $this->incumporcantidad);
        return Database::executeRow($query, $params);
    }

    //Función para mostrar los índices
    public function selectIndice() 
    {
        $query="SELECT ie.idindice, CONCAT(a.nombre, ' ', a.apellido) as Responsable, cl.usuario, ie.organizacion, ie.indice, ie.totalcompromiso, ie.cumplidos, ie.nocumplidos, ie.noconsiderados, ie.incumnoentregados, ie.incumporcalidad, ie.incumporfecha, ie.incumporcantidad, ie.estado
                FROM indiceentregas ie
                INNER JOIN administradores a
                    ON ie.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON ie.cliente = cl.codigocliente
                ORDER BY ie.estado DESC";
        $params = null;
        return Database::getRows($query, $params);
    }

    //Función para mostrar un registro
    public function readOneIndice() 
    {
        $query="SELECT ie.idindice, CONCAT(a.nombre, ' ', a.apellido) as Responsable, a.codigoadmin, cl.usuario, cl.codigocliente, ie.organizacion, ie.indice, ie.totalcompromiso, ie.cumplidos, ie.nocumplidos, ie.noconsiderados, ie.incumnoentregados, ie.incumporcalidad, ie.incumporfecha, ie.incumporcantidad, ie.estado
                FROM indiceentregas ie
                INNER JOIN administradores a
                    ON ie.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON ie.cliente = cl.codigocliente
                WHERE ie.idindice=?
                ORDER BY ie.estado DESC";
        $params = array($this->idindice);
        return Database::getRows($query, $params);
    }

    //Función para actualizar un registros
    public function updateIndice() 
    {
        $query="UPDATE indiceentregas
                SET responsable = ?, organizacion = ?, indice = ?, totalcompromiso = ?, cumplidos = ?, nocumplidos = ?, noconsiderados = ?, incumnoentregados = ?, incumporcalidad = ?, incumporfecha = ?, incumporcantidad = ? 
                WHERE idindice = ?";
        $params=array($this->responsable, $this->organizacion, $this->indice, $this->totalcompromiso, $this->cumplidos, $this->nocumplidos, $this->noconsiderados, $this->incumnoentregados, $this->incumporcalidad, $this->incumporfecha, $this->incumporcantidad, $this->idindice);
        return Database::executeRow($query, $params);
    }

    //Función para eliminar un registro
    public function desableIndice() 
    {
        $query="UPDATE indiceentregas SET estado=false WHERE idindice = ?";
        $params=array($this->idindice);
        return Database::executeRow($query, $params);
    }

    //Función para eliminar un registro
    public function deleteAll() 
    {
        $query="DELETE FROM indiceentregas";
        return Database::executeRow($query, null);
    }

    // Función para activar un registro
    public function enableIndice() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="UPDATE indiceentregas SET estado=true WHERE idindice = ?";
        $params=array($this->idindice);
        return Database::executeRow($query, $params);
    }

    // Función para realizar una búsqueda en los registros
    public function searchRows($value) 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT ie.idindice, CONCAT(a.nombre, ' ', a.apellido) as Responsable, cl.usuario, ie.organizacion, ie.indice, ie.totalcompromiso, ie.cumplidos, ie.nocumplidos, ie.noconsiderados, ie.incumnoentregados, ie.incumporcalidad, ie.incumporfecha, ie.incumporcantidad, ie.estado
                FROM indiceentregas ie
                INNER JOIN administradores a
                    ON ie.responsable = a.codigoadmin
                INNER JOIN clientes cl
                    ON ie.cliente = cl.codigocliente
                WHERE CONCAT(a.nombre, ' ', a.apellido) ILIKE ? OR cl.usuario ILIKE ? OR ie.organizacion ILIKE ?
                ORDER BY ie.estado DESC";
        $params = array("%$value%","%$value%","%$value%");
        return Database::getRows($query, $params);
    }

    // Función para buscar un registro en el sitio público
    public function searchIndicePublico($value)
    {
        $sql = "SELECT ie.idindice, CONCAT(a.nombre, ' ', a.apellido) as Responsable, cl.usuario, ie.organizacion, ie.indice, ie.totalcompromiso, ie.cumplidos, ie.nocumplidos, ie.noconsiderados, ie.incumnoentregados, ie.incumporcalidad, ie.incumporfecha, ie.incumporcantidad, ie.estado
        FROM indiceentregas ie
        INNER JOIN administradores a
            ON ie.responsable = a.codigoadmin
        INNER JOIN clientes cl
            ON ie.cliente = cl.codigocliente
        WHERE (ie.organizacion ILIKE ? OR CONCAT(a.nombre,' ',a.apellido) LIKE ?) AND ie.estado = true
		AND cl.codigocliente = ?				
        ORDER BY ie.estado DESC";
        $params = array("%$value%","%$value%", $_SESSION['codigocliente']);
        return Database::getRows($sql, $params);
    }

    // Función para mostrar todos los índices de un cliente
    public function readClienteIndices() 
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT ie.idindice, CONCAT(a.nombre, ' ', a.apellido) as Responsable, cl.usuario, ie.organizacion, ie.indice, ie.totalcompromiso, ie.cumplidos, ie.nocumplidos, ie.noconsiderados, ie.incumnoentregados, ie.incumporcalidad, ie.incumporfecha, ie.incumporcantidad, ie.estado
        FROM indiceentregas ie
        INNER JOIN administradores a
            ON ie.responsable = a.codigoadmin
        INNER JOIN clientes cl
            ON ie.cliente = cl.codigocliente
        WHERE ie.cliente = ? AND ie.estado = true
        ORDER BY ie.estado DESC";
        $params = array($this->cliente);
        return Database::getRows($query, $params);
    }

    // Función para obtener el porcentaje de cumplimiento de los compromisos de un índice
    public function porcentajeCumplimientoIndice($id_indice)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $query="SELECT ROUND(cumplidos * 100 / (SELECT totalcompromiso FROM indiceentregas WHERE idindice = 191),2) cumplidos,
        ROUND(nocumplidos * 250 / (SELECT totalcompromiso FROM indiceentregas WHERE idindice = 191),2) nocumplidos,
        ROUND(noconsiderados * 250 / (SELECT totalcompromiso FROM indiceentregas WHERE idindice = 191),2) noconsiderados
        FROM indiceentregas
        WHERE idindice = 191";
        $params=array($id_indice);
        return Database::getRow($query, null);
    }
}
?>