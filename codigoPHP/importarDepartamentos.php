 <?php
 if(isset($_REQUEST['cancelar'])){
    header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
}
if(isset($_REQUEST['aceptar'])){
    header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
}
require_once '../config/confDBPDO.php';
$entradaOK = true;

if ($_FILES['archivo'] != null) { 
    if ($_FILES['archivo']['type'] == 'text/xml') {
        try { 
            $miDB = new PDO(DNS, USER, PASSWORD);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $file_name = $_FILES['archivo']['tmp_name'];
  
            move_uploaded_file($file_name, '../tmp/exportar.xml');
            
            $documentoXML = new DOMDocument("1.0", "utf-8");
            $documentoXML->load('../tmp/exportar.xml');
            
            $sqlTruncate="truncate table Departamento";
            $consultaTruncate = $miDB->prepare($sqlTruncate);
            $consultaTruncate->execute();

            $sql = 'INSERT INTO Departamento VALUES (:CodDepartamento, :DescDepartamento, :FechaBaja, :VolumenNegocio)';

            $consulta = $miDB->prepare($sql);

            $nDepartamentos = $documentoXML->getElementsByTagName('Departamento')->length;

            for ($nDepartamento = 0; $nDepartamento < $nDepartamentos; $nDepartamento++) {
               
                $departamento = $documentoXML->getElementsByTagName('Departamento')->item($nDepartamento)->childNodes;

                $parametros = [":CodDepartamento" => $departamento->item(1)->nodeValue,
                    ":DescDepartamento" => $departamento->item(3)->nodeValue,
                    ":FechaBaja" => $departamento->item(5)->nodeValue,
                    ":VolumenNegocio" => $departamento->item(7)->nodeValue];
                
                if (empty($parametros[':FechaBaja'])) {
                    $parametros[':FechaBaja'] = null;
                }
                $consulta->execute($parametros);
            }
            echo "<p style='color:green;'>Archivo realizado correctamente</p>";

            $sql2 = 'SELECT * FROM Departamento';
            $resultadoConsulta = $miDB->query($sql2);
            
            header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
            
        } catch (PDOException $miExceptionPDO) { 
            echo "<p style='color:red;'>Código de error: " . $miExceptionPDO->getCode() . "</p>";
            echo "<p style='color:red;'>Error: " . $miExceptionPDO->getMessage() . "</p>";
            die();
        } finally {
            unset($miDB);
        }
    }
}else{
    ?>
<!DOCTYPE html>
<html style="background-color:#ffcba4">
    <head>
        <meta charset="UTF-8">
        <title>IMPORTAR</title>
    </head>
    <body>
        <form style="padding-top: 15%; padding-left: 35%;" name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" name="archivo" type="file">
            <br>
            <br>
            <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="submit" value="ACEPTAR" name="aceptar">
            <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="submit" value="CANCELAR" name="cancelar">
        </form>
    </body>
</html>
    <?php
 }
?>