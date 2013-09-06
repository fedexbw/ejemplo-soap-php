<?php

/* Este archivo corresponde la implementación de un cliente de servicios web 
 */

//en el caso de que se envie el formulario se creara la variable $id para la consulta al web service
if (isset($_GET['input'])){
    $id = $_GET['input'];
}

// se crea un pequeño formulario donde se solicictara la id para la consulta de usuario, se redirige al mismo archivo es por ello las sentencias de la linea 5
print "<h2>Web service para la consulta de usuarios</h2>";
print "<form action='cliente.php' method='GET'/>";
print "<input name='input'/><br/>";
print "<input type='Submit' name='submit' value='GO'/>";
print "</form>";

// en el caso de que se envio el formulario
if(isset($id)){
    //se hace la validación correspondiente de que se envie un valor en formato numerico
    if(($id != '')&&(is_numeric($id))){
        $id = (int)$id;
        // se instancia un cliente SOAP
        $client = new SoapClient(null, array(
          'location' => "http://localhost/ejemplo-soap-php/servidor.php",
          'uri'      => "urn:pc_SOAP",                     // se hace referencia a la clase que se solicita instanciar
           'login'=>'fede',                                     // este es el token del nombre de usuario para el acceso al web service
          'password'=>'123456'));                       // este es el token del password de usuario para el acceso al web service
        try{
            $result=$client->getUser($id);
        }  catch (SoapFault $exp){
            echo 'Exception='.$exp;
        }
        // en el caso de que no se haya producido un error con el acceso al web service se mostrara el resultado
        if(isset($result)){
          printf($result);  
        }
    }else{
        echo 'Error, ingreso un valor que no tiene formato numerico';           // en el caso de que se haya enviado un valor incorrecto al formulario
    }
}
?>
