<?php
if(isset($_REQUEST['aceptar'])){
    header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
}
            require_once '../core/201008libreriaValidacion.php';
            require_once '../config/confDBPDO.php';
            
            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

                $campos = "SELECT DescDepartamento, FechaBaja, VolumenNegocio FROM Departamento where CodDepartamento=:CodDepartamento";
                    
                $consulta=$miDB->prepare($campos);
                $parametro =[":CodDepartamento" => $_REQUEST['codigo']];
                
                $consulta->execute($parametro);
                $resultado=$consulta->fetchObject();
                
                $descDepartamento=$resultado->DescDepartamento;
                $fecha=$resultado->FechaBaja;
                if($fecha==null){
                    $fecha="null";
                }
                $vNegocio=$resultado->VolumenNegocio;
                
                }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                    echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                    echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
                }finally{
                    unset($miDB);
                }
            if(isset($_REQUEST['aceptar'])){
                try{
                    $miDB = new PDO(DNS,USER,PASSWORD); //Creo una conexión a la base de datos instanciando un objeto de la clase PDO.
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.
                    //Creo una variable heredoc e introduzco el query que voy a realizar.
                    $sql = "DELETE from Departamento where CodDepartamento=:CodDepartamento";
                    $consulta = $miDB->prepare($sql);
                    
                    $parametros =[":CodDepartamento" => $_REQUEST['codigo']];
                    $consulta->execute($parametros);
                    
                    header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
                    
                }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                    echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                    echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
                } finally {
                    unset($miDB); //Cierro la conexión a la base de datos.
                }
            }else{  
        ?>
<!DOCTYPE html>
<html style="background-color:#ffcba4">
    <head>
        <meta charset="UTF-8">
        <title>Borrar Departamento</title>
    </head>
    <body>
            <form style="padding-top: 15%; padding-left: 35%;" name="formulario" action="<?php echo $_SERVER['PHP_SELF'].'?codigo='.$_REQUEST['codigo'];//Muestro la información del formulario en la misma página que se está ejecutando en el fichero actual.?>" method="post">
                <fieldset style="width: 35%; background-color: #aab7b8">
                    <div>
                        <b><label for="CodDepartamento">Código del departamento: </label></b>
                        <input style="background-color:#eb8065" style="width: 10%;" type="text" name="CodDepartamento" value="<?php echo $_REQUEST['codigo']?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="DescDepartamento">Descripción: </label></b>
                        <input style="background-color:#eb8065" style="width: 200px;" type="text" name="DescDepartamento" value="<?php echo $descDepartamento?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="FechaBaja">Fecha: </label></b>
                        <input style="background-color:#eb8065" type="text" name="FechaBaja" value="<?php echo $fecha?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="VolumenNegocio">Volumen de negocio: </label></b>
                        <input style="background-color:#eb8065" style="width: 10%;" type="text" name="VolumenNegocio" value="<?php echo $vNegocio?>"readonly>
                    </div>
                <br>
                    <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="submit" value="ACEPTAR" name="aceptar">
                    <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="button" onclick="history.back()" name="volver" value="CANCELAR">
                </fieldset>
        </form>
        <?php
            }
        ?>
    </body>
</html>