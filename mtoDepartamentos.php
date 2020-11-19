<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mantenimiento Departamentos Raúl</title>
        <link rel="stylesheet" href="webroot/css/estilo.css">
    </head>
    <body>
        <main>
        <header>
            <div>
            <h1>Mto. de departamentos Raúl</h1>
                <nav>
                    <a href="codigoPHP/exportarDepartamentos.php">EXPORTAR XML</a>
                    <a href="codigoPHP/importar.php">IMPORTAR TABLA</a>
                    <a href="codigoPHP/añadirDepartamentos.php">AÑADIR DEPARTAMENTO</a>
                </nav>
            </div>
        </header>
        <?php
            require_once 'core/201008libreriaValidacion.php';
            require_once 'config/confDBPDO.php';
            
            define("OBLIGATORIO", 1);
            define("OPCIONAL", 0);
            
            $entradaOk = true;
            $errores = null;
            
        ?>
        <form name="formularioBuscar" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <div>
                    <label style="color:white;" for="DescDepartamento">Busca la descripción de un departamento:</label>
                    <input type="text" name="DescDepartamento" value="<?php 
                                echo(isset($_REQUEST['DescDepartamento']) ? $_REQUEST['DescDepartamento'] : null);
                                ?>">
                            <?php
                                echo($errores!=null ? $errores : null);
                            ?>
                     <input type="submit" value="BUSCAR" name="buscar" class="enviar">
                </div>
            </form>
        <?php
            if(isset ($_REQUEST['buscar'])){
                
                 $aErrores=validacionFormularios::comprobarAlfaNumerico($_REQUEST ["DescDepartamento"],255,1,OPCIONAL);
                 
                 if($aErrores !=null){
                     $entradaOk=false;
                     $_REQUEST['DescDepartamento']="";
                 }
            }else{
                $_REQUEST['DescDepartamento']="";
            }
            if($entradaOk){
             
                try{
                    $miDB = new PDO(DNS, USER, PASSWORD); 
                    $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

                    $consulta = $miDB->prepare("select * from Departamento where DescDepartamento like '%{$_REQUEST['DescDepartamento']}%'");
                    
                    $consulta->execute(); 
                    
                    if($consulta->rowCount()>=0){
               
                    ?>      
                            <table>
                                <tr>
                                    <th>CÓDIGO</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>FECHA DE BAJA</th>
                                    <th>VOLUMEN DE NEGOCIO</th>
                                </tr>
                        <?php
                            $oRegistro=$consulta->fetchObject();
                            while($oRegistro){
                        ?>
                                <tr>
                                    <td><?php echo $oRegistro->CodDepartamento;?></td>
                                    <td><?php echo $oRegistro->DescDepartamento;?></td>
                                    <td><?php echo $oRegistro->FechaBaja;?></td>
                                    <td><?php echo $oRegistro->VolumenNegocio;?></td>
                                    <td>
                                        <button style="background-color:#a3e4d7;" name="editar"><a href="<?php echo 'codigoPHP/editarDepartamento.php?codigo='.$oRegistro->CodDepartamento?>"><img src="webroot/media/editar.png" width="30px" height="30px"></a></button>
                                        <button style="background-color:#f7dc6f;" name="consultar"><a href="<?php echo 'codigoPHP/consultarDepartamento.php?codigo='.$oRegistro->CodDepartamento?>"><img src="webroot/media/consultar.png" width="30px" height="30px"></button>
                                        <button style="background-color:#e74c3c;" name="borrar"><a href="<?php echo 'codigoPHP/borrarDepartamento.php?codigo='.$oRegistro->CodDepartamento?>"><img src="webroot/media/borrar.png" width="30px" height="30px"></a></button>
                                        <button style="background-color:transparent" name="alta"><a href="<?php echo 'codigoPHP/rehabilitarDepartamento.php?codigo='.$oRegistro->CodDepartamento?>"><img src="webroot/media/alta.png" width="30px" height="30px"></button>
                                        <button style="background-color:transparent" name="baja"><a href="<?php echo 'codigoPHP/bajaLogicaDepartamento.php?codigo='.$oRegistro->CodDepartamento?>"><img src="webroot/media/baja.png" width="30px" height="30px"></button>
                                    </td> 
                                </tr>
                        <?php
                            $oRegistro=$consulta->fetchObject();
                            } 
                        ?>
                            </table>
                
                        <?php
                }
                }catch (PDOException $miExcepcionPDO){
                    echo "Error ".$miExcepcionPDO->getMessage();
                    echo "Código de error ".$miExcepcionPDO->getCode();
                }finally {
                   unset($miDB);
                }
            }
                ?>
            <button class="mostrarCodigo" type="submit" name="enviar"><a href="mostrarCodigo/muestraCodigo.php">MOSTRAR CÓDIGO</a></button>
            <button class="volverIndex" type="submit" name="volver"><a href="../proyectoDWES/indexProyectoDWES.php">VOLVER DWES</a></button>
    </body>
    </main>
        <footer>
            <address>Raúl Núñez Sebastián &copy; 2020/2021</address>
	</footer>
</html>
