<?php
//Es una clase hija de Validator
class Historial extends Validator
{

    // Funcion para obtener los datos de la tabla historial
    public function readAll()
    {
        // Declaramos sentencia SQL 
        $sql = 'SELECT idregistro, c.usuario,hora ,accion,empresa,dispositivo,sistema
        from historialcliente h
        INNER JOIN clientes c ON c.codigoCliente = h.usuario 
        order by hora DESC';
        // No requiere parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para ingresar registros dentro de la tabla historial
    public function insertHistorial($accion)
    {
        // Obtenemos el id del cliente que tiene activa la sesion 
        $cliente = $_SESSION['codigocliente'];
        // Declaramos la consulta con sus respectivos parametros
        $query = "INSERT INTO historialCliente values (default, ? ,? , default,?,?)";
        // Obtenemos la informacion del dispositivo
        $sistema = php_uname();
        // Obtenemos la informacion del sistema operativo
        $dispositivo = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        // Enviamos los parametros para que se ejecuten en la base de datos
        $params = array($cliente, $accion, $dispositivo, $sistema);
        return Database::executeRow($query, $params);
    }

    // Función para buscar un registro
    public function searchHistorial($value)
    {
        $sql = "SELECT idregistro, c.usuario,hora ,accion,empresa,dispositivo,sistema
        from historialcliente h
        INNER JOIN clientes c ON c.codigoCliente = h.usuario
		WHERE c.usuario LIKE ? OR sistema LIKE ? OR empresa LIKE ? OR accion LIKE ?    
        order by hora DESC ";
        $params = array("%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
}
