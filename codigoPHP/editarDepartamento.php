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
            
            define ('OBLIGATORIO',1); //Creo una constante $OBLIGATORIO y le asigno un 1.
            define ('MAX_FLOAT', 3.402823466E+38); //Creo una constante del máximo permitido en un campo float.
            define ('MIN_FLOAT', -3.402823466E+38); //Creo una constante del mínimo permitido en un campo float.
            
            $entradaOk = true; //Creo una variable booleana y la inicializo a true.
            
            $aErrores = ["DescDepartamento" => null,
                         "VolumenNegocio" => null];
            
            $aRespuesta = ["DescDepartamento" => null, //Creo un array de comprobación y lo inicializo a null con los campos de la tabla Departamentos.
                           "VolumenNegocio" => null];
            
            if(isset ($_REQUEST['aceptar'])){ //Compruebo que el usuario le ha dado al botón enviar.
                
                $aErrores["DescDepartamento"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST ["DescDepartamento"],255,1, OBLIGATORIO); //Compruebo que el campo DescDepartamento que introduce el usuario es válido.
                $aErrores["VolumenNegocio"] = validacionFormularios::comprobarFloat($_REQUEST["VolumenNegocio"], MAX_FLOAT, MIN_FLOAT, OBLIGATORIO); //Compruebo que el campo VolumenNegocio que introduce el usuario es válido.
                
                foreach($aErrores as $valor => $error){
                    if($error != null){
                       $entradaOk=false;
                       $_REQUEST[$valor]="";
                    }
                }
            }else{
                $entradaOk=false;
            }
            if($entradaOk){
                try{
                    $miDB = new PDO(DNS,USER,PASSWORD); //Creo una conexión a la base de datos instanciando un objeto de la clase PDO.
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.
                    //Creo una variable heredoc e introduzco el query que voy a realizar.
                    $sql = <<<EOD
                          UPDATE Departamento SET 
                          DescDepartamento=:DescDepartamento,
                          VolumenNegocio=:VolumenNegocio 
                          WHERE CodDepartamento=:CodDepartamento;  
EOD;
                    $consulta = $miDB->prepare($sql);
                    
                    $parametros =[":DescDepartamento" => $_REQUEST["DescDepartamento"],
                                  ":VolumenNegocio" => $_REQUEST["VolumenNegocio"],
                                  ":CodDepartamento" => $_REQUEST["CodDepartamento"]];
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
        <title>Editar Departamento</title>
    </head>
    <body>
            <form style="padding-top: 15%; padding-left: 35%;" name="formulario" action="<?php echo $_SERVER['PHP_SELF'].'?codigo='.$_REQUEST['codigo'];//Muestro la información del formulario en la misma página que se está ejecutando en el fichero actual.?>" method="post">
            <fieldset style="width: 35%; background-color: #aab7b8">
                    <div>
                        <b><label for="CodDepartamento">Código del departamento: </label></b>
                        <input style="width: 10%; background-color:#eb8065" style="background-color:#eb8065" type="text" name="CodDepartamento" value="<?php echo $_REQUEST['codigo']?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="DescDepartamento">Descripción: </label></b>
                        <input style="width: 200px; background-color:#67d59c" type="text" name="DescDepartamento" value="<?php 
                                if(isset($_REQUEST["DescDepartamento"]) && $aErrores["DescDepartamento"] == null){ //Compruebo  que los campos del array de errores están vacíos y el usuario le ha dado al botón de enviar.
                                    echo $_REQUEST["DescDepartamento"]; //Devuelve el campo que ha escrito previamente el usuario.
                                }else{
                                   echo $descDepartamento;
                                }
                                ?>">
                        <span style="color:red">
                            <?php
                                if ($aErrores["DescDepartamento"] != null) { //Compruebo que el array de errores no está vacío.
                                    echo $aErrores["DescDepartamento"]; //Si hay errores, devuelve el campo vacío y muestra una advertencia de los errores y como tiene que estar escrito ese campo.
                                }
                            ?>
                        </span>
                    </div>
                <br>
                    <div>
                        <b><label for="FechaBaja">Fecha: </label></b>
                        <input style="background-color:#eb8065" type="text" name="FechaBaja" value="<?php echo $fecha?>"readonly>
                    </div>
                <br>
                    <div>
                        <b><label for="VolumenNegocio">Volumen de negocio: </label></b>
                        <input style="width: 10%; background-color:#67d59c" type="text" name="VolumenNegocio" value="<?php 
                                if($aErrores["VolumenNegocio"] == null && isset($_REQUEST["VolumenNegocio"])){ //Compruebo  que los campos del array de errores están vacíos y el usuario le ha dado al botón de enviar.
                                    echo $_REQUEST["VolumenNegocio"]; //Devuelve el campo que ha escrito previamente el usuario.
                                }else{
                                    echo $vNegocio;
                                }
                                ?>">
                        <span style="color:red">
                            <?php
                                if ($aErrores["VolumenNegocio"] != null) { //Compruebo que el array de errores no está vacío.
                                    echo $aErrores["VolumenNegocio"]; //Si hay errores, devuelve el campo vacío y muestra una advertencia de los errores y como tiene que estar escrito ese campo.
                                }
                            ?>
                        </span>
                    </div>
                <br>
                    <button style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" type="submit" name="aceptar">ACEPTAR</button>
                    <input style="background-color: #0b96df; color:white; font-weight: bold; padding: 5px;" class="volver" type="button" onclick="history.back()" name="volver" value="CANCELAR">
            </fieldset>
        </form>
        <?php
            }
        ?>
    </body>
</html>