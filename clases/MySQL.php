<?php

/****************************************************************************************
* LOCACIÓN::VENEZUELA-SUCRE-CUMANÁ
* PROGRAMADOR: Christian Hernández
* 
* DESCRIPCION: Conexión y manipulación de datos para SGBD MySQL
* VERSION: 0.1 Beta
*********************************************************************************************/  

     
	class MySQL
	{
      
		private $recursoConexion = false;
		private $usuario;
		private $clave;
		private $baseDatos;
		private $host;
		private $puerto;
		private $resultadoQuery;
		private $filaResultado;
            
		function __construct($host,$usuario,$clave,$baseDatos,$puerto)
		{
			$this->host = $host;
			$this->usuario =$usuario;
			$this->clave = $clave;
			$this->baseDatos = $baseDatos;
			$this->puerto = $puerto;
			
			$this->recursoConexion = new mysqli($this->host,$this->usuario,$this->clave,$this->baseDatos);
				
			/* Chequeando la conexion */
			if (mysqli_connect_errno()) 
			{
				printf("Conexion Fallida: %s\n", mysqli_connect_error());
				exit();
			}
		
			if($this->recursoConexion)
			{
				return true;
				
			} else {
			
				return false;
			}
		
		
		}
            
		function ejecutarQuery($sql)
		{
			$this->recursoConexion->query("SET NAMES 'utf8'");
			
			$this->resultadoQuery = $this->recursoConexion->query($sql);

			if($this->resultadoQuery != false)
			{
				return true;
				
			} else {
			
				return false;
			}
		}
            
		function getCantidadFilasConsulta()//Obtine el numero de filas afectadas por un select
		{
			$w = $this->resultadoQuery->num_rows;
			return $w;
		}
            
		function getCantidadFilasCRUD()//Obtiene el numero de filas afectadas por un INSERT, UPDATE � DELETE
		{
			$t = $this->resultadoQuery->affected_rows;
			return $t;
		}
            
		function getObjetoConsulta()//Obtiene una fila afectada como un objeto, donde el nombre del campo es una propiedad
		{
			/*$objeto = $this->resultadoQuery->fetch_object();
			return $objeto;*/
		
		}

		function getFilaConsulta()//Obtiene uan fila de un select asociativa por nombre de campo
		{
			$this->filaResultado = $this->resultadoQuery->fetch_assoc();
			return $this->filaResultado;
		}
		
		function getMatrizCompleta()//Devuelve una matriz completa de un select (consulta), los campos son con numeros de posiciones ([x][y])
		{
                  if ($this->getCantidadFilasConsulta() == 1)
                  {
                        $matriz = array($this->getFilaConsulta());
                 
                  } else {
                  
                        for ($i = 0; $i < $this->getCantidadFilasConsulta()-1; $i++)
                        {
                              if ($i == 0)
                              {
                                    
                                    $matriz = array($this->getFilaConsulta());
                              }
                              
                              $matriz = array_merge($matriz,array($this->getFilaConsulta()));
                        
                        }
                  }
                  
                  return $matriz;
		}
		
		function getConsultaArray()//Obtiene una fila asociativa de un select, tanto con inidice como por nombre de campo
		{
			$this->filaResultado = $this->resultadoQuery->fetch_array();
			return $this->filaResultado;
		
		}
		
		function getFilaPosicion()//Obtiene una fila de un select asociativa por posicion de campo
		{
		
                  $this->filaResultado = $this->resultadoQuery->fetch_row();
			return $this->filaResultado;
		
		
		}
		
		//$registros: Array ;ejemplo: $registros = array("nombre,apellido,edad","'jose','castillo',27") 
		//$tabla: cadena; ejemplo: "departamento"
		function ingresar($registros,$tabla,$opcion = '')//Ingresa un registro
		{
			$sql = "insert into ".$tabla." (".$registros[0].") values (".$registros[1].")";
//echo $sql;			
			//------RETORNA EL STRING DEL SQL PARA PREPARAR UNA TRANSACCION--------//
			if (strcmp($opcion,'TRANSACCION') == 0)
			{

			
			   return $sql;
			}
			//--------------------------------------------------------------------//
			
			$operacion = "INGRESAR";
			
			if($this->ejecutarQuery($sql))
			{
                //$this->registrarHistorial($tabla,$operacion,$sql);
				return true;
				
			} else {
			
				return false;
			}
		}
            
		//$registros: Array ;ejemplo: $registros = array("nombre='jose',apellido='castillo'","cedula='14852369'") 
		//$tabla: cadena; ejemplo: "departamento"
		function modificar($registros,$tabla, $opcion = '')//Modifica un registro
		{
				$sql = "update ".$tabla." set ".$registros[0]." where ".$registros[1];
//echo $sql;		
				//------RETORNA EL STRING DEL SQL PARA PREPARAR UNA TRANSACCION--------//
      			if (strcmp($opcion,'TRANSACCION') == 0)
      			{
      			
      			   return $sql;
      			}
      			//--------------------------------------------------------------------//
      			
				$operacion = "MODIFICAR";
				
				if($this->ejecutarQuery($sql))
				{
					//$this->registrarHistorial($tabla,$operacion,$sql);				  
					return true;
					
				} else {
				
					return false;
				}
		
		}
            
		//$condicion: cadena; ejemplo: "codigo=5"
		//$tabla: cadena; ejemplo: "departamento"
		function eliminar($condicion,$tabla, $opcion = '')//Elimina un registro
		{
      		$sql = "delete from ".$tabla." where ".$condicion;
//echo $sql;      		
      		//------RETORNA EL STRING DEL SQL PARA PREPARAR UNA TRANSACCION--------//
      		if (strcmp($opcion,'TRANSACCION') == 0)
      		{
      		
      		   return $sql;
      		}
      		//--------------------------------------------------------------------//
      		
      		
      		$operacion = "ELIMINAR";
      		
      		if($this->ejecutarQuery($sql))
      		{
                //$this->registrarHistorial($tabla,$operacion,$sql);				        
      			return true;
      			
      		} else {
      		
      			return false;
      		}
		
		}
            
        /*function registrarHistorial($tabla,$operacion,$sql)//registra en la tabla historial las operaciones (modificar, Eliminar, Insertar) realizadas en la BDD
		{
		
                  $sql = mysql_escape_string($sql);
                  $coUsuario = $_SESSION['coUsuarioSesion'];
                  $fecha = date("Y-m-d");
		    
                  $resultado = $this->consultar("SELECT max(cohistorial) as cohistorial from historialsistema", 'objeto');
                  $coHistorial = $resultado->cohistorial+1;
            		    
                  $query = "INSERT INTO historialsistema (cohistorial,cousuario,tabla,operacion,cadenasql,fecha)  
                              VALUES ($coHistorial,$coUsuario,'$tabla','$operacion','$sql','$fecha')";
                  
                  $this->ejecutarQuery($query);
		
		}*/
            
		function consultar($sql = null,$tipoConsulta = null)//Realiza una consulta
		{
                  
            $resultadoQuery = $this->ejecutarQuery($sql);
      			
      		if($tipoConsulta == 'cantFilas')
      		{
      			return $this->getCantidadFilasConsulta();
      		
      		} else if ($tipoConsulta == 'objeto')
      		{
      		
      			return $this->getObjetoConsulta();
      			
      			
      		} else if ($tipoConsulta == 'filaMatriz')
      		{
      		
      			return $this->getConsultaArray();
      			
      		} else if ($tipoConsulta == 'fila')
      		{
      			return $this->getFilaConsulta();
      		
      		} else if ($tipoConsulta == 'xml')
      		{
      			return $this->devolverXML();
      		
      		} else if ($tipoConsulta == 'matriz')
      		{
      		
      		    return $this->getMatrizCompleta();
      		    
      		} else {
      		
      		    return $resultadoQuery;
      		}
		}

		public function __get($retorno)
		{
			if($retorno == 'cantFilas')
			{
				return $this->getCantidadFilasConsulta();
			
			} else if ($retorno == 'filasCRUD')
			{
				return $this->getCantidadFilasCRUD();
			
			} else if ($retorno == 'objeto')
			{
			
				return $this->getObjetoConsulta();
				
				
			} else if ($retorno == 'filaMatriz')
			{
			
				return $this->getConsultaArray();
				
			} else if ($retorno == 'fila')
			{
				return $this->getFilaConsulta();
				
			} else if ($retorno =='xml')
			{
                        return $this->devolverXML();
                        			   
			} else if ($tipoConsulta == 'matriz')
      		{
      		
      		    return $this->getMatrizCompleta();
      		}

		}
		

		function devolverXML($arreglo  = null)
		{	
						
			$doc = new DOMDocument('1.0','utf-8');
			$doc->formatOutput = true;
				
	
			$resultado = $doc->createElement('resultado');
			$resultado = $doc->appendChild($resultado);	

                  if ($arreglo == null)
                  {
      			if ($this->getCantidadFilasConsulta() > 0)
      			{
      				
      				
      				for($i = 0; $i < $this->getCantidadFilasConsulta(); $i++)
      				{
      					
      					$etiqueta = $doc->createElement('fila');
      	
      					$arrayObject = new ArrayObject($this->__get("fila"));
      					$iterator = $arrayObject->getIterator();
      	
      					while($iterator->valid()) 
      					{
      						$etiqueta->setAttribute($iterator->key(),$iterator->current());
      						$etiqueta = $resultado->appendChild($etiqueta);
      						$iterator->next();
      					}
      					
      				}
      
      				$resultado->setAttribute("filas",$this->getCantidadFilasConsulta());
      
      
      			} else {
      			
      				$resultado->setAttribute("filas","0");
      
      			}
                        		
      		} else {
      		
            		for($i = 0; $i < count($arreglo); $i++)
      			{
      
      				$etiqueta = $doc->createElement('fila');
      
      				$arrayObject = new ArrayObject($arreglo[$i]);
      				$iterator = $arrayObject->getIterator();
      
      				while($iterator->valid()) 
      				{
      					
      					$etiqueta->setAttribute($iterator->key(),$iterator->current());
      					$etiqueta = $resultado->appendChild($etiqueta);
      					
      					$iterator->next();
      
      				}
      				
      			}
      			$resultado->setAttribute("filas",count($arreglo));
      		
      		}

			header('content-type: application/xml');
			return $doc->saveXML();

		}

            //$registros: Array ;ejemplo: $registros = array("nombre,apellido,edad","'jose','castillo',27") 
		//$tabla: cadena; ejemplo: "departamento"
		function stringInsertarCommit($registros,$tabla)//crea un string de insert para una transaccion
		{
		
                  return $this->ingresar($registros,$tabla,'TRANSACCION');
		}
		
		//$registros: Array ;ejemplo: $registros = array("nombre='jose',apellido='castillo'","cedula='14852369'") 
		//$tabla: cadena; ejemplo: "departamento"
		function stringModificarCommit($registros,$tabla)//crea un string de delete para una transaccion
		{
		
		    return $this->modificar($registros,$tabla,'TRANSACCION');
		
		}
		
		
		//$condicion: cadena; ejemplo: "codigo=5"
		//$tabla: cadena; ejemplo: "departamento"
		function stringBorrarCommit($condicion,$tabla)//crea un string de update para una transaccion
		{
		
		    return $this->eliminar($condicion,$tabla,'TRANSACCION');
		
		}
		
		//$vectorSql: vector que contiene una sentencia sql por cada posicion
		function ejecutarTransaccion($vectorSql)//metodo que la transaccion la transaccion
		{
                  $sql = "";
                  $this->recursoConexion->autocommit(FALSE);
		    
                  for ($i = 0; $i < count($vectorSql); $i++)
                  {
                  
                        if (!$this->ejecutarQuery($vectorSql[$i]))
                        {
                              return false;
                              break;
                              
                        }
						
                        $sql.= $vectorSql[$i].";\n";
                  }
                 
                  //return $sql;

                  
                  if($this->recursoConexion->commit())
                  {
                  
                        $this->recursoConexion->autocommit(TRUE);                        
                        //$this->registrarHistorial('VARIAS','TRANSACCION',$sql);                       
                        return true;
                        
                  } else {
                  
                        $this->recursoConexion->autocommit(TRUE);
                        return false;
                  }
                  
		}
		
		function unirMatriz($matriz1 = '', $matriz2 = '')
		{
		
                  if (is_array($matriz1) == false && is_array($matriz2) == false)
                  {
                  
                        return array(array());
                        
                  } else if (is_array($matriz1) == true && is_array($matriz2) == false)
                  {
                        return $matriz1;
                        
                  } else if (is_array($matriz1) == false && is_array($matriz2) == true)
                  {
                        return $matriz2;
                        
                  } else if (is_array($matriz1) == true && is_array($matriz2) == true)
                  {
                        return array_merge($matriz1,$matriz2);
                        
                  }
                  
		
		}
		
		function __destruct()
		{
			//$this->resultadoQuery->close();
			$this->recursoConexion->close();
		}
		
		function registrarSesion() {
			
				
					$sql=" UPDATE  usuarios 
					       SET UltimaSesion ='".$_SESSION["UltimaSesion"]."' ,
					       IP  ='".$_SESSION["IP"]."',
					       HOSTNAME  ='".$_SESSION["HOSTNAME"]."'
					       WHERE (Usuario ='".$_SESSION["CADENA_USUARIO"]."')";
					       
					       
					    $this->consultar($sql);
		}
		
	    function compareIP() {
			
			$ipSession= $_SESSION["IP"];
			
			
			$sql=" SELECT IP 
			FROM  usuarios
			WHERE (Usuario ='".$_SESSION["USUARIO_ACTUAL"]."')";
		   
		   $fila= 	    $this->consultar($sql,'fila');	
		   
		   
		   $ipBD = $fila ["IP"];
		   
			if(	$ipSession == $ipBD ) {
				return 1;
			} else return 	0;       
					       
		}
		
		function compararSesion($usuario) {
			
				
					$sql=" SELECT UltimaSesion, IP, HOSTNAME, Usuario
					       FROM  usuarios
					       WHERE (Usuario ='".$usuario."')";
					       
					       
				     $fila= 	    $this->consultar($sql,'fila');
					       
					      
				return $fila;
		}
		
			function compareTime($Time, $today) {

			$sessionTime = new DateTime($Time);
			$today = new DateTime();
			$diff = $sessionTime->diff($today);
 
          //echo "<br>". $diff->format('%d-%m-%Y %h:%i:%s')."<br>";
 
			if ($diff->format('%d') > 0 || $diff->format('%m') > 0 || $diff->format('%Y') > 0 
			 || $diff->format('%h') > 0 || $diff->format('%i') > 5 ) return 1; 
			else return 0; 
		}
		
		

		function isActiveSession($usuario) {
			
				
					$sql=" SELECT UltimaSesion, IP, HOSTNAME, Usuario
					       FROM  usuarios
					       WHERE (Usuario ='".$usuario."')";
					
					       
					       
				return	    $this->consultar($sql,'fila');
		}
		
		function clearSesion() {
			
				
					$sql=" UPDATE  usuarios 
					       SET UltimaSesion ='".$_SESSION["UltimaSesion"]."' ,
					       IP  ='',
					       HOSTNAME  =''
					       WHERE (Usuario ='".$_SESSION["CADENA_USUARIO"]."')";
					       
					       
					    $this->consultar($sql);
		}

	}
?>
