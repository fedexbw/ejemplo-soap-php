<?php

/* Este archivo corresponde la implementación de un servidor de servicios web */

// Esta funcion corresponde a la logica de autenticacion de usuarios
function autenticacion_usuario($usuario, $contrasenia) {
    // este es un ejemplo basico de autenticación, podria conectarse a una base de datos y validar la autenticacion de usuarios inclusive con encriptacion de contraseña
    if (($usuario=='usuarioPrueba')&&($contrasenia=='123456')) {
        return true;
    } else {
        return false;
    }
}

//clase que contendra los servicios que podran ser accedidos desde el servidor
class pc_SOAP{
    
    //se define el constructor de la clase de tal manera que al instanciar un objeto de la misma se realice la autenticación de los usuarios del web service
    public function __construct(){
        if (!autenticacion_usuario($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
            throw new SoapFault('Usuario y/o contrasña incorrecta',401); // se genera una expeción en el caso de que no se cumpla con el login de usuario, se podria mejorar aqui el mensaje para este tipo de error
        }
    }
    
    //metodo para establecer la conexion a la base de datos
    private function getConnection(){
        $dbhost='localhost';
        $dbuser='tuUsuario';
        $dbpass='tuPassword';
        $db='pruebaWebService';
        $conect = new PDO("mysql:dbname=$db;host=$dbhost",$dbuser,$dbpass);
        $conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conect;
    }
    
    //metodo que sera accedido por los usuarios del web service y devuelve el usuario de acuerdo al id que se le pase como parametro
    public function getUser($id){
        $sql = "SELECT * FROM users WHERE user_id=:id";
    try {
            $db= $this->getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $usuario = $stmt->fetchObject();
            $db=null;
            return json_encode($usuario);
    }  catch (PDOException $e){       // mensaje de excepcion en caso de error en la conexion a la base de datos
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
}


// Se crea el servidor Soap

$server = new SoapServer(
        null, // no se especifica WSDL
        array('uri' => 'urn:ejemplo-soap-php') // se debe especificar el URI
        );
$server->setClass('pc_SOAP');
// metodo para la atencion de los llamados al servidor
$server->handle();

?>
