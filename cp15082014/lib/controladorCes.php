<?php
/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SIACEDA
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 27/09/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: JS
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/
	
	session_start();
	set_time_limit(-1);
	ini_set('memory_limit','128M');
	include ("../funciones.php");
	
	
	

    include_once ("../../clases/MySQL.php");
	
	include_once("../../comunes/objConexion.php");
	
    
	/*function __autoload ($nombreClase)
	{
		$archivo = '../clases/'.$nombreClase.'.php';
		
		if (file_exists ($archivo))
		{
			  include $archivo;
		}
	}*/


     
	foreach($_POST as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}



	switch($caso)
	{
            //CASOS 
            case 'buscarDocumentoInternoAviso':
				
				$sql = "select A.Cod_Documento, A.Cod_DocumentoCompleto, A.Asunto, C.Descripcion
							from cp_documentointerno as A 
							join cp_documentodistribucion as B on B.Cod_Documento = A.Cod_DocumentoCompleto
							join cp_tipocorrespondencia as C on C.Cod_TipoDocumento 	=A.Cod_TipoDocumento
							where A.Estado= 'EV' and A.Cod_DocumentoCompleto not in (select Cod_Documento from cp_documentoacuserecibo)
							and B.CodPersona='".$_SESSION["CODPERSONA_ACTUAL"]."' and B.Procedencia='INT'";
				
				$resp = $objConexion->consultar($sql,'matriz');
				
				
				
				if (count($resp) == 0)
				{
						echo '0';
						
				} else {
					
					$contenidoCapaVentana = '<table width="100%" height="auto">
														<tbody><tr class="trListaHead">
															<th scope="col" width="100">Nro. Documento</th>
															<th scope="col">Tipo Documento</th>
															<th scope="col" width="75">Asunto</th>
															
															<!-- <th scope="col" width="90">Stock M&iacute;nimo</th>
													   		<th scope="col" width="90">Stock M&aacute;ximo</th> -->
														</tr>';
																	
					for ($i = 0; $i < count($resp); $i++)
					{
						
						
						$contenidoCapaVentana.='<tr class="trListaBody" style="cursor:default"><td align="center">'.$resp[$i]['Cod_DocumentoCompleto'].'</td><td align="center">'
															.$resp[$i]['Descripcion'].'</td><td align="center">'
															.$resp[$i]['Asunto'].'</td></tr>';
														
															/*.$resp[$i]['Descripcion'].'</td><td align="right">'
															.$resp[$i]['StockMax'].'</td></tr>';*/
						
						
					}
					
					$contenidoCapaVentana.='</tbody></table>';
												
					echo utf8_encode($contenidoCapaVentana);
				}
            break;
			
            case 'buscarDocumentoExternoAviso':
             
				$sql = "SELECT A.NumeroRegistroInt, A.Asunto, C.Descripcion
							FROM cp_documentoextentrada AS A
							JOIN cp_tipocorrespondencia AS C ON C.Cod_TipoDocumento = A.Cod_TipoDocumento
							WHERE A.Estado = 'PE'";
				
				//-- and A.Cod_DocumentoCompleto not in (select Cod_Documento from cp_documentoacuserecibo)
				
				$resp = $objConexion->consultar($sql,'matriz');
				
            	if (count($resp) == 0)
				{
						echo '0';
						
				} else {
					
					$contenidoCapaVentana = '<table width="100%" height="auto">
														<tbody><tr class="trListaHead">
															<th scope="col" width="100">Nro.Registro Interno</th>
															<th scope="col">Tipo Documento</th>
															<th scope="col" width="75">Asunto</th>
															
															<!-- <th scope="col" width="90">Stock M&iacute;nimo</th>
													   		<th scope="col" width="90">Stock M&aacute;ximo</th> -->
														</tr>';
																	
					for ($i = 0; $i < count($resp); $i++)
					{
						
						
						$contenidoCapaVentana.='<tr class="trListaBody" style="cursor:default"><td align="center">'.$resp[$i]['NumeroRegistroInt'].'</td><td align="center">'
															.$resp[$i]['Descripcion'].'</td><td align="center">'
															.$resp[$i]['Asunto'].'</td></tr>';
														
															
						
						
					}
					
					$contenidoCapaVentana.='</tbody></table>';
												
					echo utf8_encode($contenidoCapaVentana);
				}
            break;
            ////FIN CASOS
            
            default://para pruebas
                 
      }
?>