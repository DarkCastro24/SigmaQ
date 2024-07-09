<?php
//Es una clase hija de Validator
class sociedades extends Validator
{

    // Declaración de atributos
    private $idsociedad = null;
    private $cliente = null;
    private $sociedad = null;

    // Metodos para asignar valores a los atributos
    public function setIdSociedad($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->idsociedad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCliente($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setSociedad($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->sociedad = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones para obtener los valores de los atributos

    public function getIdSociedad()
    {
        return $this->idsociedad;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function getSociedad()
    {
        return $this->sociedad;
    }

    // Métodos para realizar las operaciones SCRUD

    // Metodo para llenar la tabla método (Select)
    public function SelectSociedades()
    {
        $sql = "SELECT s.idsociedad, s.sociedad, c.usuario  
                FROM sociedades s
                INNER JOIN clientes c
                ON s.cliente = c.codigocliente";
        $params = null;
        print($params);
        return Database::getRows($sql, $params);
    }

    // Metodo para obtener los datos de un registro (Read one)
    public function SelectOneSociedades()
    {
        $sql = 'SELECT idsociedad, cliente, sociedad
                FROM sociedades
                WHERE idsociedad = ?';
        $params = array($this->idsociedad);
        print($params);
        return Database::getRows($sql, $params);
    }

    // Metodo para insertar datos en la base (Insert)
    public function insertSociedad() 
    {
        $query="INSERT INTO sociedades (cliente, sociedad)
        VALUES (?, ?)";
        $params = array($this->cliente, $this->sociedad);
        return Database::executeRow($query, $params);
    }

    // Metodo para actualizar datos en la base (Update)
    public function updateSociedad() 
    {
        $query="UPDATE sociedades
                SET cliente = ?, sociedad = ?
                WHERE idsociedad = ?";
        $params = array($this->cliente, $this->sociedad, $this->idsociedad);
        return Database::executeRow($query, $params);
    }

    // Metodo para eliminar datos en la base (Delete)
    public function deleteSociedad() 
    {
        $query = "DELETE FROM sociedades WHERE idsociedad = ?";
        $params = array($this->idsociedad);
        return Database::executeRow($query, $params);
    }
}