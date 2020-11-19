<?php
require_once '../config/confDBPDO.php';
try {
    $miDB = new PDO(DNS, USER, PASSWORD); // creo un objeto PDO con la conexion a la base de datos
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establezco el atributo para la apariciopn de errores y le pongo el modo para que cuando haya un error se lance una excepcion
    
    $sql = "SELECT * FROM Departamento";
    $resultadoconsulta = $miDB->prepare($sql); //Preparo la consulta
    $resultadoconsulta->execute(); //Ejecuto la consulta
    
    $documentoXML = new DOMDocument("1.0", "utf-8"); //Creo el objeto de tipo DOMDocument que recibe 2 parametros: ela version y la codificacion del XML que queremos crear
    $documentoXML->formatOutput = true; //Establece la salida formateada
    
    $nodoRaiz = $documentoXML->appendChild($documentoXML->createElement('Departamentos')); //Creo el nodo raiz
    $oDepartamento = $resultadoconsulta->fetchObject(); //Obtengo el primer registro de la consulta como un objeto
    
    while ($oDepartamento) { //Recorro los registros que devuelve la consulta y por cada uno de ellos ejecuto el codigo siguiente
        $departamento = $nodoRaiz->appendChild($documentoXML->createElement('Departamento')); //Creo el nodo para el departamento
        $departamento->appendChild($documentoXML->createElement('CodDepartamento', $oDepartamento->CodDepartamento)); //Añado como hijo el codigo de departamento con su valor
        $departamento->appendChild($documentoXML->createElement('DescDepartamento', $oDepartamento->DescDepartamento)); //Añado como hijo la descripcion del departamento con su valor
        $departamento->appendChild($documentoXML->createElement('FechaBaja', $oDepartamento->FechaBaja)); //Añado como hijo la fecha de baja del departamento con su valor
        $departamento->appendChild($documentoXML->createElement('VolumenNegocio', $oDepartamento->VolumenNegocio)); //Añado como hijo el volumen de negocio del departamento con su valor
        $oDepartamento = $resultadoconsulta->fetchObject(); //Guardo el registro actual como un objeto y avanzo el puntero al siguiente registro de la consulta
    }
    $documentoXML->save("../tmp/tablaDepartamentos.xml"); //Guarda el arbol XML en la ruta especificada con la fecha del dia que se ejecuta.
    
    header('Content-Type:text/xml');
    header("Content-Disposition: attachment;filename=tablaDepartamentos.xml");
    readfile("../tmp/tablaDepartamentos.xml"); //Mostrar desde el fichero del servidor en el navegador el documento xml si este no se descarga.
    
} catch (PDOException $miExceptionPDO) { //Codigo que se ejecuta si hay alguna excepcion.
    echo "<p style='color:red;'>Código de error: " . $miExceptionPDO->getCode() . "</p>"; //Muestra el codigo del error.
    echo "<p style='color:red;'>Error: " . $miExceptionPDO->getMessage() . "</p>"; //Muestra el mensaje de error.
} finally {
    unset($miDB); //Cierro las sesión a la bases de datos.
}
?>