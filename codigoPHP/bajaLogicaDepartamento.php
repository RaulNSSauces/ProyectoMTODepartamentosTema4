<?php
if(isset($_REQUEST['cancelar'])){
    header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
}
            require_once '../core/201008libreriaValidacion.php';
            require_once '../config/confDBPDO.php';
            
            define("OBLIGATORIO", 1);
            define("OPCIONAL", 0);

            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

                $campos = "SELECT * FROM Departamento where CodDepartamento=:CodDepartamento";
                    
                $consulta=$miDB->prepare($campos);
                $parametro =[":CodDepartamento" => $_REQUEST['codigo']];
                
                $consulta->execute($parametro);
                $resultado=$consulta->fetchObject();
                
                $descDepartamento=$resultado->DescDepartamento;

                $fecha=new DateTime();

                $vNegocio=$resultado->VolumenNegocio;

                }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                    echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                    echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
                }finally{
                    unset($miDB);
                }
                
                $entradaOK = true;
                
                $aErrores=["FechaBaja" => null];
                
            if(isset($_REQUEST['rehabilitar'])){
                $fechaMaxima = new DateTime(2500-01-01);
                $aErrores['FechaBaja']= validacionFormularios::validarFecha($_REQUEST['FechaBaja'], $fechaMaxima->format('Y-m-d'), $fecha->format('Y-m-d'), OBLIGATORIO);
                
                
                foreach($aErrores as $valor => $validar){
                    if($validar!=null){
                        $entradaOK=false;
                    }
                }
            }else{
                $entradaOK=false;
                
            }
            if($entradaOK){
            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.
                
                $consutaAcualizacion = "UPDATE Departamento set FechaBaja=:FechaBaja where CodDepartamento=:CodDepartamento";
                
                $actualizacion = $miDB->prepare($consutaAcualizacion);
                $parametro =[":CodDepartamento" => $_REQUEST['codigo'],
                             ":FechaBaja" => $_REQUEST['FechaBaja']];
                
                $actualizacion->execute($parametro);
                
                header('Location: http://daw203.ieslossauces.es/MtoDepartamentosTema4/mtoDepartamentos.php');
            }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                    echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                    echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
                }finally{
                    unset($miDB);
                }
            }else{
        ?>
<!DOCTYPE html>
<html style="background-color:#ffcba4">
    <head>
        <meta charset="UTF-8">
        <title>Consultar Departamento</title>
    </head>
    <body>
            <form style="padding-top: 15%; padding-left: 35%;" name="formulario" action="<?php echo $_SERVER['PHP_SELF'].'?codigo='.$_REQUEST['codigo'];//Muestro la información del formulario en la misma página que se está ejecutando en el fichero actual.?>" method="post">
                <fieldset style="width: 35%; background-color: #aab7b8">
                    <div>
                        <b><label for="CodDepartamento">Código del departamento: </label></b>
                        <input style="width: 10%;" type="text" name="CodDepartamento" value="<?php echo $_REQUEST['codigo']?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="DescDepartamento">Descripción: </label></b>
                        <input style="width: 200px;" type="text" name="DescDepartamento" value="<?php echo $descDepartamento?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="FechaBaja">Fecha: </label></b>
                        <input style="width: 200px; background-color:#67d59c" type="date" name="FechaBaja" value="<?php echo $fecha->format('Y-m-d');?>">
                        <span style="color:red">
                            <?php
                                if ($aErrores["FechaBaja"] != null) { //Compruebo que el array de errores no está vacío.
                                    echo $aErrores["FechaBaja"]; //Si hay errores, devuelve el campo vacío y muestra una advertencia de los errores y como tiene que estar escrito ese campo.
                                }
                            ?>
                        </span>
                    </div>
                <br>
                    <div>
                        <b><label for="VolumenNegocio">Volumen de negocio: </label></b>
                        <input style="width: 10%;" type="text" name="VolumenNegocio" value="<?php echo $vNegocio?>"readonly>
                    </div>
                <br>
                    <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="submit" value="ACEPTAR" name="rehabilitar">
                    <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="submit" value="CANCELAR" name="cancelar">
                </fieldset>     
        </form>
    </body>
</html>
<?php
            }
?>