<?php
include("fphp.php");
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	MAESTRO DE APLICACIONES
if ($_POST['modulo']=="APLICACIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	$periodo=strtr($_POST['periodo'], "/", "-");
	$voucher=strtoupper($_POST['voucher']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastaplicaciones WHERE CodAplicacion='$codigo' OR Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastaplicaciones VALUES ('$codigo', '".$descripcion."', '$periodo', '$voucher', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastaplicaciones WHERE Descripcion='".$descripcion."' AND CodAplicacion<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastaplicaciones SET Descripcion='$descripcion', PeriodoContable='$periodo', PrefVoucher='$voucher', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodAplicacion='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastmiscelaneoscab WHERE CodAplicacion='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM mastmiscelaneosdet WHERE CodAplicacion='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM mastparametros WHERE CodAplicacion='".$_POST['codigo']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else $error=0;
			}
		}
	}
	echo $error;
}

//	MAESTRO DE PAISES
elseif ($_POST['modulo']=="PAISES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	$obs=strtoupper(utf8_decode($_POST['obs']));
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpaises WHERE Pais='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastpaises", "CodPais", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastpaises VALUES ('$codigo', '$descripcion', '$obs', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpaises WHERE Pais='$descripcion' AND CodPais<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastpaises SET Pais='$descripcion', Observaciones='$obs', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPais='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastestados WHERE CodPais='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE ESTADOS
elseif ($_POST['modulo']=="ESTADOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastestados WHERE Estado='$descripcion' AND CodPais='".$_POST['pais']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastestados", "CodEstado", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastestados VALUES ('$codigo', '$descripcion', '".$_POST['pais']."', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastestados WHERE Estado='$descripcion' AND CodPais='".$_POST['pais']."' AND CodEstado<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastestados SET Estado='$descripcion', CodPais='".$_POST['pais']."', Status='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodEstado='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastmunicipios WHERE CodEstado='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE MUNICIPIOS
elseif ($_POST['modulo']=="MUNICIPIOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastmunicipios WHERE Municipio='$descripcion' AND CodEstado='".$_POST['estado']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastmunicipios", "CodMunicipio", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastmunicipios VALUES ('$codigo', '".$_POST['estado']."', '$descripcion', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastmunicipios WHERE Municipio='$descripcion' AND CodEstado='".$_POST['estado']."' AND CodMunicipio<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastmunicipios SET Municipio='$descripcion', CodEstado='".$_POST['estado']."', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodMunicipio='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastciudades WHERE CodMunicipio='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE CIUDADES
elseif ($_POST['modulo']=="CIUDADES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastciudades WHERE CodCiudad='".$_POST['codigo']."' OR (Ciudad='$descripcion' AND CodMunicipio='".$_POST['municipio']."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastciudades", "CodCiudad", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastciudades (CodCiudad, CodPostal, CodMunicipio, Ciudad, CodPostal, UltimoUsuario, UltimaFecha) VALUES ('".$codigo."', '$postal', '".$_POST['municipio']."', '$descripcion', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastciudades WHERE (CodCiudad='".$_POST['codigo']."' OR (Ciudad='$descripcion' AND CodMunicipio='".$_POST['municipio']."')) AND CodCiudad<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastciudades SET Ciudad='$descripcion', CodMunicipio='".$_POST['municipio']."', CodPostal='".$postal."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCiudad='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$mastorganismos=esFKey("mastorganismos", "CodCiudad", $codigo);
		$mastpersonas1=esFKey("mastpersonas", "CiudadNacimiento", $codigo);
		$mastpersonas2=esFKey("mastpersonas", "CiudadDomicilio", $codigo);
		$rh_capacitacion=esFKey("rh_capacitacion", "CodCiudad", $codigo);
		$rh_postulantes1=esFKey("rh_postulantes", "CiudadNacimiento", $codigo);
		$rh_postulantes2=esFKey("rh_postulantes", "CiudadDomicilio", $codigo);
		if ($mastorganismos || $mastpersonas1 || $mastpersonas2 || $rh_capacitacion || $rh_postulantes1 || $rh_postulantes2) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="DELETE FROM mastciudades WHERE CodCiudad='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	MAESTRO DE TIPOS DE PAGO
elseif ($_POST['modulo']=="TIPOSPAGO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM masttipopago WHERE TipoPago='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("masttipopago", "CodTipoPago", 2);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO masttipopago VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM masttipopago WHERE TipoPago='$descripcion' AND CodTipoPago<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE masttipopago SET TipoPago='$descripcion', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoPago='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastempleado WHERE CodTipoPago='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE ORGANISMOS
elseif ($_POST['modulo']=="ORGANISMOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	$descripcionc=strtoupper($_POST['descripcionc']);
	$rep=strtoupper($_POST['rep']);
	$dir=strtoupper($_POST['dir']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fecha']); $fecha=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastorganismos WHERE Organismo='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastorganismos", "CodOrganismo", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql = "INSERT INTO mastorganismos 
					VALUES (
						'$codigo', 
						'".htmlentities($descripcion)."', 
						'".$_POST['codpersona']."', 
						'".htmlentities($descripcionc)."', 
						'".htmlentities($rep)."', 
						'".$_POST['docr']."', 
						'".htmlentities($_POST['www'])."', 
						'".$_POST['docf']."', 
						'$fecha', 
						'$dir', 
						'".$_POST['ciudad']."', 
						'".$_POST['tel1']."', 
						'".$_POST['tel2']."', 
						'".$_POST['tel3']."', 
						'".$_POST['fax1']."', 
						'".$_POST['fax2']."', 
						'".$_POST['logo']."', 
						'".$_POST['status']."', 
						'".$_POST['nreg']."', 
						'".$_POST['treg']."', 
						'".$_SESSION['USUARIO_ACTUAL']."', 
						'$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastorganismos WHERE Organismo='$descripcion' AND CodOrganismo<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastorganismos SET Organismo='".htmlentities($descripcion)."', CodPersona='".$_POST['codpersona']."', DescripComp='".htmlentities($descripcionc)."', RepresentLegal='$rep', DocRepreLeg='".$_POST['docr']."', PaginaWeb='".htmlentities($_POST['www'])."', DocFiscal='".$_POST['docf']."', FechaFundac='$fecha', Direccion='$dir', CodCiudad='".$_POST['ciudad']."', Telefono1='".$_POST['tel1']."', Telefono2='".$_POST['tel2']."', Telefono3='".$_POST['tel3']."', Fax1='".$_POST['fax1']."', Fax2='".$_POST['fax2']."', Logo='".$_POST['logo']."', Estado='".$_POST['status']."', NumReg='".$_POST['nreg']."', TomoReg='".$_POST['treg']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastdependencias, mastdivisiones, rh_sindicatos, mastparametros, mastempleado, rh_contratos WHERE mastdependencias.CodOrganismo='".$_POST['codigo']."' OR mastdivisiones.CodOrganismo='".$_POST['codigo']."' OR rh_sindicatos.CodOrganismo='".$_POST['codigo']."' OR mastparametros.CodOrganismo='".$_POST['codigo']."' OR mastempleado.CodOrganismo='".$_POST['codigo']."' OR rh_contratos.CodOrganismo='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM mastdependencias WHERE CodOrganismo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM mastdivisiones WHERE CodOrganismo='".$_POST['codigo']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else {
					$sql="SELECT * FROM rh_sindicatos WHERE CodOrganismo='".$_POST['codigo']."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
					else {
						$sql="SELECT * FROM mastparametros WHERE CodOrganismo='".$_POST['codigo']."'";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						$rows=mysql_num_rows($query);
						if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
						else {
							$sql="SELECT * FROM mastempleado WHERE CodOrganismo='".$_POST['codigo']."'";
							$query=mysql_query($sql) or die ($sql.mysql_error());
							$rows=mysql_num_rows($query);
							if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
							else {								
								$sql="SELECT * FROM rh_contratos WHERE CodOrganismo='".$_POST['codigo']."'";
								$query=mysql_query($sql) or die ($sql.mysql_error());
								$rows=mysql_num_rows($query);
								if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
								else $error=0;
							}
						}
					}
				}
			}
		}
	}
	echo $error;
}

//	MAESTRO DE DEPENDENCIAS
elseif ($_POST['modulo']=="DEPENDENCIAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastdependencias WHERE Dependencia='$descripcion' AND CodOrganismo='".$_POST['organismo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastdependencias", "CodDependencia", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastdependencias VALUES ('$codigo', '".$_POST['organismo']."', '$descripcion', '".$_POST['tel1']."', '".$_POST['tel2']."', '".$_POST['ext1']."', '".$_POST['ext2']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastdependencias WHERE Dependencia='$descripcion' AND CodOrganismo='".$_POST['organismo']."' AND CodDependencia<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastdependencias SET Dependencia='$descripcion', CodOrganismo='".$_POST['organismo']."', Telefono1='".$_POST['tel1']."', Telefono2='".$_POST['tel2']."', Extencion1='".$_POST['ext1']."', Extencion2='".$_POST['ext2']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodDependencia='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastdivisiones, mastempleado WHERE mastdivisiones.CodDependencia='".$_POST['codigo']."' OR mastempleado.CodDependencia='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM mastempleado WHERE CodDependencia='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
	}
	echo $error;
}

//	MAESTRO DE DIVISIONES
elseif ($_POST['modulo']=="DIVISIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastdivisiones WHERE Division='$descripcion' AND CodDependencia='".$_POST['dependencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("mastdivisiones", "CodDivision", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastdivisiones VALUES ('$codigo', '".$_POST['dependencia']."', '".$_POST['organismo']."', '$descripcion', '".$_POST['ext']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastdivisiones WHERE Division='$descripcion' AND CodDependencia='".$_POST['dependencia']."' AND CodDivision<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastdivisiones SET Division='$descripcion', CodDependencia='".$_POST['dependencia']."', CodOrganismo= '".$_POST['organismo']."', Extencion='".$_POST['ext']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodDivision='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastempleado WHERE CodDivision='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE GRUPO OCUPACIONAL
elseif ($_POST['modulo']=="GRUPOOCUPACIONAL") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_grupoocupacional WHERE CodGrupOcup = '$codigo' OR GrupoOcup = '$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_grupoocupacional VALUES ('$codigo', '$descripcion', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_grupoocupacional WHERE GrupoOcup='$descripcion' AND CodGrupOcup<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_grupoocupacional SET GrupoOcup='$descripcion', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodGrupOcup='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_serieocupacional, rh_puestos WHERE rh_serieocupacional.CodGrupOcup='".$_POST['codigo']."' OR rh_puestos.CodGrupOcup='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM rh_serieocupacional WHERE CodGrupOcup='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
	}
	echo $error;
}

//	MAESTRO DE SERIE OCUPACIONAL
elseif ($_POST['modulo']=="SERIEOCUPACIONAL") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_serieocupacional WHERE (SerieOcup='$descripcion' OR CodSerieOcup = '$codigo') AND CodGrupOcup='".$_POST['grupo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_serieocupacional VALUES ('$codigo', '".$_POST['grupo']."', '$descripcion', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_serieocupacional WHERE (SerieOcup='$descripcion' AND CodGrupOcup='".$_POST['grupo']."') AND CodSerieOcup<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_serieocupacional SET SerieOcup='$descripcion', CodGrupOcup='".$_POST['grupo']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodSerieOcup='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_puestos WHERE CodSerieOcup='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE TIPOS DE CARGO
elseif ($_POST['modulo']=="TIPOSCARGO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipocargo WHERE TipCargo='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_tipocargo", "CodTipoCargo", 3);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_tipocargo VALUES ('$codigo', '$descripcion', '".$_POST['definicion']."', '".$_POST['funcion']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipocargo WHERE TipCargo='$descripcion' AND CodTipoCargo<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_tipocargo SET TipCargo='$descripcion', Funcion='".$_POST['funcion']."', Definicion='".$_POST['definicion']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoCargo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$secuencia=getSecuencia("Secuencia", "CodTipoCargo", "rh_tipocargoriesgos", $_POST['codigo']);
			$sql="INSERT INTO rh_tipocargoriesgos VALUES ('$secuencia', '".$_POST['codigo']."', '".$_POST['riesgo']."', '".$_POST['causa']."', '".$_POST['consecuencia']."', '".$_POST['prevencion']."')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		} else {
			$sql="UPDATE rh_tipocargoriesgos SET Riesgo='".$_POST['riesgo']."', Causa='".$_POST['causa']."', Consecuencia='".$_POST['consecuencia']."', Prevencion='".$_POST['prevencion']."' WHERE Secuencia='".$_POST['secuencia']."' AND CodTipoCargo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());		
		}
		echo 0;
	}
	elseif ($_POST['accion']=="EDITAR") {
		$sql="SELECT * FROM rh_tipocargoriesgos WHERE CodTipoCargo='".$_POST['codigo']."' AND Secuencia='".$_POST['secuencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		echo $error."riesgo=".$field['Riesgo']."riesgo=".$field['Causa']."riesgo=".$field['Consecuencia']."riesgo=".$field['Prevencion'];
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_tipocargoriesgos, rh_nivelclasecargo, rh_puestos WHERE rh_tipocargoriesgos.CodTipoCargo='".$_POST['codigo']."' OR rh_nivelclasecargo.CodTipoCargo='".$_POST['codigo']."' OR rh_puestos.CodTipoCargo='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM rh_nivelclasecargo WHERE CodTipoCargo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM rh_puestos WHERE CodTipoCargo='".$_POST['codigo']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else $error=0;
			}
		}
		echo $error;
	}
}

//	MAESTRO DE NIVELES DE TIPO DE CARGO
elseif ($_POST['modulo']=="NIVELESCARGO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_nivelclasecargo WHERE NivelClase='$descripcion' AND CodTipoCargo='".$_POST['tipocargo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getSecuencia_0("CodNivelClase", "CodTipoCargo", "rh_nivelclasecargo", $_POST['tipocargo']);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_nivelclasecargo VALUES ('$codigo', '".$_POST['tipocargo']."', '$descripcion', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_nivelclasecargo WHERE NivelClase='$descripcion' AND CodTipoCargo='".$_POST['tipocargo']."' AND CodNivelClase<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_nivelclasecargo SET NivelClase='$descripcion', CodTipoCargo='".$_POST['tipocargo']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodNivelClase='".$_POST['codigo']."' AND CodTipoCargo='".$_POST['tipocargo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		list($nivel, $tipocargo)=SPLIT('[_]', $_POST['codigo']);
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_puestos WHERE CodNivelClase='".$nivel."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE CARGOS
elseif ($_POST['modulo']=="CARGOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_puestos WHERE (CodDesc = '$codcargo')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_puestos", "CodCargo", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_puestos (CodCargo, CodGrupOcup, CodSerieOcup, CodTipoCargo, CodNivelClase, NivelSalarial, CodDesc, DescripCargo, CategoriaCargo, Grado, Estado, Plantilla, DescGenerica, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".$_POST['grupo']."', '".$_POST['serie']."', '".$_POST['tipocargo']."', '".$_POST['nivelcargo']."', '".$_POST['sueldo']."', '$codcargo', '".utf8_decode($descripcion)."', '".$_POST['ttra']."', '".$_POST['gcargo']."', '".$_POST['status']."', '$plantilla_competencias', '".utf8_decode($descripcion_generica)."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error.":".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_puestos WHERE (CodDesc='$codcargo') AND CodCargo<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="UPDATE rh_puestos SET CodGrupOcup='".$_POST['grupo']."', CodSerieOcup='".$_POST['serie']."', CodTipoCargo='".$_POST['tipocargo']."', CodNivelClase='".$_POST['nivelcargo']."', NivelSalarial='".$_POST['sueldo']."', DescripCargo='".utf8_decode($descripcion)."', CodDesc='$codcargo', CategoriaCargo='".$_POST['ttra']."', Grado='".$_POST['gcargo']."', Estado='".$_POST['status']."', Plantilla='$plantilla_competencias', DescGenerica='".utf8_decode($descripcion_generica)."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCargo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="DESCRIPCION") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT rh_tipocargo.TipCargo, rh_nivelclasecargo.CodNivelClase, rh_nivelclasecargo.NivelClase FROM rh_tipocargo, rh_nivelclasecargo WHERE rh_tipocargo.CodTipoCargo='".$_POST['tipocargo']."' AND rh_nivelclasecargo.CodNivelClase='".$_POST['nivelcargo']."' AND rh_tipocargo.CodTipoCargo=rh_nivelclasecargo.CodTipoCargo";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	{ $field=mysql_fetch_array($query); $tipo=$field[0]; $codnivel=$field[1]; $nivel=$field[2]; }
		
		$sql="SELECT rh_serieocupacional.SerieOcup FROM rh_serieocupacional WHERE rh_serieocupacional.CodSerieOcup='".$_POST['serie']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	{ $field=mysql_fetch_array($query); $serie=$field[0]; }
		echo $tipo." DE ".$serie." ".$nivel;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM mastempleado WHERE CodCargo='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargoexperiencia WHERE CodCargo='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				//	CONSULTO SI EL REGISTRO EXISTE
				$sql="SELECT * FROM rh_cargoformacion WHERE CodCargo='".$_POST['codigo']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else {
					//	CONSULTO SI EL REGISTRO EXISTE
					$sql="SELECT * FROM rh_cargofunciones WHERE CodCargo='".$_POST['codigo']."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
					else {
						//	CONSULTO SI EL REGISTRO EXISTE
						$sql="SELECT * FROM rh_cargoidioma WHERE CodCargo='".$_POST['codigo']."'";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						$rows=mysql_num_rows($query);
						if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
						else {
							//	CONSULTO SI EL REGISTRO EXISTE
							$sql="SELECT * FROM rh_cargoinformat WHERE CodCargo='".$_POST['codigo']."'";
							$query=mysql_query($sql) or die ($sql.mysql_error());
							$rows=mysql_num_rows($query);
							if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
							else {
								//	CONSULTO SI EL REGISTRO EXISTE
								$sql="SELECT * FROM rh_cargorelaciones WHERE CodCargo='".$_POST['codigo']."'";
								$query=mysql_query($sql) or die ($sql.mysql_error());
								$rows=mysql_num_rows($query);
								if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
								else {
									//	CONSULTO SI EL REGISTRO EXISTE
									$sql="SELECT * FROM rh_cargoreporta WHERE CodCargo='".$_POST['codigo']."'";
									$query=mysql_query($sql) or die ($sql.mysql_error());
									$rows=mysql_num_rows($query);
									if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
									else $error=0;
								}
							}
						}
					}
				}
			}
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGO-SUELDO") {
		$sql = "SELECT SueldoPromedio FROM rh_nivelsalarial WHERE CategoriaCargo = '$ttra' AND Grado = '$gcargo'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0) $field = mysql_fetch_array($query);
		echo $field[0];
	}
	elseif ($_POST['accion']=="CARGOREPORTA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargoreporta WHERE CodCargo='".$_POST['cargo']."' AND CargoReporta='".$_POST['cargosup']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE CARGO";
		else {
			$error=0;
			$secuencia=getCodigo("rh_cargoreporta", "Secuencia", 6);
			$sql="INSERT INTO rh_cargoreporta (CodCargo, Secuencia, CargoReporta, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '$secuencia', '".$_POST['cargosup']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGORELACION") {
		$ente=strtoupper($_POST['ente']);
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargorelaciones WHERE CodCargo='".$_POST['cargo']."' AND TipoRelacion='".$_POST['tipo']."' AND EnteRelacionado='$ente'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTA RELACION";
		else {
			$error=0;
			$secuencia=getCodigo("rh_cargorelaciones", "Secuencia", 6);
			$sql="INSERT INTO rh_cargorelaciones (CodCargo, Secuencia, TipoRelacion, EnteRelacionado, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '$secuencia', '".$_POST['tipo']."', '".$ente."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOFUNCION") {
		$comentarios=strtoupper($_POST['comentarios']);
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargofunciones WHERE Funcion='".$_POST['funciones']."' AND Descripcion='$comentarios'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTA FUNCION";
		else {
			$error=0;
			$secuencia=getSecuencia("CodFuncion", "CodCargo", "rh_cargofunciones", $_POST['cargo']);
			$sql="INSERT INTO rh_cargofunciones(CodCargo, CodFuncion, Funcion, Descripcion, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '$secuencia', '".$_POST['funciones']."', '".$comentarios."', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOFORMACION") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargoformacion WHERE CodCargo='".$_POST['cargo']."' AND CodGradoInstruccion='".$_POST['grado']."' AND Area='".$_POST['area']."' AND CodProfesion='".$_POST['profesion']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTA FORMACION";
		else {
			$error=0;
			$secuencia=getCodigo("rh_cargoformacion", "Secuencia", 6);
			$sql="INSERT INTO rh_cargoformacion(CodCargo, Secuencia, CodgradoInstruccion, Area, CodProfesion, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '$secuencia', '".$_POST['grado']."', '".$_POST['area']."', '".$_POST['profesion']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOIDIOMA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargoidioma WHERE CodCargo='".$_POST['cargo']."' AND CodIdioma='".$_POST['idioma']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE IDIOMA";
		else {
			$error=0;
			$sql="INSERT INTO rh_cargoidioma (CodCargo, CodIdioma, NivelLectura, NivelOral, NivelEscritura, NivelGeneral, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '".$_POST['idioma']."', '".$_POST['lectura']."', '".$_POST['oral']."', '".$_POST['escritura']."', '".$_POST['general']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOINFORMAT") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargoinformat WHERE CodCargo='".$_POST['cargo']."' AND Informatica='".$_POST['curso']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE CURSO";
		else {
			$error=0;
			$sql="INSERT INTO rh_cargoinformat (CodCargo, Informatica, Nivel, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '".$_POST['curso']."', '".$_POST['nivel']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOEXPERIENCIA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargoexperiencia WHERE CodCargo='".$_POST['cargo']."' AND AreaExperiencia='".$_POST['area']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTA EXPERIENCIA";
		else {
			$error=0;
			$secuencia=getCodigo("rh_cargoexperiencia", "Secuencia", 6);
			$sql="INSERT INTO rh_cargoexperiencia (CodCargo, Secuencia, AreaExperiencia, FlagNecesario, Meses, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '$secuencia', '".$_POST['area']."', '".$_POST['flag']."', '".$_POST['meses']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGORIESGO") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargoriesgo WHERE CodCargo='".$_POST['cargo']."' AND TipoRiesgo='".$_POST['triesgo']."' AND Riesgo='".$_POST['riesgo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE RIESGO";
		else {
			$error=0;
			$secuencia=getCodigo("rh_cargoriesgo", "Secuencia", 6);
			$sql="INSERT INTO rh_cargoriesgo (CodCargo, Secuencia, TipoRiesgo, Riesgo, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '$secuencia', '".$_POST['triesgo']."', '".$riesgo."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOSUB") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargosub WHERE CodCargo='".$_POST['cargo']."' AND CargoSubordinado='".$_POST['cargos']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE CARGO";
		else {
			$error=0;
			$sql="INSERT INTO rh_cargosub (CodCargo, CargoSubordinado, Cantidad, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', '".$_POST['cargos']."', '".$_POST['cantidad']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOCURSO") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargocursos WHERE CodCargo='".$_POST['cargo']."' AND Curso='".$_POST['cursos']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE REGISTRO";
		else {
			$error=0;
			$secuencia=getSecuencia("Secuencia", "CodCargo", "rh_cargocursos", $_POST['cargo']);
			$sql="INSERT INTO rh_cargocursos (CodCargo, Secuencia, Curso, TotalHoras, AniosVigencia, Observaciones, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', $secuencia, '".$_POST['cursos']."', '".$_POST['horas']."', '".$_POST['vigencia']."', '".$_POST['observaciones']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOMETA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargometas WHERE CodCargo='".$_POST['cargo']."' AND Descripcion='".$_POST['descripcion']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE REGISTRO";
		else {
			$error=0;
			$secuencia=getSecuencia("Secuencia", "CodCargo", "rh_cargometas", $_POST['cargo']);
			$sql="INSERT INTO rh_cargometas (CodCargo, Secuencia, Descripcion, FactorParticipacion, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['cargo']."', $secuencia, '".$_POST['descripcion']."', '".$_POST['factor']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOCOMPETENCIA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="DELETE FROM rh_cargocompetencia WHERE CodCargo='".$_POST['codcargo']."' AND Competencia='".$_POST['competencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="CARGOEVALUACION") {
		if ($inserto=="NUEVO") {
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargoevaluacion WHERE CodCargo='".$_POST['codcargo']."' AND Evaluacion='".$_POST['evaluacion']."' AND Etapa='".$etapa."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE INGRESO ESTE REGISTRO";
			else {
				$error=0;
				$secuencia=getSecuencia("Secuencia", "CodCargo", "rh_cargoevaluacion", $_POST['codcargo']);
				$sql="INSERT INTO rh_cargoevaluacion (CodCargo, Secuencia, Etapa, Evaluacion, Factor, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['codcargo']."', $secuencia, '".$_POST['etapa']."', '".$_POST['evaluacion']."', '$factor', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		elseif ($inserto=="EDITAR") {
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargoevaluacion WHERE CodCargo='".$_POST['codcargo']."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows==0) echo "NO SE ENCONTRO ESTE REGISTRO EN LA BD";
			else {
				$field=mysql_fetch_array($query);
				echo "0|:|".$field['Etapa']."|:|".$field['Evaluacion']."|:|".$field['Factor']."|:|".$field['Estado']."|:|".$field['Secuencia'];
			}
		}
		elseif ($inserto=="ACTUALIZAR") {
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargoevaluacion WHERE CodCargo='".$_POST['codcargo']."' AND Evaluacion='".$_POST['evaluacion']."' AND Etapa='".$etapa."' AND Secuencia<>'$secuencia' AND CodCargo<>'".$_POST['codcargo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE INGRESO ESTE REGISTRO";
			else {
				$sql="UPDATE rh_cargoevaluacion SET Etapa='".$_POST['etapa']."', Evaluacion='".$_POST['evaluacion']."', Factor='$factor', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCargo='$codcargo' AND Secuencia='$secuencia'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		elseif ($inserto=="BORRAR") {
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="DELETE FROM rh_cargoevaluacion WHERE CodCargo='".$_POST['codcargo']."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="CARGOAMBIENTE") {
		if ($sub=="INSERTAR") {
			$error=0;
			$secuencia=getSecuencia("Secuencia", "CodCargo", "rh_cargoambiente", $_POST['codcargo']);
			$sql="INSERT INTO rh_cargoambiente (CodCargo, Secuencia, Ambiente, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['codcargo']."', $secuencia, '".utf8_decode($ambiente)."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		elseif ($sub=="EDITAR") {
			$error=0;
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargoambiente WHERE CodCargo='".$_POST['codcargo']."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows==0) echo "NO SE ENCONTRO ESTE REGISTRO EN LA BD";
			else {
				$field=mysql_fetch_array($query);
				echo "$error|:|".htmlentities($field['Ambiente']);
			}
		}
		elseif ($sub=="ACTUALIZAR") {
			$error=0;
			$sql="UPDATE rh_cargoambiente SET Ambiente='".utf8_decode($ambiente)."', Ultimousuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCargo='".$codcargo."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		elseif ($sub=="BORRAR") {
			$error=0;
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="DELETE FROM rh_cargoambiente WHERE CodCargo='".$codcargo."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
	}
	elseif ($_POST['accion']=="CARGOESFUERZO") {
		if ($sub=="INSERTAR") {
			$error=0;
			$secuencia=getSecuencia("Secuencia", "CodCargo", "rh_cargoesfuerzo", $_POST['codcargo']);
			$sql="INSERT INTO rh_cargoesfuerzo (CodCargo, Secuencia, Esfuerzo, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['codcargo']."', $secuencia, '".utf8_decode($esfuerzo)."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		elseif ($sub=="EDITAR") {
			$error=0;
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargoesfuerzo WHERE CodCargo='".$_POST['codcargo']."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows==0) echo "NO SE ENCONTRO ESTE REGISTRO EN LA BD";
			else {
				$field=mysql_fetch_array($query);
				echo "$error|:|".htmlentities($field['Esfuerzo']);
			}
		}
		elseif ($sub=="ACTUALIZAR") {
			$error=0;
			$sql="UPDATE rh_cargoesfuerzo SET Esfuerzo='".utf8_decode($esfuerzo)."', Ultimousuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCargo='".$codcargo."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		elseif ($sub=="BORRAR") {
			$error=0;
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="DELETE FROM rh_cargoesfuerzo WHERE CodCargo='".$codcargo."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
	}
	elseif ($_POST['accion']=="CARGOHABILIDAD") {
		if ($sub=="INSERTAR") {
			$error=0;
			$secuencia=getSecuencia("Secuencia", "CodCargo", "rh_cargohabilidades", $_POST['codcargo']);
			$sql="INSERT INTO rh_cargohabilidades (CodCargo, Secuencia, Tipo, Descripcion, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['codcargo']."', $secuencia, '$tipo', '".utf8_decode($descripcion)."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		elseif ($sub=="EDITAR") {
			$error=0;
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="SELECT * FROM rh_cargohabilidades WHERE CodCargo='".$_POST['codcargo']."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows==0) echo "NO SE ENCONTRO ESTE REGISTRO EN LA BD";
			else {
				$field=mysql_fetch_array($query);
				echo "$error|:|$tipo|:|".htmlentities($field['Descripcion']);
			}
		}
		elseif ($sub=="ACTUALIZAR") {
			$error=0;
			$sql="UPDATE rh_cargohabilidades SET Tipo='$tipo', Descripcion='".utf8_decode($descripcion)."', Ultimousuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCargo='".$codcargo."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		elseif ($sub=="BORRAR") {
			$error=0;
			//	CONSULTO SI EL REGISTRO EXISTE
			$sql="DELETE FROM rh_cargohabilidades WHERE CodCargo='".$codcargo."' AND Secuencia='".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
	}
}

//	MAESTRO DE TIPOS DE TRABAJADOR
elseif ($_POST['modulo']=="TIPOSTRABAJADOR") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipotrabajador WHERE TipoTrabajador='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_tipotrabajador", "CodTipoTrabajador", 2);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_tipotrabajador VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipotrabajador WHERE TipoTrabajador='$descripcion' AND CodTipoTrabajador<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_tipotrabajador SET TipoTrabajador='$descripcion', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoTrabajador='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastempleado WHERE CodTipoTrabajador='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE TIPOS DE NOMINA
elseif ($_POST['modulo']=="TIPOSNOMINA") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM tiponomina WHERE Nomina='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("tiponomina", "CodTipoNom", 2);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO tiponomina (CodTipoNom, Nomina, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM tiponomina WHERE Nomina='$descripcion' AND CodTipoNom<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE tiponomina SET Nomina='$descripcion', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoNom='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastempleado WHERE CodTipoNom='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE TIPOS DE PERFILES DE NOMINA
elseif ($_POST['modulo']=="TIPOSPERFIL") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM tipoperfilnom WHERE Perfil='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("tipoperfilnom", "CodPerfil", 2);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO tipoperfilnom VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM tipoperfilnom WHERE Perfil='$descripcion' AND CodPerfil<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE tipoperfilnom SET Perfil='$descripcion', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPerfil='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastempleado WHERE CodPerfil='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE MOTIVOS DE CESE
elseif ($_POST['modulo']=="MOTIVOSCESE") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_motivocese WHERE MotivoCese='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_motivocese", "CodMotivoCes", 2);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_motivocese VALUES ('$codigo', '$descripcion', '".$_POST['falta']."', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_motivocese WHERE MotivoCese='$descripcion' AND CodMotivoCes<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_motivocese SET MotivoCese='$descripcion', FlagFaltaGrave='".$_POST['falta']."', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodMotivoCes='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastempleado WHERE CodMotivoCes='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE BANCOS
elseif ($_POST['modulo']=="BANCOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {
		$codigo=getCodigo("mastbancos", "CodBanco", 4);
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastbancos WHERE Banco='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastbancos VALUES ('$codigo', '".$_POST['persona']."', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastbancos WHERE Banco='$descripcion' AND CodBanco<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastbancos SET Banco='$descripcion', CodPersona='".$_POST['persona']."', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodBanco='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$sql="SELECT CodPersona FROM mastbancos WHERE CodBanco='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) { $field=mysql_fetch_array($query); $persona=$field[0]; }
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM bancopersona WHERE CodBanco='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE COOPERATIVAS
elseif ($_POST['modulo']=="COOPERATIVAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_cooperativa WHERE NombreCoop='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_cooperativa", "CodCooperativa", 3);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_cooperativa VALUES ('$codigo', '$descripcion', '".$_POST['objeto']."', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_cooperativa WHERE NombreCoop='$descripcion' AND CodCooperativa<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_cooperativa SET NombreCoop='$descripcion', ObjetoCoop='".$_POST['objeto']."', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCooperativa='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	MAESTRO DE SINDICATOS
elseif ($_POST['modulo']=="SINDICATOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	$descripcion2=strtoupper($_POST['descripcion2']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_sindicatos WHERE Nombre='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_sindicatos", "CodSindicato", 3);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_sindicatos VALUES ('$codigo', '".$_POST['organismo']."', '$descripcion', '$descripcion2', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_sindicatos WHERE Nombre='$descripcion' AND CodSindicato<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_sindicatos SET Nombre='$descripcion', CodOrganismo='".$_POST['organismo']."', Descripcion='$descripcion2', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodSindicato='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	MAESTRO DE TIPOS DE SEGURO
elseif ($_POST['modulo']=="TIPOSSEGURO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tiposeguro WHERE Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_tiposeguro", "CodTipSeguro", 3);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_tiposeguro VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tiposeguro WHERE Descripcion='$descripcion' AND CodTipSeguro<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_tiposeguro SET Descripcion='$descripcion', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipSeguro='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_planseguro WHERE CodTipSeguro='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE PLANES DE SEGURO
elseif ($_POST['modulo']=="PLANESSEGURO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_planseguro WHERE Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_planseguro", "CodPlanSeguro", 3);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_planseguro VALUES ('$codigo', '".$_POST['tiposeguro']."', '$descripcion', '".$_POST['status']."', '".$_POST['monto']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_planseguro WHERE Descripcion='$descripcion' AND CodPlanSeguro<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_planseguro SET Descripcion='$descripcion', CodTipSeguro='".$_POST['tiposeguro']."', MontoSeguro='$monto', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPlanSeguro='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	MAESTRO DE TIPOS DE CONTRATO
elseif ($_POST['modulo']=="TIPOSCONTRATO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipocontrato WHERE TipoContrato='$codigo' OR Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_tipocontrato VALUES ('$codigo', '$descripcion', '".$_POST['nomina']."', '".$_POST['vence']."', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipocontrato WHERE Descripcion='$descripcion' AND TipoContrato<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_tipocontrato SET Descripcion='$descripcion', FlagNomina='".$_POST['nomina']."', FlagVencimiento='".$_POST['vence']."', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE TipoContrato='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_contratos, rh_formatocontrato WHERE rh_contratos.TipoContrato='".$_POST['codigo']."' OR rh_formatocontrato.TipoContrato='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM rh_contratos WHERE TipoContrato='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
	}
	echo $error;
}

//	MAESTRO DE FORMATOS DE CONTRATOS
elseif ($_POST['modulo']=="FORMCONTRATO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {	
		$codigo=strtoupper($_POST['codigo']);	
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_formatocontrato WHERE CodFormato='$codigo' OR Documento='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_formatocontrato VALUES ('".$_POST['tipocontrato']."', '$codigo', '$descripcion', '".$_POST['ruta']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_formatocontrato WHERE Documento='$descripcion' AND CodFormato<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_formatocontrato SET TipoContrato='".$_POST['tipocontrato']."', Documento='$descripcion', RutaPlant='".$_POST['ruta']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodFormato='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_contratos WHERE CodFormato='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE MISCELANEOS
elseif ($_POST['modulo']=="MISCELANEOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	$detalle=strtoupper($_POST['detalle']);
	$elemento=strtoupper($_POST['elemento']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastmiscelaneoscab WHERE CodMaestro='".$_POST['codigo']."' AND CodAplicacion='".$_POST['aplicacion']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=strtoupper($_POST['codigo']);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastmiscelaneoscab VALUES ('$codigo', '".$_POST['aplicacion']."', '$descripcion', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastmiscelaneoscab WHERE (CodMaestro<>'".$_POST['codigo']."' AND CodAplicacion<>'".$_POST['aplicacion']."') AND Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastmiscelaneoscab SET Descripcion='$descripcion', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodMaestro='".$_POST['codigo']."' AND CodAplicacion='".$_POST['aplicacion']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="INSERTAR") {
		if ($_POST['helemento']=="") {
			$sql="SELECT * FROM mastmiscelaneosdet WHERE CodDetalle='".$elemento."' AND CodMaestro='".$_POST['codigo']."' AND CodAplicacion='".$_POST['aplicacion']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$sql="INSERT INTO mastmiscelaneosdet VALUES ('".$elemento."', '".$_POST['codigo']."', '".$_POST['aplicacion']."', '".$detalle."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		} else {
			$sql="SELECT * FROM mastmiscelaneosdet WHERE CodDetalle='".$elemento."' AND CodMaestro='".$_POST['codigo']."' AND CodAplicacion='".$_POST['aplicacion']."' AND CodDetalle<>'".$_POST['helemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$sql="UPDATE mastmiscelaneosdet SET CodDetalle='".$elemento."', Descripcion='".$detalle."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodMaestro='".$_POST['codigo']."' AND CodAplicacion='".$_POST['aplicacion']."' AND CodDetalle='".$_POST['helemento']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
	elseif ($_POST['accion']=="EDITAR") {		
		$sql="SELECT * FROM mastmiscelaneosdet WHERE CodDetalle='".$_POST['elemento']."' AND CodMaestro='".$_POST['codigo']."' AND CodAplicacion='".$_POST['aplicacion']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		echo $error."elemento=".$field['CodDetalle']."elemento=".$field['Descripcion'];
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		list($maestro, $aplicacion)=SPLIT('[-]', $_POST['codigo']);
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastmiscelaneosdet WHERE CodMaestro='".$maestro."' AND CodAplicacion='".$aplicacion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINARELEMENTO") {
		$maestro=$_POST['codigo']; $sql="";
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		if ($maestro=="EDOCIVIL") {
			$sql="SELECT * FROM mastpersonas WHERE EstadoCivil='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="NACION") {
			$sql="SELECT * FROM mastpersonas WHERE Nacionalidad='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="SANGRE") {
			$sql="SELECT * FROM mastpersonas WHERE GrupoSanguineo='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="SITDOM") {
			$sql="SELECT * FROM mastpersonas WHERE SituacionDomicilio='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="TIPODOC") {
			$sql="SELECT * FROM mastpersonas WHERE TipoDocumento='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="TIPOLIC") {
			$sql="SELECT * FROM mastpersonas WHERE TipoLicencia='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="TIPOAPO") {
			$sql="SELECT * FROM bancopersona WHERE Aportes='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="TIPOCTA") {
			$sql="SELECT * FROM bancopersona WHERE TipoCuenta='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		elseif ($maestro=="AREA") {
			$sql="SELECT * FROM rh_cargoformacion WHERE Area='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		elseif ($maestro=="AREAEXP") {
			$sql="SELECT * FROM rh_cargoexperiencia WHERE AreaExperiencia='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="CURSOS") {
			$sql="SELECT * FROM rh_cargocursos WHERE Curso='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		elseif ($maestro=="FUNCION") {
			$sql="SELECT * FROM rh_cargofunciones WHERE Funcion='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		elseif ($maestro=="INFORMAT") {
			$sql="SELECT * FROM rh_cargoinformat WHERE Informatica='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}		
		elseif ($maestro=="MOTBA") {
			$sql="SELECT * FROM rh_cargafamiliar WHERE MotivoBaja='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		elseif ($maestro=="NIVEL") {
			$sql="SELECT * FROM rh_cargoidioma WHERE NivelLectura='".$_POST['elemento']."' OR NivelOral='".$_POST['elemento']."' OR NivelEscritura='".$_POST['elemento']."' OR NivelGeneral='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM rh_cargoinformat WHERE Nivel='".$_POST['elemento']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else $error=0;
			}
		}
		elseif ($maestro=="PARENT") {
			$sql="SELECT * FROM mastpersonas WHERE ParentEmerg1='".$_POST['elemento']."' OR ParentEmerg2='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM rh_cargafamiliar WHERE Parentesco='".$_POST['elemento']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else $error=0;
			}
		}
		elseif ($maestro=="TEDUCA") {
			$sql="SELECT * FROM rh_cargafamiliar WHERE TipoEducacion='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		elseif ($maestro=="TRIESGO") {
			$sql="SELECT * FROM rh_cargoriesgo WHERE TipoRiesgo='".$_POST['elemento']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		echo $error;
	}
}

//	MAESTRO DE PARAMETROS
elseif ($_POST['modulo']=="PARAMETROS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastparametros WHERE ParametroClave='$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastparametros VALUES ('$codigo', '".$_POST['tipo']."', '".$_POST['valor']."', '".$_POST['status']."', '".utf8_decode($descripcion)."', '".utf8_decode($_POST['explicacion'])."', '".$_POST['organismo']."', '".$_POST['aplicacion']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	ACTUALIZO REGISTRO
		$sql="UPDATE mastparametros SET TipoValor='".$_POST['tipo']."', ValorParam='".$_POST['valor']."', Estado='".$_POST['status']."', DescripcionParam='".utf8_decode($descripcion)."', Explicacion='".utf8_decode($_POST['explicacion'])."', CodOrganismo='".$_POST['organismo']."', CodAplicacion='".$_POST['aplicacion']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE ParametroClave='$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
}

//	MAESTRO DE EMPLEADOS
elseif ($_POST['modulo']=="EMPLEADOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$periodo=date("Y-m");
	$apellido1=strtoupper($_POST['apellido1']);
	$apellido2=strtoupper($_POST['apellido2']);
	$nombres=strtoupper($_POST['nombres']);
	$busqueda=strtoupper($_POST['busqueda']);
	$ncompleto=$nombres." ".$apellido1." ".$apellido2;	
	$dir=strtoupper($_POST['dir']);
	$nomcon1=strtoupper($_POST['nomcon1']);
	$nomcon2=strtoupper($_POST['nomcon2']);
	$dircon1=strtoupper($_POST['dircon1']);
	$dircon2=strtoupper($_POST['dircon2']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fnac']); $fnac=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fedocivil']); $fedocivil=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['flic']); $flic=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fingreso']); $fingreso=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fcese']); $fcese=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpersonas WHERE Ndocumento='".$_POST['ndoc']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NRO. DE DOCUMENTO EXISTENTE";
		else {
			$codpersona=getCodigo("mastpersonas", "CodPersona", 6);
			//	INSERTO LA NUEVA PERSONA
			$sql="INSERT INTO mastpersonas VALUES ('$codpersona', '".utf8_decode($apellido1)."', '".utf8_decode($apellido2)."', '".utf8_decode($nombres)."', '".utf8_decode($busqueda)."', '".$_POST['nac']."', '".utf8_decode($ncompleto)."', '".$_POST['edocivil']."', '".$_POST['sexo']."', '".$fnac."', '".$_POST['ciudad1']."', '".$fedocivil."', '".utf8_decode($dir)."', '".$_POST['tel1']."', '".$_POST['tel2']."', '".$_POST['ciudad2']."', '".$_POST['tel3']."', '".$_POST['lnac']."', '".utf8_decode($nomcon1)."', '".utf8_decode($dircon1)."', '".$_POST['telcon1']."', '".$_POST['rif']."', 'N', '".$_POST['statusreg']."', '".$_POST['ndoc']."', 'N', '".$_POST['celcon1']."', 'N', '".utf8_decode($_POST['parent1'])."', '".utf8_decode($nomcon2)."', 'S', 'N', '".utf8_decode($dircon2)."', '".$_POST['telcon2']."', '".$_POST['celcon2']."', '".$_POST['sitdom']."', '".utf8_decode($_POST['parent2'])."', '".$_POST['tdoc']."', '".utf8_decode($_POST['email'])."', '".$_POST['foto']."', '".$_POST['gsan']."', '".utf8_decode($_POST['obs'])."', '".$_POST['tlic']."', '".$_POST['nlic']."', '".$flic."', '".$_POST['auto']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	INSERTO EL NUEVO EMPLEADO
			$codempleado=getCodigo("mastempleado", "CodEmpleado", 6);
			$sql="INSERT INTO mastempleado (CodEmpleado, CodPersona, CodTipoTrabajador, CodMotivoCes, CodTipoPago, CodPerfil, CodCargo, CodDependencia, CodOrganismo, Fingreso, SueldoActual, CodTipoNom, Fegreso, Estado, ObsCese, CodCarnetProv, UltimoUsuario, UltimaFecha) VALUES ('$codempleado', '$codpersona', '".$_POST['ttra']."', '".$_POST['tcese']."', '".$_POST['tpago']."', '".$_POST['pnom']."', '".$_POST['cargo']."', '".$_POST['dependencia']."', '".$_POST['organismo']."', '".$fingreso."', '".$sueldo."', '".$_POST['tnom']."', '".$fcese."', '".$_POST['sittra']."', '".$_POST['explicacion']."', '".$codcarnet."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	INSERTO A LA TABLA DE CONTRATOS
			$sql="INSERT INTO rh_contratos VALUES ('$codpersona', '', '".$_POST['organismo']."', '', '', '', '', '', '', 'N', '', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	INSERTO EL HISTORIAL
			$sql="SELECT mp.CodPersona, me.Fingreso, me.Estado, me.Fegreso, me.ObsCese, mo.Organismo, md.Dependencia, tn.Nomina, rp.DescripCargo, rp.NivelSalarial, mmd.Descripcion AS CategoriaCargo, mtp.TipoPago, rtt.TipoTrabajador, rmc.MotivoCese FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) INNER JOIN rh_tipotrabajador rtt ON (me.CodTipoTrabajador=rtt.CodTipoTrabajador) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastmiscelaneosdet mmd ON (rp.CategoriaCargo=mmd.CodDetalle AND mmd.CodMaestro='CATCARGO') INNER JOIN masttipopago mtp ON (me.CodTipoPago=mtp.CodTipoPago) LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) WHERE mp.CodPersona='".$codpersona."'";
			$query_datos=mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_datos)!=0) $field=mysql_fetch_array($query_datos);
			//	-------------------------
			$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_historial", $persona);
			$sql="INSERT INTO rh_historial (CodPersona, Secuencia, Periodo, Fingreso, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoPago, TipoTrabajador, Estado, MotivoCese, Fegreso, ObsCese, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$periodo."', '".$fingreso."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CategoriaCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoPago'])."', '".utf8_decode($field['TipoTrabajador'])."', '".$field['Estado']."', '".utf8_decode($field['MotivoCese'])."', '".$field['Fegreso']."', '".utf8_decode($field['ObsCese'])."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	INSERTO NIVELACION
			$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleadonivelacion", $codpersona);
			$sql="INSERT INTO rh_empleadonivelacion (CodPersona, Secuencia, CodOrganismo, Fecha, CodDependencia, CodCargo, CodTipoNom, Responsable, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$organismo."', '".$fingreso."', '".$dependencia."', '".$cargo."', '".$tnom."', '".$_SESSION["CODPERSONA_ACTUAL"]."', '".$sittra."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	INSERTO HISTORIAL DE NIVELACION
			$sql="SELECT ren.CodPersona, mp.NomCompleto AS Responsable, me.CodEmpleado, me.Fingreso, me.Fegreso, me.ObsCese, me.Estado, mo.Organismo, md.Dependencia, tn.Nomina, rp.DescripCargo, rp.NivelSalarial, mmd2.Descripcion AS CatCargo, mtp.TipoPago, rmc.MotivoCese FROM rh_empleadonivelacion ren INNER JOIN mastpersonas mp ON (ren.Responsable=mp.CodPersona) INNER JOIN mastempleado me ON (ren.CodPersona=me.CodPersona) INNER JOIN masttipopago mtp ON (me.CodTipoPago=mtp.CodTipoPago) LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastmiscelaneosdet mmd2 ON (rp.CategoriaCargo=mmd2.CodDetalle AND mmd2.CodMaestro='CATCARGO') WHERE ren.CodPersona='".$codpersona."' AND ren.Secuencia='".$secuencia."'";
			$query_datos=mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_datos)!=0) $field=mysql_fetch_array($query_datos);
			//	-------------------------
			$sql="INSERT INTO rh_empleadonivelacionhistorial (CodPersona, Secuencia, Fecha, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, Responsable, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '$fingreso', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CatCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['Responsable'])."', 'A', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpersonas WHERE Ndocumento='".$_POST['ndoc']."' AND CodPersona<>'".$_POST['persona']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO LA PERSONA
			$sql="UPDATE mastpersonas SET Apellido1='".utf8_decode($apellido1)."', Apellido2='".utf8_decode($apellido2)."', Nombres='".utf8_decode($nombres)."', Busqueda='".utf8_decode($busqueda)."', Nacionalidad='".$_POST['nac']."', NomCompleto='".utf8_decode($ncompleto)."', EstadoCivil='".$_POST['edocivil']."', Sexo='".$_POST['sexo']."', Fnacimiento='".$fnac."', CiudadNacimiento='".$_POST['ciudad1']."', FedoCivil='".$fedocivil."', Direccion='".utf8_decode($dir)."', Telefono1='".$_POST['tel1']."', Telefono2='".$_POST['tel2']."', CiudadDomicilio='".$_POST['ciudad2']."', Fax='".$_POST['tel3']."', Lnacimiento='".$_POST['lnac']."', NomEmerg1='".$nomcon1."', DirecEmerg1='".utf8_decode($dircon1)."', TelefEmerg1='".$_POST['telcon1']."', DocFiscal='".$_POST['rif']."', Estado='".$_POST['statusreg']."', Ndocumento='".$_POST['ndoc']."', EsCliente='N', CelEmerg1='".$_POST['celcon1']."', EsProveedor='N', ParentEmerg1='".$_POST['parent1']."', NomEmerg2='".utf8_decode($nomcon2)."', EsEmpleado='S', EsOtros='N', DirecEmerg2='".utf8_decode($dircon2)."', TelefEmerg2='".$_POST['telcon2']."', CelEmerg2='".$_POST['celcon2']."', SituacionDomicilio='".$_POST['sitdom']."', ParentEmerg2='".$_POST['parent2']."', TipoDocumento='".$_POST['tdoc']."', Email='".utf8_decode($_POST['email'])."', Foto='".$_POST['foto']."', GrupoSanguineo='".$_POST['gsan']."', Observacion='".utf8_decode($_POST['obs'])."', TipoLicencia='".$_POST['tlic']."', Nlicencia='".$_POST['nlic']."', ExpiraLicencia='".$flic."', SiAuto='".$_POST['auto']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	ACTUALIZO EL EMPLEADO
			$sql="UPDATE mastempleado SET CodTipoTrabajador='".$_POST['ttra']."', CodMotivoCes='".$_POST['tcese']."', CodTipoPago='".$_POST['tpago']."', CodPerfil='".$_POST['pnom']."', CodCargo='".$_POST['cargo']."', CodDivision='".$_POST['division']."', CodDependencia='".$_POST['dependencia']."', CodOrganismo='".$_POST['organismo']."', Fingreso='".$fingreso."', CodTipoNom='".$_POST['tnom']."', Fegreso='".$fcese."', Estado='".$_POST['sittra']."', ObsCese='".$_POST['explicacion']."', CodCarnetProv='".$codcarnet."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."' AND CodEmpleado='".$_POST['empleado']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			if (($sittra!=$hsittra) || ($tpago!=$htpago) || ($ttra!=$httra) || ($organismo!=$horganismo) || ($dependencia!=$hdependencia)) {
				//	INSERTO EL HISTORIAL
				$sql="SELECT mp.CodPersona, me.Fingreso, me.Estado, me.Fegreso, me.ObsCese, mo.Organismo, md.Dependencia, tn.Nomina, rp.DescripCargo, rp.NivelSalarial, mmd.Descripcion AS CategoriaCargo, mtp.TipoPago, rtt.TipoTrabajador, rmc.MotivoCese FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) INNER JOIN rh_tipotrabajador rtt ON (me.CodTipoTrabajador=rtt.CodTipoTrabajador) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastmiscelaneosdet mmd ON (rp.CategoriaCargo=mmd.CodDetalle AND mmd.CodMaestro='CATCARGO') INNER JOIN masttipopago mtp ON (me.CodTipoPago=mtp.CodTipoPago) LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) WHERE mp.CodPersona='".$persona."'";
				$query_datos=mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_datos)!=0) $field=mysql_fetch_array($query_datos);
				//	-------------------------
				$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_historial", $persona);
				$sql="INSERT INTO rh_historial (CodPersona, Secuencia, Periodo, Fingreso, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoPago, TipoTrabajador, Estado, MotivoCese, Fegreso, ObsCese, UltimoUsuario, UltimaFecha) VALUES ('".$persona."', '".$secuencia."', '".$periodo."', '".$fingreso."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CategoriaCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoPago'])."', '".utf8_decode($field['TipoTrabajador'])."', '".$sittra."', '".utf8_decode($field['MotivoCese'])."', '".$field['Fegreso']."', '".utf8_decode($field['ObsCese'])."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM bancopersona WHERE CodPersona='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM mastbancos WHERE CodPersona='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM rh_cargafamiliar WHERE CodPersona='".$_POST['codigo']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else {
					$sql="SELECT * FROM rh_contratos WHERE CodPersona='".$_POST['codigo']."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
					else $error=0;
				}
			}
		}
	}
	elseif ($_POST['accion']=="LNAC") {
		$sql="SELECT mastciudades.Ciudad, mastmunicipios.Municipio, mastestados.Estado FROM mastciudades, mastmunicipios, mastestados WHERE mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastciudades.CodCiudad='".$_POST['ciudad']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) { $field=mysql_fetch_array($query); $lnac="lnac=$field[0]lnac=$field[2]"; $error="0$lnac"; }
	}
	echo $error;
}

//	MAESTRO DE PERSONAS
elseif ($_POST['modulo']=="PERSONAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$apellido1=strtoupper($_POST['apellido1']);
	$apellido2=strtoupper($_POST['apellido2']);
	$nombres=strtoupper($_POST['nombres']);
	$busqueda=strtoupper($_POST['busqueda']);
	$nom_completo=strtoupper($_POST['nom_completo']);
	$dir=strtoupper($_POST['dir']);
	$nomcon1=strtoupper($_POST['nomcon1']);
	$dircon1=strtoupper($_POST['dircon1']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fnac']); $fnac=$annio.$mes.$dia;
	if ($_POST['cpersona']=="J") { $apellido1=""; $apellido2=""; $nomcon1=""; $dircon2=""; }
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpersonas WHERE Ndocumento='".$_POST['ndoc']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NRO. DE DOCUMENTO EXISTENTE";
		else {
			$codpersona=getCodigo("mastpersonas", "CodPersona", 6);
			//	INSERTO LA NUEVA PERSONA
			$sql="INSERT INTO mastpersonas VALUES ('$codpersona', '".utf8_decode($apellido1)."', '".utf8_decode($apellido2)."', '".utf8_decode($nombres)."', '".utf8_decode($busqueda)."', '', '".utf8_decode($nom_completo)."', '".$_POST['edocivil']."', '".$_POST['sexo']."', '".$fnac."', '', '', '".$dir."', '".$_POST['tel1']."', '".$_POST['tel2']."', '".$_POST['ciudad2']."', '".$_POST['tel3']."', '', '".utf8_decode($nomcon1)."', '".utf8_decode($dircon1)."', '', '".$_POST['rif']."', '".$_POST['cpersona']."', '".$_POST['status']."', '".$_POST['ndoc']."', '".$_POST['escliente']."', '', '".$_POST['esproveedor']."', '', '', '".$_POST['esempleado']."', '".$_POST['esotro']."', '', '', '', '', '', '".$_POST['tdoc']."', '".$_POST['email']."', '', '', '', '', '', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	INSERTO LA INFORMACION BANCARIA
			$secuencia=getCodigo("bancopersona", "CodSecuencia", 6);
			$sql="INSERT INTO bancopersona VALUES ('$secuencia', '".$_POST['banco']."', '".$codpersona."', '".$_POST['tcta']."', '".$_POST['ncta']."', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora', 'S')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpersonas WHERE Ndocumento='".$_POST['ndoc']."' AND CodPersona<>'".$_POST['persona']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO LA PERSONA
			$sql="UPDATE mastpersonas SET Apellido1='".utf8_decode($apellido1)."', Apellido2='".utf8_decode($apellido2)."', Nombres='".utf8_decode($nombres)."', Busqueda='".utf8_decode($busqueda)."', NomCompleto='".utf8_decode($nom_completo)."', EstadoCivil='".$_POST['edocivil']."', Sexo='".$_POST['sexo']."', Fnacimiento='".$fnac."', Direccion='".$dir."', Telefono1='".$_POST['tel1']."', Telefono2='".$_POST['tel2']."', CiudadDomicilio='".$_POST['ciudad2']."', Fax='".$_POST['tel3']."', NomEmerg1='".utf8_decode($nomcon1)."', DirecEmerg1='".utf8_decode($dircon1)."', DocFiscal='".$_POST['rif']."', TipoPersona='".$_POST['cpersona']."', Estado='".$_POST['status']."', Ndocumento='".$_POST['ndoc']."', EsCliente='".$_POST['escliente']."', EsProveedor='".$_POST['esproveedor']."', EsEmpleado='".$_POST['esempleado']."', EsOtros='".$_POST['esotro']."', TipoDocumento='".$_POST['tdoc']."', Email='".$_POST['email']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			//	-------------------------------
			//	Consulto para verificar si ya existe en la tabla bancopersona
			$sql="SELECT CodPersona FROM bancopersona WHERE CodPersona='".$persona."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				//	Actualizo en bancopersona
				$sql="UPDATE bancopersona SET CodBanco='$banco', TipoCuenta='$tcta', Ncuenta='$ncta', UltimoUsuario='".$_SESSION["SESION_USUARIO"]."', UltimaFecha='$ahora' WHERE CodPersona='".$persona."' AND FlagPrincipal='S'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	Inserto en bancopersona
				$secuencia=generarCodigo("CodSecuencia", "bancopersona", 6);
				$sql="INSERT INTO bancopersona (CodSecuencia, CodBanco, CodPersona, TipoCuenta, Ncuenta, FlagPrincipal, UltimoUsuario, UltimaFecha) VALUES ('$secuencia', '$banco', '$codpersona', '$tcta', '$ncta', 'S', '".$_SESSION["SESION_USUARIO"]."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			
			if ($tabproveedor = "S") {
				list($dia, $mes, $annio)=SPLIT( '[/.-]', $fconstitucion); $fconstitucion=$annio.$mes.$dia;
				list($dia, $mes, $annio)=SPLIT( '[/.-]', $femision); $femision=$annio.$mes.$dia;
				list($dia, $mes, $annio)=SPLIT( '[/.-]', $fvalidacion); $fvalidacion=$annio.$mes.$dia;
				
				if ($actualizo == 0) {
					$sql = "INSERT INTO mastproveedores (CodProveedor,
															CodTipoDocumento,
															CodTipoPago,
															CodFormaPago,
															CodTipoServicio,
															DiasPago,
															RegistroPublico,
															LicenciaMunicipal,
															FechaConstitucion,
															RepresentanteLegal,
															ContactoVendedor,
															FlagSNC,
															NroInscripcionSNC,
															FechaEmisionSNC,
															FechaValidacionSNC,
															Nacionalidad,
															UltimoUsuario,
															UltimaFecha)
													VALUES ('".$persona."',
															'".$docproveedor."',
															'".$docpago."',
															'".$formapago."',
															'".$servicio."',
															'".$dias."',
															'".$registro."',
															'".$licencia."',
															'".$fconstitucion."',
															'".$representante."',
															'".$contacto."',
															'".$flagsnc."',
															'".$nrosnc."',
															'".$femision."',
															'".$fvalidacion."', 
															'".$flagnacionalidad."', 
															'".$_SESSION["SESION_USUARIO"]."', 
															'$ahora')";
					$query=mysql_query($sql) or die ($sql.mysql_error());
				} else {
					$sql = "UPDATE mastproveedores SET CodTipoDocumento = '".$docproveedor."',
														CodFormaPago = '".$docpago."',
														CodTipoServicio = '".$servicio."',
														DiasPago = '".$dias."',
														RegistroPublico = '".$registro."',
														LicenciaMunicipal = '".$licencia."',
														FechaConstitucion = '".$fconstitucion."',
														RepresentanteLegal = '".$representante."',
														ContactoVendedor = '".$contacto."',
														FlagSNC = '".$flagsnc."',
														NroInscripcionSNC = '".$nrosnc."',
														FechaEmisionSNC = '".$femision."',
														FechaValidacionSNC = '".$fvalidacion."', 
														Nacionalidad = '".$flagnacionalidad."', 
														UltimoUsuario = '".$_SESSION["SESION_USUARIO"]."', 
														UltimaFecha = '$ahora'
													WHERE CodProveedor = '".$persona."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
				}
			}
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI LA PERSONA ES UN EMPLEADO
		$sql="SELECT EsEmpleado FROM mastpersonas WHERE CodPersona='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	{ 
			$field=mysql_fetch_array($query);
			if ($field[0]=="S") $error="NO SE PUEDE ELIMINAR EL EMPLEADO";
		} else {
			//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
			$sql="SELECT * FROM bancopersona WHERE CodPersona='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else {
				$sql="SELECT * FROM mastbancos WHERE CodPersona='".$_POST['codigo']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
				else {
					$sql="SELECT * FROM mastempleado WHERE CodPersona='".$_POST['codigo']."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
					else {
						$sql="SELECT * FROM rh_cargafamiliar WHERE CodPersona='".$_POST['codigo']."'";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						$rows=mysql_num_rows($query);
						if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
						else {
							$sql="SELECT * FROM rh_contratos WHERE CodPersona='".$_POST['codigo']."'";
							$query=mysql_query($sql) or die ($sql.mysql_error());
							$rows=mysql_num_rows($query);
							if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
							else $error=0;
						}
					}
				}
			}
		}
	}
	echo $error;
}

//	INFORMACION BANCARIA
elseif ($_POST['modulo']=="BANCARIA") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$sql="SELECT * FROM bancopersona WHERE (CodPersona='".$_POST['persona']."' AND CodBanco='".$_POST['banco']."' AND TipoCuenta='".$_POST['tcta']."' AND Ncuenta='".$_POST['cuenta']."' AND Aportes='".$_POST['tapo']."') OR (CodPersona='".$_POST['persona']."' AND Aportes='".$_POST['tapo']."') OR (CodPersona='".$_POST['persona']."' AND FlagPrincipal='".$_POST['principal']."')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$secuencia=getCodigo("bancopersona", "CodSecuencia", 6);
				$sql="INSERT INTO bancopersona VALUES ('$secuencia', '".$_POST['banco']."', '".$_POST['persona']."', '".$_POST['tcta']."', '".$_POST['cuenta']."', '".$_POST['tapo']."', '".$_POST['monto']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora', '".$_POST['principal']."')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		} else {
			$sql="SELECT * FROM bancopersona WHERE ((CodPersona='".$_POST['persona']."' AND CodBanco='".$_POST['banco']."' AND TipoCuenta='".$_POST['tcta']."' AND Ncuenta='".$_POST['cuenta']."' AND Aportes='".$_POST['tapo']."') OR (CodPersona='".$_POST['persona']."' AND Aportes='".$_POST['tapo']."') OR (CodPersona='".$_POST['persona']."' AND FlagPrincipal='".$_POST['principal']."')) AND CodSecuencia<>'$secuencia'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$sql="UPDATE bancopersona SET CodBanco='".$_POST['banco']."', TipoCuenta='".$_POST['tcta']."', Ncuenta='".$_POST['cuenta']."', Aportes='".$_POST['tapo']."', Monto='".$_POST['monto']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora', FlagPrincipal='".$_POST['principal']."' WHERE CodSecuencia='".$_POST['secuencia']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
}

//	DOCUMENTOS
elseif ($_POST['modulo']=="DOCUMENTO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fentrega']); $fentrega=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fvence']); $fvence=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$sql="SELECT * FROM rh_empleado_documentos WHERE (CodPersona='".$_POST['registro']."' AND (Documento='".$_POST['doc']."' AND CargaFamiliar='".$_POST['familiar']."'	))";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$secuencia=getSecuencia("CodDocumento", "CodPersona", "rh_empleado_documentos", $_POST['registro']);
				$sql="INSERT INTO rh_empleado_documentos VALUES ('$secuencia', '".$_POST['registro']."', '".$_POST['doc']."', '".$_POST['flagpresento']."', '".$fentrega."', '".$fvence."', '".$_POST['flagfamiliar']."', '".$_POST['familiar']."', '".$_POST['observacion']."', '".$_POST['archivo']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		} else {
			$sql="SELECT * FROM rh_empleado_documentos WHERE (CodPersona='".$_POST['registro']."' AND (Documento='".$_POST['doc']."' AND CargaFamiliar='".$_POST['familiar']."'	)) AND (CodDocumento<>'".$_POST['secuencia']."')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$sql="UPDATE rh_empleado_documentos SET Documento='".$_POST['doc']."', FlagPresento='".$_POST['flagpresento']."', FechaPresento='".$fentrega."', FechaVence='".$fvence."', FlagCarga='".$_POST['flagfamiliar']."', CargaFamiliar='".$_POST['familiar']."', Observaciones='".$_POST['observacion']."', Ruta='".$_POST['archivo']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE (CodPersona='".$_POST['registro']."' AND CodDocumento='".$_POST['secuencia']."')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
}

//	MOVIMIENTOS DE DOCUMENTOS
elseif ($_POST['modulo']=="MOVIMIENTO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fentrega']); $fentrega=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fstatus']); $festado=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$sql="SELECT * FROM rh_documentos_historia WHERE (CodPersona='".$_POST['registro']."' AND CodDocumento='".$_POST['documento']."' AND Estado='E')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="ESTE DOCUMENTO SE ENCUENTRA EN ESTADO ENTREGADO";
			else {
				$secuencia=getSecuencia2("Secuencia", "CodPersona", "CodDocumento", "rh_documentos_historia", $_POST['registro'], $_POST['documento']);
				$sql="INSERT INTO rh_documentos_historia VALUES ('".$_POST['documento']."', '".$_POST['registro']."', '$secuencia', '".$_POST['codpersona']."', '".$_POST['status']."', '".$fentrega."', '".$festado."', '".$_POST['obsentrega']."', '".$_POST['obsestado']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		} else {
			$sql="UPDATE rh_documentos_historia  SET Estado='".$_POST['status']."', FechaDevuelto='".$festado."', ObsEntrega='".$_POST['obsentrega']."', ObsDevuelto='".$_POST['obsestado']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
}

//	EXPERIENCIA LABORAL
elseif ($_POST['modulo']=="EXPERIENCIA") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$empresa=strtoupper($_POST['empresa']);
	$cargo=strtoupper($_POST['cargo']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleado_experiencia", $_POST['persona']);
			$sql="INSERT INTO rh_empleado_experiencia VALUES ('".$_POST['persona']."', '$secuencia', '".$empresa."', '".$fdesde."', '".$fhasta."', '".$_POST['mcese']."', '".$_POST['sueldo']."', '".$_POST['area']."', '".$_POST['ente']."', '".$cargo."', '".$_POST['funciones']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		} else {
			$sql="UPDATE rh_empleado_experiencia SET Empresa='".$empresa."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', MotivoCese='".$_POST['mcese']."', Sueldo='".$_POST['sueldo']."', AreaExperiencia='".$_POST['area']."', TipoEnte='".$_POST['ente']."', CargoOcupado='".$cargo."', Funciones='".$_POST['funciones']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."' AND Secuencia='".$_POST['secuencia']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
}

//	MAESTRO DE CONTRATOS
elseif ($_POST['modulo']=="CONTRATOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['vcontratod']); $vcond=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['vcontratoh']); $vconh=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['firma']); $firma=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="NUEVO") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_contratos WHERE CodPersona='".$_POST['persona']."' AND CodOrganismo='".$_POST['organismo']."' AND FechaDesde='".$_POST['vcontratod']."' AND FechaHasta='".$_POST['vcontratoh']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$secuencia=getCodigo("rh_contratos", "Secuencia", 4);
			//	ACTUALIZO LA TABLA CONTRATOS
			$sql="UPDATE rh_contratos SET Secuencia='$secuencia', CodOrganismo='".$_POST['organismo']."', TipoContrato='".$_POST['tcon']."', CodFormato='".$_POST['fcon']."', FechaDesde='".$vcond."', FechaHasta='".$vconh."', Estado='".$_POST['status']."', Comentarios='".$_POST['coment']."', FlagFirma='".$_POST['flagfirma']."', FechaFirma='".$firma."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	ACTUALIZO LA TABLA CONTRATOS
		$sql="UPDATE rh_contratos SET TipoContrato='".$_POST['tcon']."', CodFormato='".$_POST['fcon']."', FechaDesde='".$vcond."', FechaHasta='".$vconh."', Estado='".$_POST['status']."', Comentarios='".$_POST['coment']."', FlagFirma='".$_POST['flagfirma']."', FechaFirma='".$firma."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
}

//	CARGA FAMILIAR
elseif ($_POST['modulo']=="FAMILIARES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$nombres=strtoupper($_POST['nombres']);
	$apellidos=strtoupper($_POST['apellidos']);
	$dirfam=strtoupper($_POST['dirfam']);
	$empresa=strtoupper($_POST['empresa']);
	$diremp=strtoupper($_POST['diremp']);
	$comentarios=strtoupper($_POST['comentarios']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fnac']); $fnac=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fbaja']); $fbaja=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="NUEVO") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargafamiliar WHERE Ndocumento<>'' AND Ndocumento='".$_POST['ndoc']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NRO. DOCUMENTO DE IDENTIDAD EXISTENTE";
		else {
			$secuencia=getCodigo("rh_cargafamiliar", "CodSecuencia", 6);
			//	INSERTO EN  LA TABLA CARGAFAMILIAR
			$sql="INSERT INTO rh_cargafamiliar (CodSecuencia, CodPersona, Parentesco, Sexo, ApellidosCarga, NombresCarga, FechaNacimiento, TipoDocumento, Ndocumento, DireccionFam, GrupoSanguineo, Afiliado, Telefono, Celular, MotivoBaja, FechaBaja, Estado, CodGradoInstruccion, TipoEducacion, CodCentroEstudio, FlagTrabaja, Empresa, DireccionEmpresa, TiempoServicio, SueldoMensual, Comentarios, UltimoUsuario, UltimaFecha) VALUES ('$secuencia', '".$_POST['persona']."', '".$_POST['parent']."', '".$_POST['sexo']."', '$apellidos', '$nombres', '$fnac', '".$_POST['tdoc']."', '".$_POST['ndoc']."', '".$dirfam."', '".$_POST['gsan']."', '".$_POST['afiliado']."', '".$_POST['tel']."', '".$_POST['cel']."', '".$_POST['mbaja']."', '".$fbaja."', '".$_POST['status']."', '".$_POST['instruccion']."', '".$_POST['educacion']."', '".$_POST['centroeduc']."', '".$_POST['trabaja']."', '".$empresa."', '".$diremp."', '".$_POST['tservicio']."', '".$_POST['sueldo']."', '".$comentarios."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_cargafamiliar WHERE Ndocumento<>'' AND Ndocumento='".$_POST['ndoc']."' AND CodSecuencia<>'".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO LA TABLA CONTRATOS
			$sql="UPDATE rh_cargafamiliar SET Parentesco='".$_POST['parent']."', Sexo='".$_POST['sexo']."', ApellidosCarga='".$apellidos."', NombresCarga='".$nombres."', FechaNacimiento='".$fnac."', TipoDocumento='".$_POST['tdoc']."', Ndocumento='".$_POST['ndoc']."', DireccionFam='".$dirfam."', GrupoSanguineo='".$_POST['gsan']."', Afiliado='".$_POST['afiliado']."', Telefono='".$_POST['tel']."', Celular='".$_POST['cel']."', MotivoBaja='".$_POST['mbaja']."', FechaBaja='".$fbaja."', Estado='".$_POST['status']."', CodGradoInstruccion='".$_POST['instruccion']."', TipoEducacion='".$_POST['educacion']."', CodCentroEstudio='".$_POST['centroeduc']."', FlagTrabaja='".$_POST['trabaja']."', Empresa='".$empresa."', DireccionEmpresa='".$diremp."', TiempoServicio='".$_POST['tservicio']."', SueldoMensual='".$_POST['sueldo']."', Comentarios='".$comentarios."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodSecuencia='".$_POST['registro']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	MAESTRO DE GRADO DE INSTRUCCION
elseif ($_POST['modulo']=="GINSTRUCCION") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcion=strtoupper($_POST['descripcion']);
	$elemento=strtoupper($_POST['elemento']);
	$detalle=strtoupper($_POST['detalle']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_gradoinstruccion WHERE CodGradoInstruccion='".$codigo."' OR Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_gradoinstruccion (CodGradoInstruccion, Descripcion, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_gradoinstruccion WHERE CodGradoInstruccion<>'".$codigo."' AND Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_gradoinstruccion SET Descripcion='$descripcion', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodGradoInstruccion='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="INSERTAR") {
		if ($_POST['helemento']=="") {
			$sql="SELECT * FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$codigo."' AND (Nivel='".$elemento."' OR Descripcion='$detalle'))";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$sql="INSERT INTO rh_nivelgradoinstruccion (CodGradoInstruccion, Nivel, Descripcion, UltimoUsuario, UltimaFecha) VALUES ('".$codigo."', '".$elemento."', '".$detalle."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		} else {
			$sql="SELECT * FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$codigo."' AND Nivel<>'".$_POST['helemento']."' AND (Nivel='".$elemento."' OR Descripcion='$detalle'))";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="REGISTRO EXISTENTE";
			else {
				$sql="UPDATE rh_nivelgradoinstruccion SET Nivel='".$elemento."', Descripcion='".$detalle."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodGradoInstruccion='".$_POST['codigo']."' AND Nivel='".$_POST['helemento']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
	elseif ($_POST['accion']=="EDITAR") {		
		$sql="SELECT * FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$codigo."' AND Nivel='".$elemento."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		echo $error."elemento=".$field['Nivel']."elemento=".$field['Descripcion'];
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_cargafamiliar WHERE CodGradoInstruccion='$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="SELECT * FROM rh_cargoformacion WHERE CodGradoInstruccion='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
			else $error=0;
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINARELEMENTO") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		/*
		$sql="SELECT * FROM mastpersonas WHERE EstadoCivil='".$_POST['elemento']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
		*/
	}
}

//	MAESTRO DE CENTRO DE ESTUDIO
elseif ($_POST['modulo']=="CESTUDIO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	$ubicacion=strtoupper($_POST['ubicacion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_centrosestudios WHERE Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo = getCodigo("rh_centrosestudios", "CodCentroEstudio", 3);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_centrosestudios (CodCentroEstudio, Descripcion, Ubicacion, FlagEstudio, FlagCurso, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$descripcion', '$ubicacion', '$estudio', '$curso', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_centrosestudios WHERE Descripcion='$descripcion' AND CodCentroEstudio<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_centrosestudios SET Descripcion='$descripcion', FlagEstudio='$estudio', FlagCurso='$curso', Ubicacion='$ubicacion', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCentroEstudio='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_cargafamiliar WHERE CodCentroEstudio='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE IDIOMAS
elseif ($_POST['modulo']=="IDIOMAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcionl=strtoupper($_POST['descripcionl']);
	$descripcione=strtoupper($_POST['descripcione']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastidioma WHERE CodIdioma='$codigo' OR DescripcionLocal='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO mastidioma (CodIdioma, DescripcionLocal, DescripcionExtra, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$descripcionl', '$descripcione', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastidioma WHERE CodIdioma<>'$codigo' AND DescripcionLocal='$descripcionl'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE mastidioma SET DescripcionLocal='$descripcionl', DescripcionExtra='$descripcione', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodIdioma='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_cargoidioma WHERE CodIdioma='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE PROFESIONES
elseif ($_POST['modulo']=="PROFESIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_profesiones WHERE Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$codigo=getCodigo("rh_profesiones", "CodProfesion", 6);
			$sql="INSERT INTO rh_profesiones (CodProfesion, Area, CodGradoInstruccion, Descripcion, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".$_POST['area']."', '".$_POST['grado']."', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_profesiones WHERE Descripcion='$descripcion' AND CodProfesion<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_profesiones SET Descripcion='$descripcion', Area='".$_POST['area']."', CodGradoInstruccion='".$_POST['grado']."', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodProfesion='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
	}
	echo $error;
}

//	MAESTRO DE ESTUDIOS
elseif ($_POST['modulo']=="ESTUDIO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fgraduacion']); $fgraduacion=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		//	INSERTO EL NUEVO REGISTRO
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleado_instruccion", $_POST['persona']);
		$sql="INSERT INTO rh_empleado_instruccion (CodPersona, Secuencia, CodGradoInstruccion, Area, CodProfesion, Nivel, CodCentroEstudio, FechaDesde, FechaHasta, Colegiatura, NroColegiatura, Observaciones, FechaGraduacion, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '".$secuencia."', '".$_POST['grado']."', '".$_POST['area']."', '".$_POST['profesion']."', '".$_POST['nivel']."', '".$_POST['codcentro']."', '".$fdesde."', '".$fhasta."', '".$_POST['colegiatura']."', '".$_POST['ncolegiatura']."', '".$_POST['observaciones']."', '".$fgraduacion."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo 0;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		$sql="UPDATE rh_empleado_instruccion SET CodGradoInstruccion='".$_POST['grado']."', Area='".$_POST['area']."', CodProfesion='".$_POST['profesion']."', Nivel='".$_POST['nivel']."', CodCentroEstudio='".$_POST['codcentro']."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', Colegiatura='".$_POST['colegiatura']."', NroColegiatura='".$_POST['ncolegiatura']."', Observaciones='".$_POST['observaciones']."', FechaGraduacion = '".$fgraduacion."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='".$ahora."' WHERE CodPersona='".$_POST['persona']."' AND Secuencia='".$_POST['secuencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());		
	}
	elseif ($_POST['accion']=="IDIOMA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_empleado_idioma WHERE CodPersona='".$_POST['persona']."' AND CodIdioma='".$_POST['idioma']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE IDIOMA";
		else {
			$error=0;
			$sql="INSERT INTO rh_empleado_idioma (CodPersona, CodIdioma, NivelLectura, NivelOral, NivelEscritura, NivelGeneral, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '".$_POST['idioma']."', '".$_POST['lectura']."', '".$_POST['oral']."', '".$_POST['escritura']."', '".$_POST['general']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}
elseif ($_POST['modulo']=="INSTRUCCION_CURSOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		//	INSERTO EL NUEVO REGISTRO
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleado_cursos", $_POST['persona']);
		$sql="INSERT INTO rh_empleado_cursos (CodPersona, Secuencia, CodCurso, TipoCurso, CodCentroEstudio, FechaDesde, FechaHasta, FechaCulminacion, TotalHoras, AniosVigencia, Observaciones, FlagInstitucional, FlagPago, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '".$secuencia."', '".$_POST['codcurso']."', '".$_POST['tcurso']."', '".$_POST['codcentro']."', '".$fdesde."', '".$fhasta."', '".$fculminacion."', '".$_POST['horas']."', '".$_POST['anios']."', '".$_POST['observaciones']."', '".$_POST['flag']."', '".$_POST['flagpago']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo 0;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		$sql="UPDATE rh_empleado_cursos SET CodCurso='".$_POST['codcurso']."', TipoCurso='".$_POST['tcurso']."', CodCentroEstudio='".$_POST['codcentro']."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', FechaCulminacion='".$fculminacion."', TotalHoras='".$_POST['horas']."', AniosVigencia='".$_POST['anios']."', Observaciones='".$_POST['observaciones']."', FlagInstitucional='".$_POST['flag']."', FlagPago='".$_POST['flagpago']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."' AND Secuencia='".$_POST['secuencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());		
	}
	echo $error;
}

//	MAESTRO DE CURSOS
elseif ($_POST['modulo']=="CURSOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_cursos WHERE Descripcion='$descripcion' AND AreaCurso='".$_POST['area']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_cursos", "CodCurso", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_cursos VALUES ('$codigo', '".$_POST['area']."', '$descripcion', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_cursos WHERE (Descripcion='$descripcion' AND AreaCurso='".$_POST['area']."') AND CodCurso<>'".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_cursos SET Descripcion='$descripcion', AreaCurso='".$_POST['area']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodCurso='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM mastmunicipios WHERE CodEstado='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else $error=0;
	}
	echo $error;
}

//	MAESTRO DE MERITOS
elseif ($_POST['modulo']=="MERITOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdoc']); $fdoc=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="MERITOS") {		
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_meritosfaltas", $_POST["persona"]);
		//	INSERTO EL NUEVO REGISTRO
		$sql="INSERT INTO rh_meritosfaltas VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['doc']."', '$fdoc', '".$_POST['obs']."', '".$_POST['merito']."', '".$_POST['responsable']."', '".$_POST['tipo']."', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="DEMERITOS") {
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_meritosfaltas", $_POST["persona"]);
		//	INSERTO EL NUEVO REGISTRO
		$sql="INSERT INTO rh_meritosfaltas VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['doc']."', '$fdoc', '".$_POST['obs']."', '".$_POST['merito']."', '".$_POST['responsable']."', '".$_POST['tipo']."', '$fdesde', '$fhasta', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	MAESTRO DE REFERENCIAS DEL EMPLEADO
elseif ($_POST['modulo']=="REFERENCIAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleado_referencias", $_POST["persona"]);
	//	INSERTO EL NUEVO REGISTRO
	$sql="INSERT INTO rh_empleado_referencias VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['nombre']."', '".$_POST['empresa']."', '".$_POST['dir']."', '".$_POST['tel']."', '".$_POST['cargo']."', '".$_POST['tipo']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}

//	MAESTRO DE PERMISOS DEL EMPLEADO
elseif ($_POST['modulo']=="PERMISOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$annio=date("Y");
	$mes=date("m");
	$periodo=date("Y-m");
	list($d, $m, $a)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$a.$m.$d;
	list($d, $m, $a)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$a.$m.$d;
	list($h, $m, $s)=SPLIT( '[:]', $_POST['hdesde']); if ($turnodesde=="PM") $h=$h+12; $hdesde=$h.":".$m;
	list($h, $m, $s)=SPLIT( '[:]', $_POST['hhasta']); if ($turnohasta=="PM") $h=$h+12; $hhasta=$h.":".$m;
	//
	if ($_POST["accion"]=="GUARDAR") {
		$correlativo=getCorrelativo("rh_permisos", "CodPermiso", $annio, 6);
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_permisos", $_POST["codempleado"]);
		//	INSERTO EL NUEVO REGISTRO
		$sql="INSERT INTO rh_permisos VALUES ('$correlativo', '".$_POST['codempleado']."', '$secuencia', '$ahora', '".$_POST['tfalta']."', '".$_POST['tpermiso']."', '".$fdesde."', '".$fhasta."', '".$hdesde."', '".$hhasta."', '".$_POST['periodo']."', '".$_POST['observaciones']."', '".$_POST['remunerado']."', '".$_POST['justificativo']."', '".$flagexento."', '".$_POST['codaprueba']."', '', '', '".$_POST['status']."', '$dias', '$horas', '$minutos', '$tfecha', '$ttiempo', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST["accion"]=="ACTUALIZAR") {
		//	INSERTO EL NUEVO REGISTRO
		$sql="UPDATE rh_permisos SET CodPersona='".$_POST['codempleado']."', TipoFalta='".$_POST['tfalta']."', TipoPermiso='".$_POST['tpermiso']."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', HoraDesde='".$hdesde."', HoraHasta='".$hhasta."', PeriodoContable='".$_POST['periodo']."', ObsMotivo='".$_POST['observaciones']."', FlagRemunerado='".$_POST['remunerado']."', FlagJustificativo='".$_POST['justificativo']."', FlagExento='".$flagexento."', Aprobador='".$_POST['codaprueba']."', Estado='".$_POST['status']."', TotalHoras = '".$horas."', TotalDias = '".$dias."', TotalMinutos = '".$minutos."', TotalFecha = '".$tfecha."', TotalTiempo = '".$ttiempo."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPermiso='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST["accion"]=="ANULAR") {
		$error="";
		$sql="SELECT Estado FROM rh_permisos WHERE CodPermiso='$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		if ($field[0]=="A") $status="P";
		else if ($field[0]=="P") $status="N";
		else $error="NO SE PUEDE ANULAR ESTE PERMISO";
		if ($error=="") {
			//	ACTUALIZO
			$sql="UPDATE rh_permisos SET FlagRemunerado='S', FlagJustificativo='N', FechaAprobado='', Estado='$status', ObsAprobado='', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPermiso='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		} else echo $error;
	}
	elseif ($_POST["accion"]=="APROBAR") {
		if ($codaprueba != $_SESSION["CODPERSONA_ACTUAL"]) $error = "¡EL USUARIO ACTUAL NO PUEDE APROBAR ESTE PERMISO!";
		else {
			//	ACTUALIZO
			$sql="UPDATE rh_permisos SET FlagRemunerado='".$_POST['remunerado']."', FlagJustificativo='".$_POST['justificativo']."', FechaAprobado='$ahora', Estado='".$_POST['status']."', ObsAprobado='".$_POST['obsaprobado']."', FlagExento='".$flagexento."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPermiso='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST["accion"]=="RECHAZAR") {
		//	ACTUALIZO
		$sql="UPDATE rh_permisos SET FlagRemunerado='', FlagJustificativo='', FechaAprobado='$ahora', Estado='".$_POST['status']."', ObsAprobado='".$_POST['obsaprobado']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPermiso='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	REGISTRO DE POSTULANTES
elseif ($_POST['modulo']=="POSTULANTES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$anio=date("Y");
	$nombres=strtoupper($_POST['nombres']);
	$apellido1=strtoupper($_POST['apellido1']);
	$apellido2=strtoupper($_POST['apellido2']);	
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fnac']); $fnac=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fedocivil']); $fedocivil=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_postulantes WHERE Ndocumento='".$ndoc."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NRO. DE DOCUMENTO EXISTENTE";
		else {
			$postulante=getCodigo("rh_postulantes", "Postulante", 6);
			$expediente=$anio.$postulante;
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_postulantes (Postulante, Apellido1, Apellido2, Nombres, ResumenEjec, CiudadNacimiento, CiudadDomicilio, Fnacimiento, Sexo, Direccion, Referencia, Email, Telefono1, FechaRegistro, Expediente, TipoDocumento, Ndocumento, Estado, EstadoCivil, FedoCivil, GrupoSanguineo, SituacionDomicilio, InformacionAdic, FlagBeneficas, Beneficas, FlagLaborales, Laborales, FlagCulturales, Culturales, FlagDeportivas, Deportivas, FlagReligiosas, Religiosas, FlagSociales, Sociales, UltimoUsuario, UltimaFecha) VALUES ('$postulante', '$apellido1', '$apellido2', '$nombres', '$resumen', '$ciudad', '$ciudad2', '$fnac', '$sexo', '$dir', '$referencia', '$email', '$tel', '$ahora', '$expediente', '$tdoc', '$ndoc', '$status', '$edocivil', '$fedocivil', '$gsang', '$sitdom', '$obs', '$fbeneficas', '$beneficas', '$flaborales', '$laborales', '$fculturales', '$culturales', '$fdeportivas', '$deportivas', '$freligiosas', '$religiosas', '$fsociales', '$sociales', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$error="0"."|.|".$postulante;
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_postulantes WHERE Ndocumento='".$ndoc."' AND Postulante<>'".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_postulantes SET Apellido1='$apellido1', Apellido2='$apellido2', Nombres='$nombres', ResumenEjec='$resumen', CiudadNacimiento='$ciudad', CiudadDomicilio='$ciudad2', Fnacimiento='$fnac', Sexo='$sexo', Direccion='$dir', Referencia='$referencia', Email='$email', Telefono1='$tel', TipoDocumento='$tdoc', Ndocumento='$ndoc', Estado='$status', EstadoCivil='$edocivil', FedoCivil='$fedocivil', GrupoSanguineo='$gsang', SituacionDomicilio='$sitdom', InformacionAdic='$obs', FlagBeneficas='$fbeneficas', Beneficas='$beneficas', FlagLaborales='$flaborales', Laborales='$laborales', FlagCulturales='$fculturales', Culturales='$culturales', FlagDeportivas='$fdeportivas', Deportivas='$deportivas', FlagReligiosas='$freligiosas', Religiosas='$religiosas', FlagSociales='$fsociales', Sociales='$sociales', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Postulante='".$postulante."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$error=0;		
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_postulantes_cargos WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_cursos WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_documentos WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_experiencia WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_idioma WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_informat WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_instruccion WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		
		$sql="SELECT * FROM rh_postulantes_referencias WHERE Postulante='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
	}
	elseif ($_POST['accion']=="IDIOMA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_postulantes_idioma WHERE Postulante='".$_POST['postulante']."' AND CodIdioma='".$_POST['idioma']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="YA SE INGRESO ESTE IDIOMA";
		else {
			$error=0;
			$sql="INSERT INTO rh_postulantes_idioma (Postulante, CodIdioma, NivelLectura, NivelOral, NivelEscritura, NivelGeneral, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['postulante']."', '".$_POST['idioma']."', '".$_POST['lectura']."', '".$_POST['oral']."', '".$_POST['escritura']."', '".$_POST['general']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="INFORMATICA") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_postulantes_informat WHERE Postulante='".$_POST['postulante']."' AND Informatica='".$_POST['curso']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="YA SE INGRESO ESTE CURSO";
		else {
			$error=0;
			$sql="INSERT INTO rh_postulantes_informat (Postulante, Informatica, Nivel, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['postulante']."', '".$_POST['curso']."', '".$_POST['nivel']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="CARGOS") {
		//	CONSULTO SI EL REGISTRO EXISTE
		$sql="SELECT * FROM rh_postulantes_cargos WHERE Postulante='".$_POST['postulante']."' AND CodOrganismo='".$_POST['organismo']."' AND CodCargo='".$_POST['cargo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="CARGO YA INGRESADO";
		else {
			$error=0;
			$secuencia=getSecuencia("Secuencia", "Postulante", "rh_postulantes_cargos", $_POST['postulante']);
			$sql="INSERT INTO rh_postulantes_cargos (Postulante, Secuencia, CodOrganismo, CodCargo, Comentario, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['postulante']."', '$secuencia', '".$_POST['organismo']."', '".$_POST['cargo']."', '".$_POST['comentario']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}
elseif ($_POST['modulo']=="POSTULANTES_INSTRUCCION") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		//	INSERTO EL NUEVO REGISTRO
		$secuencia=getSecuencia("Secuencia", "Postulante", "rh_postulantes_instruccion", $postulante);
		$sql="INSERT INTO rh_postulantes_instruccion (Postulante, Secuencia, CodGradoInstruccion, Area, CodProfesion, Nivel, CodCentroEstudio, FechaDesde, FechaHasta, Colegiatura, NroColegiatura, Observaciones, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['postulante']."', '".$secuencia."', '".$_POST['grado']."', '".$_POST['area']."', '".$_POST['profesion']."', '".$_POST['nivel']."', '".$_POST['codcentro']."', '".$fdesde."', '".$fhasta."', '".$_POST['colegiatura']."', '".$_POST['ncolegiatura']."', '".$_POST['observaciones']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo 0;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		$sql="UPDATE rh_postulantes_instruccion SET CodGradoInstruccion='".$_POST['grado']."', Area='".$_POST['area']."', CodProfesion='".$_POST['profesion']."', Nivel='".$_POST['nivel']."', CodCentroEstudio='".$_POST['codcentro']."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', Colegiatura='".$_POST['colegiatura']."', NroColegiatura='".$_POST['ncolegiatura']."', Observaciones='".$_POST['observaciones']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='".$ahora."' WHERE Postulante='".$_POST['postulante']."' AND Secuencia='".$_POST['secuencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());		
	}
	echo $error;
}
elseif ($_POST['modulo']=="POSTULANTES_CURSOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		//	INSERTO EL NUEVO REGISTRO
		$secuencia=getSecuencia("Secuencia", "Postulante", "rh_postulantes_cursos", $_POST['postulante']);
		$sql="INSERT INTO rh_postulantes_cursos (Postulante, Secuencia, CodCurso, TipoCurso, CodCentroEstudio, FechaDesde, FechaHasta, TotalHoras, AniosVigencia, Observaciones, FlagInstitucional, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['postulante']."', '".$secuencia."', '".$_POST['codcurso']."', '".$_POST['tcurso']."', '".$_POST['codcentro']."', '".$fdesde."', '".$fhasta."', '".$_POST['horas']."', '".$_POST['anios']."', '".$_POST['observaciones']."', '".$_POST['flag']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo 0;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		$sql="UPDATE rh_postulantes_cursos SET CodCurso='".$_POST['codcurso']."', TipoCurso='".$_POST['tcurso']."', CodCentroEstudio='".$_POST['codcentro']."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', TotalHoras='".$_POST['horas']."', AniosVigencia='".$_POST['anios']."', Observaciones='".$_POST['observaciones']."', FlagInstitucional='".$_POST['flag']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Postulante='".$_POST['postulante']."' AND Secuencia='".$_POST['secuencia']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());		
	}
	echo $error;
}
elseif ($_POST['modulo']=="POSTULANTE_EXPERIENCIA") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$empresa=strtoupper($_POST['empresa']);
	$cargo=strtoupper($_POST['cargo']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$secuencia=getSecuencia("Secuencia", "Postulante", "rh_postulantes_experiencia", $_POST['postulante']);
			$sql="INSERT INTO rh_postulantes_experiencia VALUES ('".$_POST['postulante']."', '$secuencia', '".$empresa."', '".$fdesde."', '".$fhasta."', '".$_POST['mcese']."', '".$_POST['sueldo']."', '".$_POST['area']."', '".$_POST['ente']."', '".$cargo."', '".$_POST['funciones']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		} else {
			$sql="UPDATE rh_postulantes_experiencia SET Empresa='".$empresa."', FechaDesde='".$fdesde."', FechaHasta='".$fhasta."', MotivoCese='".$_POST['mcese']."', Sueldo='".$_POST['sueldo']."', AreaExperiencia='".$_POST['area']."', TipoEnte='".$_POST['ente']."', CargoOcupado='".$cargo."', Funciones='".$_POST['funciones']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Postulante='".$_POST['postulante']."' AND Secuencia='".$_POST['secuencia']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
}
elseif ($_POST['modulo']=="POSTULANTES_REFERENCIAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	$secuencia=getSecuencia("Secuencia", "Postulante", "rh_postulantes_referencias", $_POST["postulante"]);
	//	INSERTO EL NUEVO REGISTRO
	$sql="INSERT INTO rh_postulantes_referencias VALUES ('".$_POST['postulante']."', '$secuencia', '".$_POST['nombre']."', '".$_POST['empresa']."', '".$_POST['dir']."', '".$_POST['tel']."', '".$_POST['cargo']."', '".$_POST['tipo']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
elseif ($_POST['modulo']=="POSTULANTES_DOCUMENTOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion']=="INSERTAR") {
		if ($_POST['secuencia']=="") {
			$secuencia=getSecuencia("Secuencia", "Postulante", "rh_postulantes_documentos", $_POST['registro']);
			$sql="INSERT INTO rh_postulantes_documentos VALUES ('$secuencia', '".$_POST['registro']."', '".$_POST['doc']."', '".$_POST['flagpresento']."', '".$_POST['observacion']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		} else {
			$sql="UPDATE rh_postulantes_documentos SET Documento='".$_POST['doc']."', FlagPresento='".$_POST['flagpresento']."', Observaciones='".$_POST['observacion']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE (Postulante='".$_POST['registro']."' AND Secuencia='".$_POST['secuencia']."')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
}

//	PATRIMONIO DEL EMPLEADO
elseif ($_POST['modulo']=="PATRIMONIO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion']=="INMUEBLE") {
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_patrimonio_inmueble", $_POST['persona']);
		$sql="INSERT INTO rh_patrimonio_inmueble (CodPersona, Secuencia, Descripcion, Ubicacion, Uso, Valor, FlagHipotecado, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['descripcion']."', '".$_POST['ubicacion']."', '".$_POST['uso']."', '".$_POST['valor']."', '".$_POST['flaghipoteca']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="INVERSION") {
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_patrimonio_inversion", $_POST['persona']);
		$sql="INSERT INTO rh_patrimonio_inversion (CodPersona, Secuencia, Titular, EmpresaRemitente, NroCertificado, Cantidad, ValorNominal, Valor, FlagGarantia, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['titular']."', '".$_POST['empresa']."', '".$_POST['certificado']."', '".$_POST['cant']."', '".$_POST['valorn']."', '".$_POST['valor']."', '".$_POST['flaggarantia']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="VEHICULO") {
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_patrimonio_vehiculo", $_POST['persona']);
		$sql="INSERT INTO rh_patrimonio_vehiculo (CodPersona, Secuencia, Marca, Modelo, Anio, Color, Placa, Valor, FlagPrendado, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['marca']."', '".$_POST['modelo']."', '".$_POST['anio']."', '".$_POST['color']."', '".$_POST['placa']."', '".$_POST['valor']."', '".$_POST['flagprendado']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="CUENTA") {
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_patrimonio_cuenta", $_POST['persona']);
		$sql="INSERT INTO rh_patrimonio_cuenta (CodPersona, Secuencia, TipoCuenta, Institucion, NroCuenta, Valor, FlagGarantia, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['tipo']."', '".$_POST['institucion']."', '".$_POST['cta']."', '".$_POST['valor']."', '".$_POST['flaggarantia']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="OTRO") {
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_patrimonio_cuenta", $_POST['persona']);
		$sql="INSERT INTO rh_patrimonio_otro (CodPersona, Secuencia, Descripcion, ValorCompra, Valor, FlagGarantia, UltimoUsuario, UltimaFecha) VALUES ('".$_POST['persona']."', '$secuencia', '".$_POST['descripcion']."', '".$_POST['valorc']."', '".$_POST['valor']."', '".$_POST['flaggarantia']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	MAESTRO DE PREGUNTAS
elseif ($_POST['modulo']=="PREGUNTAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_encuesta_preguntas WHERE Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_encuesta_preguntas", "Pregunta", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_encuesta_preguntas VALUES ('$codigo', '$descripcion', '$area', '$minimo', '$maximo', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_encuesta_preguntas WHERE Descripcion='$descripcion' AND Pregunta<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_encuesta_preguntas SET Descripcion='$descripcion', Area='$area', ValorMinimo='$minimo', ValorMaximo='$maximo', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_encuesta_plantillas_det WHERE Pregunta='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
	}
	echo $error;
}

//	MAESTRO DE PLANTILLAS
elseif ($_POST['modulo']=="PLANTILLAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$descripcion=strtoupper($_POST['descripcion']);
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_encuesta_plantillas WHERE Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_encuesta_plantillas", "Plantilla", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_encuesta_plantillas VALUES ('$codigo', '$descripcion', '".$_POST['status']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error."|:|".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_encuesta_plantillas WHERE Descripcion='$descripcion' AND Plantilla<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_encuesta_plantillas SET Descripcion='$descripcion', Estado='".$_POST['status']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Plantilla='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error."|:|".$codigo;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_encuesta_plantillas_det WHERE Plantilla='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		echo $error;
	}	
}

//	MAESTRO DE ENCUESTAS
elseif ($_POST['modulo']=="ENCUESTAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$titulo=strtoupper($_POST['titulo']);
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fecha']); $fecha=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_encuestas WHERE Titulo='$titulo' AND PeriodoContable='$periodo' AND Fecha='$fecha'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_encuestas", "Secuencia", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_encuestas (CodOrganismo, Secuencia, PeriodoContable, Titulo, Fecha, Observaciones, Muestra, UltimoUsuario, UltimaFecha) VALUES ('$organismo', '$codigo', '$periodo', '$titulo', '$fecha', '$obs', '$muestra', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error."|:|".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_encuestas WHERE Titulo='$titulo' AND PeriodoContable='$periodo' AND Fecha='$fecha' AND Secuencia<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_encuestas SET CodOrganismo='$organismo', Secuencia='$codigo', PeriodoContable='$periodo', Titulo='$titulo', Fecha='$fecha', Observaciones='$obs', Muestra='$muestra', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Secuencia='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error."|:|".$codigo;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_encuesta_detalle WHERE Secuencia='".$_POST['codigo']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		echo $error;
	}
}

//	REQUERIMIENTOS
elseif ($_POST['modulo']=="REQUERIMIENTOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fsolicitud']); $fsolicitud=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['ddesde']); $ddesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['dhasta']); $dhasta=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['vdesde']); $vdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['vhasta']); $vhasta=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		$codigo=getSecuencia("Requerimiento", "CodOrganismo", "rh_requerimiento", $organismo);
		$codigo=(string) str_repeat("0", 6-strlen($codigo)).$codigo;
		//	INSERTO EL NUEVO REGISTRO
		$sql="INSERT INTO rh_requerimiento (Requerimiento, FechaSolicitud, NumeroSolicitado, NumeroPendiente, CodDivision, CodDependencia, CodOrganismo, CodPersona, Modalidad, VigenciaInicio, VigenciaFin, CodCargo, Motivo, TipoContrato, FechaDesde, FechaHasta, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$fsolicitud', '$vacantes', '$vacantes', '$division', '$dependencia', '$organismo', '$codempleado', '$modalidad', '$vdesde', '$vhasta', '$cargo', '$motivo', '$contrato', '$ddesde', '$dhasta', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	INSERTO TODOS LAS EVALUACIONES QUE ESTAN EN RH_CARGOEVALUACION RH_EVALREQUERIMIENTO
		$sql="SELECT Secuencia, Evaluacion, Etapa FROM rh_cargoevaluacion WHERE CodCargo='$cargo'";
		$query_cargo=mysql_query($sql) or die ($sql.mysql_error());
		while ($field_cargo=mysql_fetch_array($query_cargo)) {
			$sql="INSERT INTO rh_requerimientoeval (Requerimiento, CodOrganismo, Secuencia, Evaluacion, Etapa, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$organismo', '".$field_cargo['Secuencia']."', '".$field_cargo['Evaluacion']."', '".$field_cargo['Etapa']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query_eval=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error.":".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		if ($status=="T") $error="NO SE PUEDE MODIFICAR UN REQUERIMIENTO CUANDO ESTA TERMINADO";
		else {
			//	ACTUALIZO EL REGISTRO
			$sql="UPDATE rh_requerimiento SET FechaSolicitud='$fsolicitud', NumeroSolicitado='$vacantes', NumeroPendiente='$pendientes', CodDivision='$division', CodDependencia='$dependencia', CodPersona='$codempleado', Modalidad='$modalidad', VigenciaInicio='$vdesde', VigenciaFin='$vhasta', CodCargo='$cargo', Motivo='$motivo', TipoContrato='$contrato', FechaDesde='$ddesde', FechaHasta='$dhasta', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Requerimiento='$codigo' AND CodOrganismo='$organismo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="EVALUACION") {
		$secuencia=getSecuencia2("Secuencia", "Requerimiento", "CodOrganismo", "rh_requerimientoeval", $requerimiento, $organismo);
		//	INSERTO EN EVALUACION
		$sql="INSERT INTO rh_requerimientoeval (Requerimiento, CodOrganismo, Secuencia, Evaluacion, Etapa, PlantillaEvaluacion, UltimoUsuario, UltimaFecha) VALUES ('$requerimiento', '$organismo', '$secuencia', '$evaluacion', '$etapa', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	CONSULTO
		$sql="SELECT * FROM rh_requerimientopost WHERE Requerimiento='".$requerimiento."' AND CodOrganismo='".$organismo."' GROUP BY Postulante";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$sql="INSERT INTO rh_requerimientoevalpost (Requerimiento, CodOrganismo, Postulante, Secuencia, Calificativo, Descripcion, FlagAprobacion, UltimoUsuario, UltimaFecha) VALUES ('$requerimiento', '$organismo', '".$field['Postulante']."', '$secuencia', '', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query2=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
	elseif ($_POST['accion']=="APROBAR") {
		list($requerimiento, $codorganismo)=SPLIT( '[/.-]', $registro);
		//	ACTUALIZO
		$sql="UPDATE rh_requerimiento SET Estado='A', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Requerimiento='$requerimiento' AND CodOrganismo='".$codorganismo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="EVALREQUERIMIENTO") {
		//	ELIMINO DE RH_REQUERIMIENTOEVAL TODOS LAS EVALUACIONES DEL REQUERIMIENTO PARA DESPUES INSERTAR LAS NUEVAS
		$sql="DELETE FROM rh_requerimientoeval WHERE Requerimiento='".$requerimiento."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	INSERTO TODOS LAS EVALUACIONES QUE ESTAN EN RH_CARGOEVALUACION RH_EVALREQUERIMIENTO
		$sql="SELECT Secuencia, Evaluacion, Etapa FROM rh_cargoevaluacion WHERE CodCargo='$cargo'";
		$query_cargo=mysql_query($sql) or die ($sql.mysql_error());
		while ($field_cargo=mysql_fetch_array($query_cargo)) {
			$sql="INSERT INTO rh_requerimientoeval (Requerimiento, CodOrganismo, Secuencia, Evaluacion, Etapa, UltimoUsuario, UltimaFecha) VALUES ('$requerimiento', '$organismo', '".$field_cargo['Secuencia']."', '".$field_cargo['Evaluacion']."', '".$field_cargo['Etapa']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query_eval=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="COMPETENCIAS") {
		$puntaje = explode(";", $checks);
		$i=0;
		foreach ($puntaje as $item) {
			list($p, $c)=SPLIT( '[:]', $item);
			$punto[$i]=$p;
			$competencia[$i]=$c;
			$i++;
		}
		$sql="DELETE FROM rh_requerimientocomp WHERE Requerimiento='$registro' AND CodOrganismo='$organismo' AND Postulante='$postulante' AND Evaluacion='$evaluacion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		for ($j=0; $j<$i-1; $j++) {
			$sql="INSERT INTO rh_requerimientocomp (Requerimiento, CodOrganismo, Postulante, Evaluacion, Competencia, Puntaje, UltimoUsuario, UltimaFecha) VALUES ('$registro', '$organismo', '$postulante', '$evaluacion', '$competencia[$j]', '$punto[$j]', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		//	---------------------
		$sql="SELECT AVG(Puntaje) AS Puntaje FROM rh_requerimientocomp WHERE Requerimiento='$registro' AND CodOrganismo='$organismo' AND Postulante='$postulante' AND Evaluacion='$evaluacion'";
		$query_calificativo=mysql_query($sql) or die ($sql.mysql_error());
		$field_calificativo=mysql_fetch_array($query_calificativo);
		//	
		$sql="UPDATE rh_requerimientoevalpost SET Calificativo='".$field_calificativo['Puntaje']."' WHERE Requerimiento='$registro' AND CodOrganismo='$organismo' AND Postulante='$postulante' AND Secuencia=(SELECT Secuencia FROM rh_requerimientoeval WHERE Requerimiento='$registro' AND CodOrganismo='$organismo' AND Evaluacion='$evaluacion')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$sql="SELECT SUM(Calificativo) AS Puntaje FROM rh_requerimientoevalpost WHERE Requerimiento='$registro' AND CodOrganismo='$organismo' AND Postulante='$postulante'";
		$query_calificativo=mysql_query($sql) or die ($sql.mysql_error());
		$field_calificativo=mysql_fetch_array($query_calificativo);
		//	
		$sql="UPDATE rh_requerimientopost SET Puntaje='".$field_calificativo['Puntaje']."' WHERE Requerimiento='$registro' AND CodOrganismo='$organismo' AND Postulante='$postulante'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$sql="UPDATE rh_requerimiento SET Estado='E' WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$registro."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR-CANDIDATOS") {
		$sql="DELETE FROM rh_requerimientocomp WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$sql="DELETE FROM rh_requerimientoevalpost WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$sql="DELETE FROM rh_requerimientopost WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="APROBAR-CANDIDATOS") {
		$sql="UPDATE rh_requerimientopost SET Estado='A' WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$sql="UPDATE rh_requerimientoevalpost SET FlagAprobacion='S' WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$cod_postulante=substr($postulante, 1, 6);
		$sql="UPDATE rh_postulantes SET Estado='A' WHERE Postulante='".$cod_postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="REPROBAR-CANDIDATOS") {
		$sql="UPDATE rh_requerimientopost SET Estado='D' WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$sql="UPDATE rh_requerimientoevalpost SET FlagAprobacion='N' WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."' AND Postulante='".$postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------
		$cod_postulante=substr($postulante, 1, 6);
		$sql="UPDATE rh_postulantes SET Estado='P' WHERE Postulante='".$cod_postulante."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion']=="CONTRATAR-EMPLEADOS") {
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fnac']); $fnac=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fedocivil']); $fedocivil=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['flic']); $flic=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fingreso']); $fingreso=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fcese']); $fcese=$annio.$mes.$dia;
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpersonas WHERE Ndocumento='".$_POST['ndoc']."' AND CodPersona<>'".$_POST['persona']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	CONSULTO SI EXISTEN VACANTES DISPONIBLES
			$sql="SELECT NumeroPendiente AS Disponibles FROM rh_requerimiento WHERE Requerimiento='".$requerimiento."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$field=mysql_fetch_array($query);
			if ($field['Disponibles']==0) $error="NO EXISTEN VACANTES DISPONIBLES PARA ESTE REQUERIMIENTO";
			else {
				$ncompleto=$nombres." ".$apellido1." ".$apellido2;
				//	ACTUALIZO LA PERSONA
				$sql="UPDATE mastpersonas SET Apellido1='".utf8_decode($apellido1)."', Apellido2='".utf8_decode($apellido2)."', Nombres='".utf8_decode($nombres)."', Busqueda='".utf8_decode($busqueda)."', Nacionalidad='".$_POST['nac']."', NomCompleto='".utf8_decode($ncompleto)."', EstadoCivil='".$_POST['edocivil']."', Sexo='".$_POST['sexo']."', Fnacimiento='".$fnac."', CiudadNacimiento='".$_POST['ciudad1']."', FedoCivil='".$fedocivil."', Direccion='".utf8_decode($dir)."', Telefono1='".$_POST['tel1']."', Telefono2='".$_POST['tel2']."', CiudadDomicilio='".$_POST['ciudad2']."', Fax='".$_POST['tel3']."', Lnacimiento='".$_POST['lnac']."', NomEmerg1='".$nomcon1."', DirecEmerg1='".utf8_decode($dircon1)."', TelefEmerg1='".$_POST['telcon1']."', DocFiscal='".$_POST['rif']."', Estado='".$_POST['statusreg']."', Ndocumento='".$_POST['ndoc']."', EsCliente='N', CelEmerg1='".$_POST['celcon1']."', EsProveedor='N', ParentEmerg1='".$_POST['parent1']."', NomEmerg2='".utf8_decode($nomcon2)."', EsEmpleado='S', EsOtros='N', DirecEmerg2='".utf8_decode($dircon2)."', TelefEmerg2='".$_POST['telcon2']."', CelEmerg2='".$_POST['celcon2']."', SituacionDomicilio='".$_POST['sitdom']."', ParentEmerg2='".$_POST['parent2']."', TipoDocumento='".$_POST['tdoc']."', Email='".utf8_decode($_POST['email'])."', Foto='".$_POST['foto']."', GrupoSanguineo='".$_POST['gsan']."', Observacion='".utf8_decode($_POST['obs'])."', TipoLicencia='".$_POST['tlic']."', Nlicencia='".$_POST['nlic']."', ExpiraLicencia='".$flic."', SiAuto='".$_POST['auto']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO EL EMPLEADO
				$sql="UPDATE mastempleado SET CodTipoTrabajador='".$_POST['ttra']."', CodMotivoCes='".$_POST['tcese']."', CodTipoPago='".$_POST['tpago']."', CodPerfil='".$_POST['pnom']."', CodCargo='".$_POST['cargo']."', CodDivision='".$_POST['division']."', CodDependencia='".$_POST['dependencia']."', CodOrganismo='".$_POST['organismo']."', Fingreso='".$fingreso."', CodTipoNom='".$_POST['tnom']."', Fegreso='".$fcese."', Estado='".$_POST['sittra']."', ObsCese='".$_POST['explicacion']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."' AND CodEmpleado='".$_POST['empleado']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO LA TABLA DE CONTRATOS
				$sql="UPDATE rh_contratos SET CodOrganismo='".$_POST['organismo']."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$_POST['persona']."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO LA TABLA DE REQUERIMIENTOS
				$sql="UPDATE rh_requerimiento SET NumeroPendiente=NumeroPendiente-1 WHERE Requerimiento='".$requerimiento."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO LA TABLA DE REQUERIMIENTOS - POSTULANTES
				$sql="UPDATE rh_requerimientopost SET Estado='C' WHERE Requerimiento='".$requerimiento."' AND CodOrganismo='".$org."' AND Postulante='".$postulante."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO LA TABLA DE POSTULANTES
				$cod_postulante=substr($postulante, 1, 6);
				$sql="UPDATE rh_postulantes SET Estado='C' WHERE Postulante='".$cod_postulante."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
	elseif ($_POST['accion']=="CONTRATAR-CANDIDATOS") {
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fnac']); $fnac=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fedocivil']); $fedocivil=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['flic']); $flic=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fingreso']); $fingreso=$annio.$mes.$dia;
		list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fcese']); $fcese=$annio.$mes.$dia;
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM mastpersonas WHERE Ndocumento='".$_POST['ndoc']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="NRO. DE DOCUMENTO EXISTENTE";
		else {
			//	CONSULTO SI EXISTEN VACANTES DISPONIBLES
			$sql="SELECT NumeroPendiente AS Disponibles FROM rh_requerimiento WHERE Requerimiento='".$requerimiento."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$field=mysql_fetch_array($query);
			if ($field['Disponibles']==0) $error="NO EXISTEN VACANTES DISPONIBLES PARA ESTE REQUERIMIENTO";
			else {
				$codpersona=getCodigo("mastpersonas", "CodPersona", 6);
				$ncompleto=$nombres." ".$apellido1." ".$apellido2;
				//	INSERTO LA NUEVA PERSONA
				$sql="INSERT INTO mastpersonas VALUES ('$codpersona', '".utf8_decode($apellido1)."', '".utf8_decode($apellido2)."', '".utf8_decode($nombres)."', '".utf8_decode($busqueda)."', '".$_POST['nac']."', '".utf8_decode($ncompleto)."', '".$_POST['edocivil']."', '".$_POST['sexo']."', '".$fnac."', '".$_POST['ciudad1']."', '".$fedocivil."', '".utf8_decode($dir)."', '".$_POST['tel1']."', '".$_POST['tel2']."', '".$_POST['ciudad2']."', '".$_POST['tel3']."', '".$_POST['lnac']."', '".utf8_decode($nomcon1)."', '".utf8_decode($dircon1)."', '".$_POST['telcon1']."', '".$_POST['rif']."', 'N', '".$_POST['statusreg']."', '".$_POST['ndoc']."', 'N', '".$_POST['celcon1']."', 'N', '".utf8_decode($_POST['parent1'])."', '".utf8_decode($nomcon2)."', 'S', 'N', '".utf8_decode($dircon2)."', '".$_POST['telcon2']."', '".$_POST['celcon2']."', '".$_POST['sitdom']."', '".utf8_decode($_POST['parent2'])."', '".$_POST['tdoc']."', '".utf8_decode($_POST['email'])."', '".$_POST['foto']."', '".$_POST['gsan']."', '".utf8_decode($_POST['obs'])."', '".$_POST['tlic']."', '".$_POST['nlic']."', '".$flic."', '".$_POST['auto']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	INSERTO EL NUEVO EMPLEADO
				$codempleado=getCodigo("mastempleado", "CodEmpleado", 6);
				$sql="INSERT INTO mastempleado VALUES ('$codempleado', '$codpersona', '', '".$_POST['ttra']."', '".$_POST['tcese']."', '".$_POST['tpago']."', '".$_POST['pnom']."', '".$_POST['cargo']."', '".$_POST['division']."', '".$_POST['dependencia']."', '".$_POST['organismo']."', '".$fingreso."', '', '', '".$_POST['tnom']."', '', '".$fcese."', '', '', '".$_POST['sittra']."', '".$_POST['explicacion']."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	INSERTO A LA TABLA DE CONTRATOS
				$sql="INSERT INTO rh_contratos VALUES ('$codpersona', '', '".$_POST['organismo']."', '', '', '', '', '', '', 'N', '', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO LA TABLA DE REQUERIMIENTOS
				$sql="UPDATE rh_requerimiento SET NumeroPendiente=NumeroPendiente-1 WHERE Requerimiento='".$requerimiento."'";
				$query=mysql_query($sql) or die ($sql.mysql_error()); echo $sql;
				//	ACTUALIZO LA TABLA DE REQUERIMIENTOS - POSTULANTES
				$sql="UPDATE rh_requerimientopost SET Estado='C' WHERE Requerimiento='".$requerimiento."' AND CodOrganismo='".$org."' AND Postulante='".$postulante."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				//	ACTUALIZO LA TABLA DE POSTULANTES
				$cod_postulante=substr($postulante, 1, 6);
				$sql="UPDATE rh_postulantes SET Estado='C' WHERE Postulante='".$cod_postulante."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
	elseif ($_POST['accion']=="TERMINAR-REQUERIMIENTO") {
		$sql="UPDATE rh_requerimiento SET Estado='T' WHERE CodOrganismo='".$organismo."' AND Requerimiento='".$requerimiento."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="VIGENCIA-CONTRATO") {
		$sql="SELECT FlagVencimiento FROM rh_tipocontrato WHERE TipoContrato='".$tipo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $field=mysql_fetch_array($query);
		echo $error.":".$field['FlagVencimiento'];
	}
}

//	CAPACITACION
elseif ($_POST['modulo']=="CAPACITACION") {
	connect();
	$error=0;
	$datos="";
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fdesde']); $fdesde=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fhasta']); $fhasta=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fecha']); $fecha=$annio.$mes.$dia;
	//
	if ($_POST['accion']=="GUARDAR") {
		$codigo=getSecuencia("Capacitacion", "CodOrganismo", "rh_capacitacion", $organismo);
		$codigo=(string) str_repeat("0", 6-strlen($codigo)).$codigo;
		//	INSERTO EL NUEVO REGISTRO
		$sql="INSERT INTO rh_capacitacion (Capacitacion, CodOrganismo, CodCurso, CodCentroEstudio, Vacantes, Estado, FechaDesde, FechaHasta, Participantes, FlagHorarioIndividual, Aula, TipoCapacitacion, Expositor, FlagLogistica, CodCiudad, CostoEstimado, Solicitante, TelefonoContacto, MontoMaxAsumido, MontoAsumido, Modalidad, TipoCurso, Fundamentacion1, Fundamentacion2, Fundamentacion3, Fundamentacion4, Fundamentacion5, Fundamentacion6, Fundamentacion7, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$organismo', '$codcurso', '$codcentro', '$vacantes', '$status', '$fdesde', '$fhasta', '$participantes', '', '$aula', '$tcapacitacion', '$expositor', '', '$ciudad', '$costo', '$codempleado', '$tel', '$asumido', '$utilizado', '$modalidad', '$tcurso', '$funda1', '$funda2', '$funda3', '$funda4', '$funda5', '$funda6', '$funda7', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die (mysql_error());
		echo $error.":".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	ACTUALIZO EL REGISTRO
		$sql="UPDATE rh_capacitacion SET CodCurso='$codcurso', CodCentroEstudio='$codcentro', Vacantes='$vacantes', Estado='$status', FechaDesde='$fdesde', FechaHasta='$fhasta', Participantes='$participantes', Aula='$aula', TipoCapacitacion='$tcapacitacion', Expositor='$expositor', CodCiudad='$ciudad', CostoEstimado='$costo', Solicitante='$codempleado', TelefonoContacto='$tel', MontoMaxAsumido='$asumido', MontoAsumido='$utilizado', Modalidad='$modalidad', TipoCurso='$tcurso', Fundamentacion1='$funda1', Fundamentacion2='$funda2', Fundamentacion3='$funda3', Fundamentacion4='$funda4', Fundamentacion5='$funda5', Fundamentacion6='$funda6', Fundamentacion7='$funda7', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Capacitacion='$codigo' AND CodOrganismo='".$organismo."'";
		$query=mysql_query($sql) or die (mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="APROBAR") {
		//	COMPRUEBO QUE INGRESO LOS HORARIOS
		$sql="SELECT * FROM rh_capacitacion_hora WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			//	ACTUALIZO
			$sql="UPDATE rh_capacitacion SET Estado='A', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		} else $error="DEBE INGRESAR EL HORARIO DE LA CAPACITACION";
		echo $error;
	}
	elseif ($_POST['accion']=="HORARIOS") {
		if ($sec=="") {
			$sql="SELECT Secuencia, CodPersona FROM rh_capacitacion_empleados WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."' ORDER BY Capacitacion, CodOrganismo, Secuencia";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					$sql="SELECT * FROM rh_capacitacion_hora WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."' AND Secuencia='".$field['Secuencia']."'";
					$query_comprobar=mysql_query($sql) or die ($sql.mysql_error());
					$rows_comprobar=mysql_num_rows($query_comprobar);
					if ($rows_comprobar!=0) $error="NO SE PUEDE INSERTAR OTRO HORARIO";
					else {
						//	COMPRUEBO QUE INGRESO LOS HORARIOS
						$sql="INSERT INTO rh_capacitacion_hora (Capacitacion, CodOrganismo, Secuencia, CodPersona, Estado, PeriodoInicio, PeriodoFin, FechaDesde, FechaHasta, Lunes, HoraInicioLunes, HoraFinLunes, Martes, HoraInicioMartes, HoraFinMartes, Miercoles, HoraInicioMiercoles, HoraFinMiercoles, Jueves, HoraInicioJueves, HoraFinJueves, Viernes, HoraInicioViernes, HoraFinViernes, Sabado, HoraInicioSabado, HoraFinSabado, Domingo, HoraInicioDomingo, HoraFinDomingo, TotalDias, TotalHoras, UltimoUsuario, UltimaFecha) VALUES ('$capacitacion', '$organismo', '".$field['Secuencia']."', '".$field['CodPersona']."', '$status', '', '', '$fdesde', '$fhasta', '$flunes', '$dlunes', '$hlunes', '$fmartes', '$dmartes', '$hmartes', '$fmiercoles', '$dmiercoles', '$hmiercoles', '$fjueves', '$djueves', '$hjueves', '$fviernes', '$dviernes', '$hviernes', '$fsabado', '$dsabado', '$hsabado', '$fdomingo', '$ddomingo', '$hdomingo', '', '', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
						$query_insert=mysql_query($sql) or die ($sql.mysql_error());
					}
				}
			} else $error="DEBE INGRESAR PRIMERO LOS PARTICIPANTES";
		} else {
			$sql="UPDATE rh_capacitacion_hora SET Estado='$status', PeriodoInicio='', PeriodoFin='', FechaDesde='$fdesde', FechaHasta='$fhasta', Lunes='$flunes', HoraInicioLunes='$dlunes', HoraFinLunes='$hlunes', Martes='$fmartes', HoraInicioMartes='$dmartes', HoraFinMartes='$hmartes', Miercoles='$fmiercoles', HoraInicioMiercoles='$dmiercoles', HoraFinMiercoles='$hmiercoles', Jueves='$fjueves', HoraInicioJueves='$djueves', HoraFinJueves='$hjueves', Viernes='$fviernes', HoraInicioViernes='$dviernes', HoraFinViernes='$hviernes', Sabado='$fsabado', HoraInicioSabado='$dsabado', HoraFinSabado='$hsabado', Domingo='$fdomingo', HoraInicioDomingo='$ddomingo', HoraFinDomingo='$hdomingo', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
			$query_update=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="HORARIOS_EDITAR") {
		$sql="SELECT * FROM rh_capacitacion_hora WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."' GROUP BY Capacitacion, CodOrganismo";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$datos=$field['Estado']."|:|".$field['FechaDesde']."|:|".$field['FechaHasta']."|:|".$field['Lunes']."|:|".$field['HoraInicioLunes']."|:|".$field['HoraFinLunes']."|:|".$field['Martes']."|:|".$field['HoraInicioMartes']."|:|".$field['HoraFinMartes']."|:|".$field['Miercoles']."|:|".$field['HoraInicioMiercoles']."|:|".$field['HoraFinMiercoles']."|:|".$field['Jueves']."|:|".$field['HoraInicioJueves']."|:|".$field['HoraFinJueves']."|:|".$field['Viernes']."|:|".$field['HoraInicioViernes']."|:|".$field['HoraFinViernes']."|:|".$field['Sabado']."|:|".$field['HoraInicioSabado']."|:|".$field['HoraFinSabado']."|:|".$field['Domingo']."|:|".$field['HoraInicioDomingo']."|:|".$field['HoraFinDomingo']."|:|".$field['TotalDias']."|:|".$field['TotalHoras'];
		} else $error="NO SE ENCONTRO EL REGISTRO EN LA BASE DE DATOS";
		echo $error."|:|".$datos;
	}
	elseif ($_POST['accion']=="GASTOS") {
		if ($sec=="") {
			$sql="SELECT * FROM rh_capacitacion_gastos WHERE Capacitacion='".$capacitacion."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="NO SE PUEDE INGRESAR OTRO GASTO";
			else {
				$secuencia=getSecuencia("Secuencia", "Capacitacion", "rh_capacitacion_gastos", $capacitacion);
				$sql="INSERT INTO rh_capacitacion_gastos (Capacitacion, Secuencia, Numero, Fecha, SubTotal, Impuestos, Total, UltimoUsuario, UltimaFecha) VALUES ('$capacitacion', '$secuencia', '$numero', '$fecha', '$subtotal', '$impuesto', '$total', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		} else {
			$sql="UPDATE rh_capacitacion_gastos SET Numero='$numero', Fecha='$fecha', SubTotal='$subtotal', Impuestos='$impuesto', Total='$total', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Capacitacion='".$capacitacion."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="GASTOS_EDITAR") {
		$sql="SELECT * FROM rh_capacitacion_gastos WHERE Capacitacion='".$capacitacion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) {
			$field=mysql_fetch_array($query);
			$datos=$field['Numero']."|:|".$field['Fecha']."|:|".$field['SubTotal']."|:|".$field['Impuestos']."|:|".$field['Total'];
		} else $error="NO SE ENCONTRO EL REGISTRO EN LA BASE DE DATOS";
		echo $error."|:|".$datos;
	}
	elseif ($_POST['accion']=="INICIAR") {
		if ($estado=="Iniciar"){
			$sql="SELECT Estado FROM rh_capacitacion WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				if ($field['Estado']=="A") {
					$sql="SELECT * FROM rh_capacitacion_gastos WHERE Capacitacion='".$capacitacion."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					if ($rows!=0) {
						$sql="UPDATE rh_capacitacion SET Estado='I', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
						$query=mysql_query($sql) or die ($sql.mysql_error());
					} else $error="DEBE INGRESAR LOS GASTOS DE LA CAPACITACION";
				}
				else $error="LA CAPACITACION NO SE PUEDE INICIAR SI NO HA SIDO APROBADA";
			} else $error="NO SE ENCONTRO ESTA CAPACITACION EN LA BASE DE DATOS";
		}
		elseif ($estado=="Terminar") {
			$sql="SELECT Estado FROM rh_capacitacion WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				if ($field['Estado']=="I") {
					$sql="UPDATE rh_capacitacion SET Estado='T', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
				}
				else $error="LA CAPACITACION NO SE PUEDE TERMINAR SI NO HA SIDO INICIADA";
			} else $error="NO SE ENCONTRO ESTA CAPACITACION EN LA BASE DE DATOS";
		}
		echo $error;
	}
}

//	MAESTRO DE EVALUACIONES
elseif ($_POST['modulo']=="EVALUACIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacion WHERE Descripcion='".$descripcion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_evaluacion", "Evaluacion", 4); $codigo=(int) $codigo;
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_evaluacion (Evaluacion, Descripcion, Plantilla, PuntajeMin, PuntajeMax, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".utf8_decode($descripcion)."', '$plantilla', '$pmin', '$pmax', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacion WHERE Descripcion='".$descripcion."' AND Evaluacion<>'".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_evaluacion SET Descripcion='".utf8_decode($descripcion)."', Plantilla='$plantilla', PuntajeMin='$pmin', PuntajeMax='$pmax', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Evaluacion='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {}
}

//	TIPO DE EVALUACIONES
elseif ($_POST['modulo']=="TEVALUACIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipoevaluacion WHERE Descripcion='".$descripcion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_tipoevaluacion (TipoEvaluacion, Descripcion, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".utf8_decode($descripcion)."', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_tipoevaluacion WHERE Descripcion='$descripcion' AND TipoEvaluacion<>'".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_tipoevaluacion SET Descripcion='".utf8_decode($descripcion)."', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE TipoEvaluacion='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$tevaluacion=esFKey("rh_tipoevaluacion", "TipoEvaluacion", $codigo);
		if ($tevaluacion) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="DELETE from rh_tipoevaluacion WHERE TipoEvaluacion='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
}

//	GRUPO DE COMPETENCIAS
elseif ($_POST['modulo']=="AEVALUACIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionarea WHERE Descripcion='".utf8_decode($descripcion)."' OR Area='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_evaluacionarea (Area, TipoEvaluacion, Descripcion, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '$tipo', '".utf8_decode($descripcion)."', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionarea WHERE Descripcion='".utf8_decode($descripcion)."' AND Area<>'".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_evaluacionarea SET TipoEvaluacion='$tipo', Descripcion='".utf8_decode($descripcion)."', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Area='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {	
		$sql="DELETE FROM rh_evaluacionarea WHERE Area='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	GRADO DE CALIFICACIONES
elseif ($_POST['modulo']=="GCALIFICACIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_gradoscompetencia WHERE Descripcion='".utf8_decode($descripcion)."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_gradoscompetencia", "Grado", 4); $codigo=(int) $codigo;
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_gradoscompetencia (Grado, Descripcion, PuntajeMin, PuntajeMax, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".utf8_decode($descripcion)."', '$pmin', '$pmax', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_gradoscompetencia WHERE Descripcion='".utf8_decode($descripcion)."' AND Grado<>'".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_gradoscompetencia SET Descripcion='".utf8_decode($descripcion)."', PuntajeMin='$pmin', PuntajeMax='$pmax', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Grado='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$sql="DELETE FROM rh_gradoscompetencia WHERE Grado='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
		$sql="DELETE FROM rh_factorvalor WHERE Grado='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	COMPETENCIAS
elseif ($_POST['modulo']=="COMPETENCIAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionfactores WHERE Descripcion='".utf8_decode($descripcion)."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_evaluacionfactores", "Competencia", 3); $codigo=(int) $codigo;
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_evaluacionfactores (Competencia, Descripcion, Explicacion, TipoCompetencia, Area, Nivel, Calificacion, FlagPlantilla, ValorRequerido, ValorMinimo, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".utf8_decode($descripcion)."', '".utf8_decode($explicacion)."', '$tipo', '$grupo', '$nivel', '$calificacion', '$fplantilla', '$requerido', '$minimo', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error.":".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionfactores WHERE Descripcion='".utf8_decode($descripcion)."' AND Competencia<>'".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_evaluacionfactores SET Descripcion='".utf8_decode($descripcion)."', Explicacion='".utf8_decode($explicacion)."', TipoCompetencia='$tipo', Area='$grupo', Nivel='$nivel', Calificacion='$calificacion', FlagPlantilla='$fplantilla', ValorRequerido='$requerido', ValorMinimo='$minimo', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Competencia='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error.":".$codigo;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	Elimino los grados de evaluacion de la competencia
		$sql="DELETE FROM rh_factorvalor WHERE Competencia='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	Elimino de Competencias
		$sql="DELETE FROM rh_evaluacionfactores WHERE Competencia='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	PLANTILLA DE COMPETENCIAS
elseif ($_POST['modulo']=="PCOMPETENCIAS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionfactoresplantilla WHERE Descripcion='".utf8_decode($descripcion)."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			$codigo=getCodigo("rh_evaluacionfactoresplantilla", "Plantilla", 3); $codigo=(int) $codigo;
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_evaluacionfactoresplantilla (Plantilla, Descripcion, TipoEvaluacion, FlagTipoEvaluacion, Estado, UltimoUsuario, UltimaFecha) VALUES ('$codigo', '".utf8_decode($descripcion)."', '$tipo', '$ftipo', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error."|:|".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionfactoresplantilla WHERE Descripcion='".utf8_decode($descripcion)."' AND Plantilla<>'".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_evaluacionfactoresplantilla SET Descripcion='".utf8_decode($descripcion)."', TipoEvaluacion='$tipo', FlagTipoEvaluacion='$ftipo', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Plantilla='".$codigo."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error."|:|".$codigo;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		//	Elimino de Plantillas Competencias
		$sql="DELETE FROM rh_factorvalorplantilla WHERE Plantilla='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	Elimino de Plantillas
		$sql="DELETE FROM rh_evaluacionfactoresplantilla WHERE Plantilla='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="INSERTAR") {
		if ($inserto=="NUEVO") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_factorvalorplantilla WHERE Competencia='$codcompetencia' AND Plantilla='".$plantilla."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE AGREGO ESTA COMPETENCIA A LA PLANTILLA";
			else {
				//	INSERTO
				$sql="INSERT INTO rh_factorvalorplantilla (Plantilla, Competencia, FlagPotencial, FlagCompetencia, FlagConceptual, Peso, OrdenFactor, UltimoUsuario, UltimaFecha) values ('$plantilla', '$codcompetencia', '$potencial', '$competencia', '$conceptual', '$peso', '$orden', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		} 
		else if ($inserto=="ACTUALIZAR"){
			//	ACTUALIZO
			$sql="UPDATE rh_factorvalorplantilla SET FlagPotencial='$potencial', FlagCompetencia='$competencia', FlagConceptual='$conceptual', Peso='$peso', OrdenFactor='$orden', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Plantilla='$plantilla' AND Competencia='$codcompetencia'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
		else if ($inserto=="EDITAR"){
			//	CONSULTO
			$sql="SELECT fvp.*, ef.Descripcion AS NomCompetencia FROM rh_factorvalorplantilla fvp INNER JOIN rh_evaluacionfactores ef ON (fvp.Competencia=ef.Competencia) WHERE fvp.Plantilla='$plantilla' AND fvp.Competencia='$codcompetencia'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			echo $error."|:|".$field['Competencia']."|:|".$field['NomCompetencia']."|:|".$field['Peso']."|:|".$field['OrdenFactor']."|:|".$field['FlagPotencial']."|:|".$field['FlagCompetencia']."|:|".$field['FlagConceptual']."|:|".$field['FactorParticipacion'];
		}
		else if ($inserto=="BORRAR"){
			//	ELIMINO
			$sql="DELETE FROM rh_factorvalorplantilla WHERE Plantilla='$plantilla' AND Competencia='$codcompetencia'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
		/*
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_factorvalorplantilla WHERE Competencia='$codcompetencia' AND Plantilla='".$plantilla."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="YA SE AGREGO ESTA COMPETENCIA A LA PLANTILLA";
		else {
			
		}
		*/
	}
}

//	VACACIONES DEL EMPLEADO
elseif ($_POST['modulo']=="VACACIONES") {
	connect();
	$error=0;
	$guardar=true;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $finicio); $finicio=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $ffin); $ffin=$annio.$mes.$dia;
	//	---------------------------------
	$aactual=date("Y"); $mactual=date("m"); $dactual=date("d"); $factual=date("d-m-Y");
	list($dingreso, $mingreso, $aingreso)=SPLIT( '[/.-]', $fingreso);
	$total_periodos=$aactual-$aingreso+1;
	//	---------------------------------
	$sql="SELECT ValorParam FROM mastparametros WHERE ParametroClave='DERECHO' AND Estado='A' AND CodAplicacion='RH'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	$dias_derecho=$field['ValorParam'];
	//	---------------------------------
	$sql="SELECT ValorParam FROM mastparametros WHERE ParametroClave='PAGOVACA' AND Estado='A' AND CodAplicacion='RH'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	$dias_pago=$field['ValorParam'];
	//	---------------------------------
	if ($_POST['accion']=="UTILIZACION") {
		if ($sub=="INSERTAR") {
			if ($nro_periodo>1) {
				$periodo_validar=$nro_periodo-1;
				$sql="SELECT vp.*, vu.* FROM rh_vacacionperiodo vp INNER JOIN rh_vacacionutilizacion vu ON (vp.CodPersona=vu.CodPersona AND vp.NroPeriodo=vu.NroPeriodo) WHERE vp.CodPersona='".$persona."' AND vp.NroPeriodo='".$periodo_validar."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows==0) { 
					$error="DEBE INGRESAR LOS PERIODOS QUE TIENEN VACACIONES PENDIENTES PRIMERO"; 
					$guardar=false; 
				} else {
					$field=mysql_fetch_array($query);
					if ($field['Pendientes']>0) { 
						$error="DEBE INGRESAR LOS PERIODOS QUE TIENEN VACACIONES PENDIENTES PRIMERO"; 
						$guardar=false; 
					}
				}
			}
			$periodo_validar=$nro_periodo+1;
			$sql="SELECT * FROM rh_vacacionutilizacion WHERE CodPersona='".$persona."' AND NroPeriodo='".$periodo_validar."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) { $error="NO SE PUEDE INSERTAR UN NUEVO REGISTRO"; $guardar=false; }
			if ($guardar) {
				if ($mingreso<$mactual || ($mingreso==$mactual && $dingreso<$dactual)) $total_periodos=$aactual-$aingreso+1; else $total_periodos=$aactual-$aingreso;
				//	VERIFICO LA TABLA rh_vacacionperiodo
				$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo>=$nro_periodo ORDER BY NroPeriodo";
				$query_select=mysql_query($sql) or die ($sql.mysql_error());
				$rows_select=mysql_num_rows($query_select);
				if ($rows_select==0) {
					//	SI NO EXSITEN DATOS EN LA TABLA INSERTO TODOS LOS PERIODOS
					$anio_periodo=$aingreso;
					$ley=0;
					for ($i=1; $i<=$total_periodos; $i++) {
						if (($i-1)%5==0 && $i>1) $ley+=2; else $ley++;
						if ($i==1) {
							if ($mes_programado<10) $mes_programado="0".$mes_programado;
							$pendientes=$dias_derecho-$dias;
							$pendiente_pago=$dias_pago;
							$sql="INSERT INTO rh_vacacionperiodo (CodPersona, NroPeriodo, Anio, Mes, Derecho, PendientePeriodo, DiasGozados, DiasTrabajados, DiasInterrumpidos, DiasNoGozados, TotalUtilizados, Pendientes, PagosRealizados, PendienteReales, PendientePago, UltimoUsuario, UltimaFecha) VALUES ('".$persona."', '".$i."', '".$anio_periodo."', '".$mes_programado."', '".$dias_derecho."', '0', '".$dias."', '0', '0', '0', '".$dias."', '".$pendientes."', '0', '0', '".$pendiente_pago."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
							$pendiente_periodo=$pendientes;
						} else {
							if ($i==$total_periodos) {
								$dias_transcurridos=getFechaDias("$dingreso-$mingreso-$anio_periodo", "$dactual-$mactual-$aactual"); 
								if ($dias_transcurridos>365) $dias_transcurridos=365;
								$derecho_ley=($dias_derecho/365)*$dias_transcurridos; $derecho_ley=$derecho_ley+$ley-1;
							} else $derecho_ley=$dias_derecho+$ley-1;
							if ($derecho_ley>30) $derecho_ley=30;
							$pendiente_pago+=$dias_pago;
							$pendientes=$derecho_ley+$pendiente_periodo;
							$sql="INSERT INTO rh_vacacionperiodo (CodPersona, NroPeriodo, Anio, Mes, Derecho, PendientePeriodo, DiasGozados, DiasTrabajados, DiasInterrumpidos, DiasNoGozados, TotalUtilizados, Pendientes, PagosRealizados, PendienteReales, PendientePago, UltimoUsuario, UltimaFecha) VALUES ('".$persona."', '".$i."', '".$anio_periodo."', '".$mingreso."', '".$derecho_ley."', '".$pendiente_periodo."', '0', '0', '0', '0', '0', '".$pendientes."', '0', '0', '".$pendiente_pago."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
							$pendiente_periodo+=$derecho_ley;
						}
						$query_insert=mysql_query($sql) or die ($sql.mysql_error());
						$anio_periodo++;
					}
				} else {
					$fin=$rows_select+$nro_periodo;
					for ($i=$nro_periodo; $i<=$fin; $i++) {
						$field_select=mysql_fetch_array($query_select);
						if ($i==$nro_periodo) {
							if ($mes_programado<10) $mes_programado="0".$mes_programado;
							if ($utilizacion=="G") {
								$dias_gozados=$dias+$field_select['DiasGozados']; 
								$dias_interrumpidos=0;
								$pendientes=$derecho-$dias_gozados;
							}
							elseif ($utilizacion=="I") { 
								$dias_gozados=$field_select['DiasGozados']-$dias; 
								$dias_interrumpidos=$dias; 
								$pendientes=$field_select['Pendientes']+$dias; ;
							}
							$sql="UPDATE rh_vacacionperiodo SET Mes='$mes_programado', Derecho='$derecho', PendientePeriodo='0', DiasGozados='$dias_gozados', DiasTrabajados='$dias_interrumpidos', DiasInterrumpidos='$dias_interrumpidos', DiasNoGozados='$dias_interrumpidos', TotalUtilizados='$dias_gozados', Pendientes='$pendientes' WHERE CodPersona='$persona' AND NroPeriodo='$nro_periodo'";
							$pendiente_periodo=$pendientes;
						} else {
							$pendientes=$field_select['Derecho']+$pendiente_periodo;
							$sql="UPDATE rh_vacacionperiodo SET PendientePeriodo='$pendiente_periodo', Pendientes='$pendientes' WHERE CodPersona='$persona' AND NroPeriodo='$i'";
							$pendiente_periodo+=$field_select['Derecho'];
						}
						$query_update=mysql_query($sql) or die ($sql.mysql_error());
					}
				}
				//	INSERTO EL REGISTRO
				$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_vacacionutilizacion", $persona);
				$sql="INSERT INTO rh_vacacionutilizacion (Secuencia, CodPersona, NroPeriodo, FechaInicio, FechaFin, TipoUtilizacion, DiasUtiles, UltimoUsuario, UltimaFecha) VALUES ('$secuencia', '$persona', '$nro_periodo', '$finicio', '$ffin', '$utilizacion', '$dias', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		elseif ($sub=="ELIMINAR") {
			list($persona, $nro_periodo)=SPLIT( '[/.-]', $foraneos);
			$periodo_validar=$nro_periodo+1;
			//	---------------------------------
			$sql="SELECT * FROM rh_vacacionutilizacion WHERE CodPersona='".$persona."' AND NroPeriodo='".$periodo_validar."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO";
			else {
				$sql="SELECT Derecho, DiasGozados, (SELECT DiasUtiles FROM rh_vacacionutilizacion WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."' AND Secuencia='".$codigo."') AS Dias, (SELECT TipoUtilizacion FROM rh_vacacionutilizacion WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."' AND Secuencia='".$codigo."') AS Utilizacion FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0) $field=mysql_fetch_array($query);
				$dias=$field['Dias'];
				$utilizacion=$field['Utilizacion'];
				//	----------------------------
				if ($field['Utilizacion']=="I" && $field['Derecho']<$field['DiasGozados']+$field['Dias']) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO.";
				else {
					$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo>='".$nro_periodo."' ORDER BY NroPeriodo";
					$query_select=mysql_query($sql) or die ($sql.mysql_error());
					$rows_select=mysql_num_rows($query_select);
					//	----------------------------
					$fin=$rows_select+$nro_periodo;
					for ($i=$nro_periodo; $i<=$fin; $i++) {
						$field_select=mysql_fetch_array($query_select);
						if ($i==$nro_periodo) {
							if ($utilizacion=="G") { 
								$dias_gozados=$field_select['DiasGozados']-$dias;
								$dias_interrumpidos=$field_select['DiasInterrumpidos'];
								$pendientes=$field_select['Pendientes']+$dias;
							}
							elseif ($utilizacion=="I") { 
								$dias_gozados=$field_select['DiasGozados']+$dias;
								$dias_interrumpidos=$field_select['DiasInterrumpidos']-$dias; if ($dias_interrumpidos<0) $dias_interrumpidos=0;
								$pendientes=$field_select['Pendientes']-$dias; if ($pendientes<0) $pendientes=0;
							}
							$sql="UPDATE rh_vacacionperiodo SET DiasGozados='$dias_gozados', DiasTrabajados='$dias_interrumpidos', DiasInterrumpidos='$dias_interrumpidos', DiasNoGozados='$dias_interrumpidos', TotalUtilizados='$dias_gozados', Pendientes='$pendientes' WHERE CodPersona='$persona' AND NroPeriodo='$i'";
						} else {
							if ($utilizacion=="G") {
								$pendiente_periodo=$field_select['PendientePeriodo']+$dias;
								$pendientes=$field_select['Pendientes']+$dias;
							}
							elseif ($utilizacion=="I") { 
								$pendiente_periodo=$field_select['PendientePeriodo']-$dias;
								$pendientes=$field_select['Pendientes']-$dias;
							}
							$sql="UPDATE rh_vacacionperiodo SET PendientePeriodo='$pendiente_periodo', Pendientes='$pendientes' WHERE CodPersona='$persona' AND NroPeriodo='$i'";
						}
						$query_update=mysql_query($sql) or die ($sql.mysql_error());
					}
					$sql="DELETE FROM rh_vacacionutilizacion WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."' AND Secuencia='".$codigo."'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
				}
			}
			echo $error;
		}
	}
	//	---------------------------------
	elseif ($_POST['accion']=="PAGOS") {
		if ($sub=="INSERTAR") {
			if ($nro_periodo>1) {
				$periodo_validar=$nro_periodo-1;
				$sql="SELECT vp.*, vu.* FROM rh_vacacionperiodo vp INNER JOIN rh_vacacionpago vu ON (vp.CodPersona=vu.CodPersona AND vp.NroPeriodo=vu.NroPeriodo) WHERE vp.CodPersona='".$persona."' AND vp.NroPeriodo='".$periodo_validar."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows==0) { 
					$error="DEBE INGRESAR LOS PERIODOS QUE TIENEN PAGOS PENDIENTES PRIMERO"; 
					$guardar=false; 
				} else {
					$field=mysql_fetch_array($query);
					if ($field['PendientePago']>0) { 
						$error="DEBE INGRESAR LOS PERIODOS QUE TIENEN PAGOS PENDIENTES PRIMERO"; 
						$guardar=false; 
					}
				}
			}
			$periodo_validar=$nro_periodo+1;
			$sql="SELECT * FROM rh_vacacionpago WHERE CodPersona='".$persona."' AND NroPeriodo='".$periodo_validar."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) { $error="NO SE PUEDE INSERTAR UN NUEVO REGISTRO"; $guardar=false; }
			if ($guardar) {
				if ($mingreso<$mactual || ($mingreso==$mactual && $dingreso<$dactual)) $total_periodos=$aactual-$aingreso+1; else $total_periodos=$aactual-$aingreso;
				//	VERIFICO LA TABLA rh_vacacionperiodo
				$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo>=$nro_periodo ORDER BY NroPeriodo";
				$query_select=mysql_query($sql) or die ($sql.mysql_error());
				$rows_select=mysql_num_rows($query_select);
				if ($rows_select==0) {
					//	SI NO EXSITEN DATOS EN LA TABLA INSERTO TODOS LOS PERIODOS
					$anio_periodo=$aingreso;
					$ley=0;
					for ($i=1; $i<=$total_periodos; $i++) {
						if (($i-1)%5==0 && $i>1) $ley+=2; else $ley++;
						if ($i==1) {
							if ($mes_programado<10) $mes_programado="0".$mes_programado;
							$pendientes=$dias_derecho;
							$pendiente_pago=$dias_pago-$dias;
							$sql="INSERT INTO rh_vacacionperiodo (CodPersona, NroPeriodo, Anio, Mes, Derecho, PendientePeriodo, DiasGozados, DiasTrabajados, DiasInterrumpidos, DiasNoGozados, TotalUtilizados, Pendientes, PagosRealizados, PendienteReales, PendientePago, UltimoUsuario, UltimaFecha) VALUES ('".$persona."', '".$i."', '".$anio_periodo."', '".$mes_programado."', '".$dias_derecho."', '0', '".$dias."', '0', '0', '0', '".$dias."', '".$pendientes."', '0', '0', '".$pendiente_pago."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
							$pendiente_periodo=$pendientes;
						} else {
							if ($i==$total_periodos) {
								$dias_transcurridos=getFechaDias("$dingreso-$mingreso-$anio_periodo", "$dactual-$mactual-$aactual"); 
								if ($dias_transcurridos>365) $dias_transcurridos=365;
								$derecho_ley=($dias_derecho/365)*$dias_transcurridos; $derecho_ley=$derecho_ley+$ley-1;
							} else $derecho_ley=$dias_derecho+$ley-1;
							if ($derecho_ley>30) $derecho_ley=30;
							$pendiente_pago+=$dias_pago;
							$pendientes=$derecho_ley+$pendiente_periodo;
							$sql="INSERT INTO rh_vacacionperiodo (CodPersona, NroPeriodo, Anio, Mes, Derecho, PendientePeriodo, DiasGozados, DiasTrabajados, DiasInterrumpidos, DiasNoGozados, TotalUtilizados, Pendientes, PagosRealizados, PendienteReales, PendientePago, UltimoUsuario, UltimaFecha) VALUES ('".$persona."', '".$i."', '".$anio_periodo."', '".$mingreso."', '".$derecho_ley."', '".$pendiente_periodo."', '0', '0', '0', '0', '0', '".$pendientes."', '0', '0', '".$pendiente_pago."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
							$pendiente_periodo+=$derecho_ley;
						}
						$query_insert=mysql_query($sql) or die ($sql.mysql_error());
						$anio_periodo++;
					}
				} else {
					$fin=$rows_select+$nro_periodo;
					for ($i=$nro_periodo; $i<=$fin; $i++) {
						$field_select=mysql_fetch_array($query_select);
						if ($i==$nro_periodo) {
							if ($mes_programado<10) $mes_programado="0".$mes_programado;
							$pendiente_pago=$field_select['PendientePago']-$dias;
							$sql="UPDATE rh_vacacionperiodo SET Mes='$mes_programado', Derecho='$derecho', PendientePago='$pendiente_pago' WHERE CodPersona='$persona' AND NroPeriodo='$nro_periodo'";
						} else {
							$pendiente_pago=$dias_pago+$pendiente_pago;
							$sql="UPDATE rh_vacacionperiodo SET PendientePago='$pendiente_pago' WHERE CodPersona='$persona' AND NroPeriodo='$i'";
							$pendiente_periodo+=$derecho;
						}
						$query_update=mysql_query($sql) or die ($sql.mysql_error());
					}
				}
				//	INSERTO EL REGISTRO
				$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_vacacionpago", $persona);
				$sql="INSERT INTO rh_vacacionpago (CodPersona, NroPeriodo, Secuencia, DiasPago, Periodo, Concepto, FechaInicio, FechaFin, UltimoUsuario, UltimaFecha) VALUES ('$persona', '$nro_periodo', '$secuencia', '$dias', '$periodo', '$concepto', '$finicio', '$ffin', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		elseif ($sub=="ELIMINAR") {
			list($persona, $nro_periodo)=SPLIT( '[/.-]', $foraneos);
			$periodo_validar=$nro_periodo+1;
			//	---------------------------------
			$sql="SELECT * FROM rh_vacacionpago WHERE CodPersona='".$persona."' AND NroPeriodo='".$periodo_validar."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO";
			else {
				$sql="SELECT Derecho, PendientePago, (SELECT DiasPago FROM rh_vacacionpago WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."' AND Secuencia='".$codigo."') AS Dias FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0) $field=mysql_fetch_array($query);
				$dias=$field['Dias'];
				//	----------------------------
				$sql="SELECT * FROM rh_vacacionperiodo WHERE CodPersona='".$persona."' AND NroPeriodo>='".$nro_periodo."' ORDER BY NroPeriodo";
				$query_select=mysql_query($sql) or die ($sql.mysql_error());
				$rows_select=mysql_num_rows($query_select);
				//	----------------------------
				$fin=$rows_select+$nro_periodo;
				for ($i=$nro_periodo; $i<=$fin; $i++) {
					$field_select=mysql_fetch_array($query_select);
					if ($i==$nro_periodo) $pendiente_pago=$field_select['PendientePago']+$dias;
					else $pendiente_pago+=$field_select['Derecho'];
					$sql="UPDATE rh_vacacionperiodo SET PendientePago='$pendiente_pago' WHERE CodPersona='$persona' AND NroPeriodo='$i'";
					$query_update=mysql_query($sql) or die ($sql.mysql_error());
				}
				$sql="DELETE FROM rh_vacacionpago WHERE CodPersona='".$persona."' AND NroPeriodo='".$nro_periodo."' AND Secuencia='".$codigo."'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
	}
}

//	MAESTRO DE USUARIOS
elseif ($_POST['modulo']=="USUARIOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fexpira']); $fexpira=$annio.$mes.$dia;
	//	---------------------------------
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM usuarios WHERE Usuario='$usuario' OR CodPersona='$codempleado'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NOMBRE DE USUARIO O EMPLEADO YA EXISTENTE ";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO usuarios (Usuario, CodPersona, Clave, FlagFechaExpirar, FechaExpirar, Estado, UltimoUsuario, UltimaFecha) VALUES ('$usuario', '$codempleado', '$clave', '$flag', '$fexpira', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	---------------------------------
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	ACTUALIZO REGISTRO
		$sql="UPDATE usuarios SET Clave='$clave', FlagFechaExpirar='$flag', FechaExpirar='$fexpira', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE Usuario='".$_POST['usuario']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	---------------------------------
	elseif ($_POST['accion']=="ELIMINAR") {
		$seguridad_autorizaciones=esFKey("seguridad_autorizaciones", "Usuario", $_POST['codigo']);
		if ($seguridad_autorizaciones) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
			$sql="DELETE FROM usuarios WHERE Usuario='".$_POST['codigo']."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	MAESTRO DE CONCEPTOS DE SEGURIDAD
elseif ($_POST['modulo']=="EVALUACIONPERIODO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $ffin); $ffin=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $finicio); $finicio=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $fcierre); $fcierre=$annio.$mes.$dia;
	//	---------------------------
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionperiodo WHERE CodOrganismo='$organismo' AND Periodo='$periodo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="YA SE APERTURO EL PERIODO PARA ESTE ORGANISMO";
		else {
			$secuencia=getSecuencia2("Secuencia", "CodOrganismo", "Periodo", "rh_evaluacionperiodo", $organismo, $periodo);
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_evaluacionperiodo (CodOrganismo, Secuencia, Descripcion, TipoEvaluacion, Periodo, FechaFin, FechaInicio, FechaFinal, FlagDesempenio, FlagMetas, FlagNecesidad, FlagRevision, FlagIncidentes, FlagFortaleza, FlagFunciones, Estado, UltimoUsuario, UltimaFecha) VALUES ('$organismo', '$secuencia', '".utf8_decode($descripcion)."', '$tipo', '$periodo', '$ffin', '$finicio', '$fcierre', '$desempenio', '$metas', '$necesidad', '$revision', '$incidentes', '$fortaleza', '$funciones', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo "$error|:|$organismo:$periodo:$secuencia";
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionperiodo WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Descripcion='$descripcion' AND Secuencia<>'$codigo' ";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE rh_evaluacionperiodo SET Descripcion='".utf8_decode($descripcion)."', TipoEvaluacion='$tipo', FechaFin='$ffin', FechaInicio='$finicio', FechaFinal='$fcierre', FlagDesempenio='$desempenio', FlagMetas='$metas', FlagNecesidad='$necesidad', FlagRevision='$revision', FlagIncidentes='$incidentes', FlagFortaleza='$fortaleza', FlagFunciones='$funciones', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		list($organismo, $periodo, $secuencia)=SPLIT( '[:.:]', $codigo);
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_evaluacionperiodovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="DELETE FROM rh_evaluacionperiodo WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="VALORES") {
		if ($sub=="INSERTAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_evaluacionperiodovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND Descripcion='".utf8_decode($descripcion)."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="RANGO YA INGRESADO";
			else {
				$rango=getSecuencia3("Rango", "CodOrganismo", "Periodo", "Secuencia", "rh_evaluacionperiodovalor", $organismo, $periodo, $secuencia);
				//	INSERTO
				$sql="INSERT INTO rh_evaluacionperiodovalor (CodOrganismo, Periodo, Secuencia, Rango, Descripcion, Explicacion, Valor, UltimoUsuario, UltimaFecha) values ('$organismo', '$periodo', '$secuencia', '$rango', '".utf8_decode($descripcion)."', '".utf8_decode($explicacion)."', '$valor', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		} 
		else if ($sub=="ACTUALIZAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_evaluacionperiodovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND Descripcion='".utf8_decode($descripcion)."' AND Rango<>'$rango'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE AGREGO ESTE CONCEPTO";
			else {
				//	ACTUALIZO
				$sql="UPDATE rh_evaluacionperiodovalor SET Descripcion='".utf8_decode($descripcion)."', Explicacion='".utf8_decode($explicacion)."', Valor='$valor', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND Rango='$rango'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		else if ($sub=="EDITAR") {
			//	CONSULTO
			$sql="SELECT Descripcion, Explicacion, Valor FROM rh_evaluacionperiodovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND Rango='$rango'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			echo $error."|:|".$field['Descripcion']."|:|".$field['Explicacion']."|:|".number_format($field['Valor'], 2, ',', '.');
		}
		else if ($sub=="BORRAR") {
			//	ELIMINO
			$sql="DELETE FROM rh_evaluacionperiodovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND Rango='$rango'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
	}
}

//	EVALUACION DE DESEMPE�O--	ELIMINAR AQUI
elseif ($_POST['modulo']=="EVALUACIONDESEMPENIO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($d, $m, $a)=SPLIT( '[/.-]', $fecha_evaluacion); $fecha_evaluacion="$a-$m-$d";
	//	---------------------------
	if ($_POST['accion']=="GUARDAR") {		
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM rh_evaluacionempleado WHERE CodOrganismo='$forganismo' AND Periodo='$periodo' AND Secuencia='$codigo' AND CodPersona='$persona'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="EMPLEADO YA INGRESADO";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO rh_evaluacionempleado (CodOrganismo, Periodo, Secuencia, CodPersona, ComentarioPersona, Evaluador, ComentarioEvaluador, Supervisor, ComentarioSupervisor, FechaEvaluacion, TotalDesempenio, TotalFuncion, TotalMetas, Estado, UltimoUsuario, UltimaFecha) VALUES ('$forganismo', '$periodo', '$secuencia', '$persona', '".utf8_decode($comempleado)."', '$codevaluador', '".utf8_decode($comevaluador)."', '$codsupervisor', '".utf8_decode($comsupervisor)."', '$fecha_evaluacion', '$desempenio', '$funcion', '$metas', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo "$error|:|$forganismo:$fpevaluacion:$chkempleado:$fempleado:$fnomempleado:$chkresponsable:$fresponsable:$fnomresponsable:$filtro:$limit";
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	ACTUALIZO REGISTRO
		$sql="UPDATE rh_evaluacionempleado SET ComentarioPersona='".utf8_decode($comempleado)."', Evaluador='$codevaluador', ComentarioEvaluador='".utf8_decode($comevaluador)."', Supervisor='$codsupervisor', ComentarioSupervisor='".utf8_decode($comsupervisor)."', FechaEvaluacion='$fecha_evaluacion', TotalDesempenio='$desempenio', TotalFuncion='$funcion', TotalMetas='$metas', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$forganismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		list($organismo, $periodo, $secuencia)=SPLIT( '[:.:]', $codigo);
		//	CONSULTO SI EL REGISTRO A ELIMINAR ESTA ENLAZADO A UNA TABLA HIJA
		$sql="SELECT * FROM rh_evaluacionempleadovalor WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="NO SE PUEDE ELIMINAR ESTE REGISTRO (ENLAZADO A OTRO REGISTRO)";
		else {
			$sql="DELETE FROM rh_evaluacionempleado WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="FORTALEZAS") {
		if ($sub=="INSERTAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_desempenio WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND Tipo='$tipo' AND Descripcion='".utf8_decode($descripcion)."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="REGISTRO YA INGRESADO";
			else {
				$codigo=getSecuencia5("SecuenciaDesempenio", "CodOrganismo", "Periodo", "Secuencia", "CodPersona", "Evaluador", "rh_empleado_desempenio", $organismo, $periodo, $secuencia, $persona, $evaluador);
				//	INSERTO
				$sql="INSERT INTO rh_empleado_desempenio (CodOrganismo, Periodo, Secuencia, CodPersona, Evaluador, SecuenciaDesempenio, Descripcion, Tipo, UltimoUsuario, UltimaFecha) values ('$organismo', '$periodo', '$secuencia', '$persona', '$evaluador', '$codigo', '".utf8_decode($descripcion)."', '$tipo', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		} 
		else if ($sub=="ACTUALIZAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_desempenio WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND Descripcion='".utf8_decode($descripcion)."' AND SecuenciaDesempenio<>'$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE AGREGO ESTE CONCEPTO";
			else {
				//	ACTUALIZO
				$sql="UPDATE rh_empleado_desempenio SET Descripcion='".utf8_decode($descripcion)."', Tipo='$tipo', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$codigo'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		else if ($sub=="EDITAR") {
			//	CONSULTO
			$sql="SELECT Descripcion, Tipo FROM rh_empleado_desempenio WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			echo $error."|:|".$field['Descripcion']."|:|".$field['Tipo'];
		}
		else if ($sub=="BORRAR") {
			//	ELIMINO
			$sql="DELETE FROM rh_empleado_desempenio WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
	}
	elseif ($_POST['accion']=="METAS") {
		if ($sub=="INSERTAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_metas WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND Descripcion='".utf8_decode($descripcion)."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="REGISTRO YA INGRESADO";
			else {
				$codigo=getSecuencia5("SecuenciaDesempenio", "CodOrganismo", "Periodo", "Secuencia", "CodPersona", "Evaluador", "rh_empleado_metas", $organismo, $periodo, $secuencia, $persona, $evaluador);
				//	INSERTO
				$sql="INSERT INTO rh_empleado_metas (CodOrganismo, Periodo, Secuencia, CodPersona, Evaluador, SecuenciaDesempenio, Descripcion, Comentarios, PeriodoMetas, Calificacion, Peso, UltimoUsuario, UltimaFecha) values ('$organismo', '$periodo', '$secuencia', '$persona', '$evaluador', '$codigo', '".utf8_decode($descripcion)."', '".utf8_decode($comentarios)."', '$periodo_metas', '$calificacion', '$peso', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		} 
		else if ($sub=="ACTUALIZAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_metas WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND Descripcion='".utf8_decode($descripcion)."' AND SecuenciaDesempenio<>'$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE AGREGO ESTE CONCEPTO";
			else {
				//	ACTUALIZO
				$sql="UPDATE rh_empleado_metas SET Descripcion='".utf8_decode($descripcion)."', Comentarios='".utf8_decode($comentarios)."', PeriodoMetas='$periodo_metas', Calificacion='$calificacion', Peso='$peso', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$codigo'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		else if ($sub=="EDITAR") {
			//	CONSULTO
			$sql="SELECT Descripcion, PeriodoMetas, Comentarios, Calificacion, Peso FROM rh_empleado_metas WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			$calificacion=number_format($field['Calificacion'], 2, ',', '.');
			$peso=number_format($field['Peso'], 2, ',', '.');
			echo $error."|:|".$field['Descripcion']."|:|".$field['PeriodoMetas']."|:|".$field['Comentarios']."|:|".$calificacion."|:|".$peso;
		}
		else if ($sub=="BORRAR") {
			//	ELIMINO
			$sql="DELETE FROM rh_empleado_metas WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
	}
	elseif ($_POST['accion']=="REVISION") {
		if ($sub=="INSERTAR") {
			$codigo=getSecuencia5("SecuenciaDesempenio", "CodOrganismo", "Periodo", "Secuencia", "CodPersona", "Evaluador", "rh_empleado_revision", $organismo, $periodo, $secuencia, $persona, $evaluador);
			//	INSERTO
			$sql="INSERT INTO rh_empleado_revision (CodOrganismo, Periodo, Secuencia, CodPersona, Evaluador, SecuenciaDesempenio, PrimeraRevision, PorcPrimeraRevision, SegundaRevision, PorcSegundaRevision, TerceraRevision, PorcTerceraRevision, UltimoUsuario, UltimaFecha) values ('$organismo', '$periodo', '$secuencia', '$persona', '$evaluador', '$codigo', '".utf8_decode($primera)."', '$porc1', '".utf8_decode($segunda)."', '$porc2', '".utf8_decode($tercera)."', '$porc3', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		} 
		else if ($sub=="ACTUALIZAR") {
			//	ACTUALIZO
			$sql="UPDATE rh_empleado_revision SET PrimeraRevision='".utf8_decode($primera)."', PorcPrimeraRevision='$porc1', SegundaRevision='".utf8_decode($segunda)."', PorcSegundaRevision='$porc2', TerceraRevision='".utf8_decode($tercera)."', PorcTerceraRevision='$porc3', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
		else if ($sub=="EDITAR") {
			//	CONSULTO
			$sql="SELECT PrimeraRevision, SegundaRevision, TerceraRevision, PorcPrimeraRevision, PorcSegundaRevision, PorcTerceraRevision FROM rh_empleado_revision WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			$porc1=number_format($field['PorcPrimeraRevision'], 2, ',', '.');
			$porc2=number_format($field['PorcSegundaRevision'], 2, ',', '.');
			$porc3=number_format($field['PorcTerceraRevision'], 2, ',', '.');
			echo $error."|:|".$field['PrimeraRevision']."|:|".$field['SegundaRevision']."|:|".$field['TerceraRevision']."|:|".$porc1."|:|".$porc2."|:|".$porc3;
		}
		else if ($sub=="BORRAR") {
			//	ELIMINO
			$sql="DELETE FROM rh_empleado_revision WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
	}
	elseif ($_POST['accion']=="NECESIDAD") {
		if ($sub=="INSERTAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_necesidad WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND CodCurso='".$codcurso."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="REGISTRO YA INGRESADO";
			else {
				$codigo=getSecuencia5("SecuenciaDesempenio", "CodOrganismo", "Periodo", "Secuencia", "CodPersona", "Evaluador", "rh_empleado_necesidad", $organismo, $periodo, $secuencia, $persona, $evaluador);
				//	INSERTO
				$sql="INSERT INTO rh_empleado_necesidad (CodOrganismo, Periodo, Secuencia, CodPersona, Evaluador, SecuenciaDesempenio, Necesidad, Prioridad, CodCurso, Objetivo, UltimoUsuario, UltimaFecha) values ('$organismo', '$periodo', '$secuencia', '$persona', '$evaluador', '$codigo', '".utf8_decode($necesidad)."', '$prioridad', '$codcurso', '".utf8_decode($objetivo)."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		} 
		else if ($sub=="ACTUALIZAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_necesidad WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND CodCurso='".$codcurso."' AND SecuenciaDesempenio<>'$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE AGREGO ESTE CONCEPTO";
			else {
				//	ACTUALIZO
				$sql="UPDATE rh_empleado_necesidad SET Necesidad='".utf8_decode($necesidad)."', Objetivo='".utf8_decode($objetivo)."', Prioridad='$prioridad', CodCurso='$codcurso', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$codigo'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		else if ($sub=="EDITAR") {
			//	CONSULTO
			$sql="SELECT en.Necesidad, en.Prioridad, en.CodCurso, c.Descripcion AS Curso, en.Objetivo FROM rh_empleado_necesidad en INNER JOIN rh_cursos c ON (en.CodCurso=c.CodCurso) WHERE en.CodOrganismo='$organismo' AND en.Periodo='$periodo' AND en.Secuencia='$secuencia' AND en.CodPersona='$persona' AND en.Evaluador='$evaluador' AND en.SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			$calificacion=number_format($field['Calificacion'], 2, ',', '.');
			$peso=number_format($field['Peso'], 2, ',', '.');
			echo $error."|:|".$field['Necesidad']."|:|".$field['Prioridad']."|:|".$field['CodCurso']."|:|".$field['Curso']."|:|".$field['Objetivo'];
		}
		else if ($sub=="BORRAR") {
			//	ELIMINO
			$sql="DELETE FROM rh_empleado_necesidad WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
	}
	elseif ($_POST['accion']=="FUNCIONES") {
		if ($sub=="INSERTAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_funciones WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND Funcion='".$funcion."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="REGISTRO YA INGRESADO";
			else {
				$codigo=getSecuencia5("SecuenciaDesempenio", "CodOrganismo", "Periodo", "Secuencia", "CodPersona", "Evaluador", "rh_empleado_funciones", $organismo, $periodo, $secuencia, $persona, $evaluador);
				//	INSERTO
				$sql="INSERT INTO rh_empleado_funciones (CodOrganismo, Periodo, Secuencia, CodPersona, Evaluador, SecuenciaDesempenio, Funcion, Calificacion, Peso, UltimoUsuario, UltimaFecha) values ('$organismo', '$periodo', '$secuencia', '$persona', '$evaluador', '$codigo', '".utf8_decode($funcion)."', '$calificacion', '$peso', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		} 
		else if ($sub=="ACTUALIZAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM rh_empleado_funciones WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND Funcion='".$funcion."' AND SecuenciaDesempenio<>'$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="YA SE AGREGO ESTA FUNCION";
			else {
				//	ACTUALIZO
				$sql="UPDATE rh_empleado_funciones SET Funcion='".utf8_decode($funcion)."', Calificacion='$calificacion', Peso='$peso', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$codigo'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		else if ($sub=="EDITAR") {
			//	CONSULTO
			$sql="SELECT Funcion, Calificacion, Peso FROM rh_empleado_funciones WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='".$secuencia_desempenio."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $field=mysql_fetch_array($query);
			$calificacion=number_format($field['Calificacion'], 2, ',', '.');
			echo $error."|:|".$field['Funcion']."|:|".$calificacion."|:|".$field['Peso'];
		}
		else if ($sub=="BORRAR") {
			//	ELIMINO
			$sql="DELETE FROM rh_empleado_funciones WHERE CodOrganismo='$organismo' AND Periodo='$periodo' AND Secuencia='$secuencia' AND CodPersona='$persona' AND Evaluador='$evaluador' AND SecuenciaDesempenio='$secuencia_desempenio'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error."|:|";
		}
	}
	elseif ($_POST['accion']=="CALIFICACIONDESEMPENIO") {
		$sql="SELECT SUM(Calificacion) AS SumaCalificacion, COUNT(Calificacion) AS NroCalificacion FROM rh_empleado_evaluacion WHERE CodOrganismo='".$organismo."' AND Periodo='".$periodo."' AND Secuencia='".$secuencia."' AND CodPersona='".$persona."' AND Evaluador='".$evaluador."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$field=mysql_fetch_array($query);
		$total_calificacion=$field['SumaCalificacion']/$field['NroCalificacion'];
		$calificacion=number_format($total_calificacion, 2, ',', '.');
		//	ACTUALIZO EL VALOR DEL DESEMPENIO
		$sql="UPDATE rh_evaluacionempleado SET TotalDesempenio='".$total_calificacion."' WHERE CodOrganismo='".$organismo."' AND Periodo='".$periodo."' AND Secuencia='".$secuencia."' AND CodPersona='".$persona."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo "0:$calificacion";
	}
}

//	CONTROL DE NIVELACIONES
elseif ($_POST['modulo']=="NIVELACIONES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	$periodo=date("Y-m");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $_POST['fecha']); $fecha=$annio.$mes.$dia;
	//	---------------------------------
	if ($tipo_accion != "ET") {
		//	ACTUALIZO EMPLEADO
		$sql="UPDATE mastempleado SET CodOrganismo='".$organismo."', CodDependencia='".$dependencia."', CodCargo='".$cargo."', CodCargoTemp = '', CodTipoNom='".$nomina."', SueldoAnterior='".$sueldo_ant."', SueldoActual='".$sueldo."' WHERE CodPersona='".$codpersona."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
		//	ACTUALIZO NIVELACION
		$a = (int) $annio; $m = (int) $mes; $d = (int) $dia;
		$d = $d - 1;
		if ($d == 0) {
			$m = $m - 1;
			if ($m == 0) {
				$a = $a - 1;
				$m = 12;
			}
			$d = getDiasMes($a, $m);
		}
		if ($d < 10) $d = "0$d";
		if ($m < 10) $m = "0$m";
		
		$fecha_hasta = "$a-$m-$d";
		
		$sql = "UPDATE rh_empleadonivelacion SET FechaHasta = '".$fecha_hasta."' WHERE CodPersona = '".$codpersona."' AND FechaHasta = '0000-00-00'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	--------------------
		
		//	INSERTO NIVELACION
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleadonivelacion", $codpersona);
		$sql="INSERT INTO rh_empleadonivelacion (CodPersona, Secuencia, Fecha, CodOrganismo, CodDependencia, CodCargo, CodTipoNom, TipoAccion, Motivo, Responsable, Documento, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$fecha."', '".$organismo."', '".$dependencia."', '".$cargo."', '".$nomina."', '".$tipo_accion."', '".$motivo."', '".$responsable."', '".$documento."', 'A', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	
		//	INSERTO HISTORIAL DE NIVELACION
		$sql="SELECT ren.CodPersona, mmd.Descripcion AS TipoAccion, mp.NomCompleto AS Responsable, me.CodEmpleado, me.Fingreso, me.Fegreso, me.ObsCese, me.Estado, mo.Organismo, md.Dependencia, tn.Nomina, rp.DescripCargo, rp.NivelSalarial, mmd2.Descripcion AS CatCargo, mtp.TipoPago, rmc.MotivoCese FROM rh_empleadonivelacion ren INNER JOIN mastpersonas mp ON (ren.Responsable=mp.CodPersona) INNER JOIN mastmiscelaneosdet mmd ON (ren.TipoAccion=mmd.CodDetalle AND CodMaestro='TIPOACCION') INNER JOIN mastempleado me ON (ren.CodPersona=me.CodPersona) INNER JOIN masttipopago mtp ON (me.CodTipoPago=mtp.CodTipoPago) LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastmiscelaneosdet mmd2 ON (rp.CategoriaCargo=mmd2.CodDetalle AND mmd2.CodMaestro='CATCARGO') WHERE ren.CodPersona='".$codpersona."' AND ren.Secuencia='".$secuencia."'";
		$query_datos=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_datos)!=0) $field=mysql_fetch_array($query_datos);
		//	-------------------------
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleadonivelacionhistorial", $codpersona);
		$sql="INSERT INTO rh_empleadonivelacionhistorial (CodPersona, Secuencia, Fecha, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoAccion, Motivo, Responsable, Documento, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$fecha."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CatCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoAccion'])."', '".utf8_decode($motivo)."', '".utf8_decode($field['Responsable'])."', '".utf8_decode($documento)."', 'A', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	INSERTO HISTORIAL DE EMPLEADO
		//	-------------------------
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_historial", $codpersona);
		$sql="INSERT INTO rh_historial (CodPersona, Secuencia, Periodo, Fingreso, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoPago, Estado, MotivoCese, Fegreso, ObsCese, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$periodo."', '".$field['Fingreso']."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CatCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoPago'])."', '".$field['Estado']."', '".utf8_decode($field['MotivoCese'])."', '".$field['Fegreso']."', '".utf8_decode($field['ObsCese'])."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	} else {
		//	ACTUALIZO EMPLEADO
		$sql="UPDATE mastempleado SET CodOrganismo='".$organismo."', CodDependencia='".$dependencia."', CodCargoTemp = '".$cargo."', CodTipoNom='".$nomina."' WHERE CodPersona='".$codpersona."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());	
		
		//	ACTUALIZO NIVELACION
		$a = (int) $annio; $m = (int) $mes; $d = (int) $dia;
		$d = $d - 1;
		if ($d == 0) {
			$m = $m - 1;
			if ($m == 0) {
				$a = $a - 1;
				$m = 12;
			}
			$d = getDiasMes($a, $m);
		}
		if ($d < 10) $d = "0$d";
		if ($m < 10) $m = "0$m";
		
		$fecha_hasta = "$a-$m-$d";
		
		$sql = "UPDATE rh_empleadonivelacion SET FechaHasta = '".$fecha_hasta."' WHERE CodPersona = '".$codpersona."' AND FechaHasta = '0000-00-00' AND TipoAccion = 'ET'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	--------------------
		
		//	INSERTO NIVELACION
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleadonivelacion", $codpersona);
		$sql="INSERT INTO rh_empleadonivelacion (CodPersona, Secuencia, Fecha, CodOrganismo, CodDependencia, CodCargo, CodTipoNom, TipoAccion, Motivo, Responsable, Documento, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$fecha."', '".$organismo."', '".$dependencia."', '".$cargo."', '".$nomina."', '".$tipo_accion."', '".$motivo."', '".$responsable."', '".$documento."', 'A', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	
		//	INSERTO HISTORIAL DE NIVELACION
		$sql="SELECT ren.CodPersona, mmd.Descripcion AS TipoAccion, mp.NomCompleto AS Responsable, me.CodEmpleado, me.Fingreso, me.Fegreso, me.ObsCese, me.Estado, mo.Organismo, md.Dependencia, tn.Nomina, rp.DescripCargo, rp.NivelSalarial, mmd2.Descripcion AS CatCargo, mtp.TipoPago, rmc.MotivoCese FROM rh_empleadonivelacion ren INNER JOIN mastpersonas mp ON (ren.Responsable=mp.CodPersona) INNER JOIN mastmiscelaneosdet mmd ON (ren.TipoAccion=mmd.CodDetalle AND CodMaestro='TIPOACCION') INNER JOIN mastempleado me ON (ren.CodPersona=me.CodPersona) INNER JOIN masttipopago mtp ON (me.CodTipoPago=mtp.CodTipoPago) LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) INNER JOIN rh_puestos rp ON (me.CodCargoTemp=rp.CodCargo) INNER JOIN mastmiscelaneosdet mmd2 ON (rp.CategoriaCargo=mmd2.CodDetalle AND mmd2.CodMaestro='CATCARGO') WHERE ren.CodPersona='".$codpersona."' AND ren.Secuencia='".$secuencia."'";
		$query_datos=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_datos)!=0) $field=mysql_fetch_array($query_datos);
		//	-------------------------
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_empleadonivelacionhistorial", $codpersona);
		$sql="INSERT INTO rh_empleadonivelacionhistorial (CodPersona, Secuencia, Fecha, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoAccion, Motivo, Responsable, Documento, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$fecha."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CatCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoAccion'])."', '".utf8_decode($motivo)."', '".utf8_decode($field['Responsable'])."', '".utf8_decode($documento)."', 'A', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	INSERTO HISTORIAL DE EMPLEADO
		//	-------------------------
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_historial", $codpersona);
		$sql="INSERT INTO rh_historial (CodPersona, Secuencia, Periodo, Fingreso, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoPago, Estado, MotivoCese, Fegreso, ObsCese, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".$periodo."', '".$field['Fingreso']."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CatCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoPago'])."', '".$field['Estado']."', '".utf8_decode($field['MotivoCese'])."', '".$field['Fegreso']."', '".utf8_decode($field['ObsCese'])."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
}

//	ANTECEDENTES DEL EMPLEADO
elseif ($_POST['modulo']=="EMPLEADO-ANTECEDENTES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $fingreso); $fingreso=$annio.$mes.$dia;
	list($dia, $mes, $annio)=SPLIT( '[/.-]', $fegreso); $fegreso=$annio.$mes.$dia;
	//	---------------------------------
	//	Guardar nuevo antecedente
	if ($accion == "GUARDAR") {
		//	Verifico que no haya coincidencia con las fechas
		$sql = "SELECT * FROM rh_empleado_antecedentes WHERE (CodPersona = '".$codpersona."') AND (('".$fingreso."' >= FIngreso AND '".$fingreso."' <= FEgreso) OR ('".$fegreso."' >= FIngreso AND '".$fegreso."' <= FEgreso) OR (FIngreso >= '".$fingreso."' AND FIngreso <= '".$fegreso."') OR (FEgreso >= '".$fingreso."' AND FEgreso <= '".$fegreso."'))";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error ="SE ENCONTRARON ANTECEDENTES QUE COINCIDEN CON LAS FECHAS";
		else {
			$sql = "INSERT INTO rh_empleado_antecedentes (Secuencia, CodPersona, Organismo, Fingreso, Fegreso, UltimoUsuario, UltimaFecha) VALUES ('".$secuencia."', '".$codpersona."', '".utf8_decode($organismo)."', '".$fingreso."', '".$fegreso."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	// Consulto los datos para editarlo en el formulario...
	elseif ($accion == "EDITAR") {
		$sql = "SELECT * FROM rh_empleado_antecedentes WHERE CodPersona = '".$codpersona."' AND Secuencia = '".$secuencia."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) {
			$field = mysql_fetch_array($query);
			list($anio, $mes, $dia)=SPLIT( '[/.-]', $field['FIngreso']); $fingreso = "$dia-$mes-$anio";
			list($anio, $mes, $dia)=SPLIT( '[/.-]', $field['FEgreso']); $fegreso = "$dia-$mes-$anio";
			echo "$error|:|".$field['Organismo']."|:|$fingreso|:|$fegreso|:|".$field['Secuencia'];
		}
	}
	//	Actualizo un antecedente editado....
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no haya coincidencia con las fechas
		$sql = "SELECT * FROM rh_empleado_antecedentes WHERE (Secuencia <> '".$secuencia."') AND (CodPersona = '".$codpersona."') AND (('".$fingreso."' >= FIngreso AND '".$fingreso."' <= FEgreso) OR ('".$fegreso."' >= FIngreso AND '".$fegreso."' <= FEgreso))";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error ="SE ENCONTRARON ANTECEDENTES QUE COINCIDEN CON LAS FECHAS";
		else {
			$sql = "UPDATE rh_empleado_antecedentes SET Organismo = '".utf8_decode($organismo)."', FIngreso = '".$fingreso."', FEgreso = '".$fegreso."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '$ahora' WHERE CodPersona = '".$codpersona."' AND Secuencia = '".$secuencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	// Elimino el antececedente del empleado...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM rh_empleado_antecedentes WHERE CodPersona = '".$codpersona."' AND Secuencia = '".$secuencia."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	---------------------------------
}

//	RETENCIONES JUDICIALES
elseif ($modulo == "RETENCIONES-JUDICIALES") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($d, $m, $a)=SPLIT( '[/.-]', $fresolucion); $fresolucion = $a.$m.$d; $periodo="$a-$m";
	
	//	GUARDAR
	if ($accion == "GUARDAR") {		
		$codretencion = getCodigo("rh_retencionjudicial", "CodRetencion", 6);
		//	INSERTO EL NUEVO REGISTRO
		$sql = "INSERT INTO rh_retencionjudicial (CodRetencion, 
													CodOrganismo, 
													CodPersona, 
													Expediente, 
													FechaResolucion, 
													TipoRetencion, 
													Juzgado, 
													Demandante, 
													CodTipoPago, 
													Estado, 
													Observaciones,
													UltimoUsuario, 
													UltimaFecha)  
											VALUES ('".$codretencion."', 
													'".$organismo."', 
													'".$codempleado."', 
													'".$expediente."', 
													'".$fresolucion."', 
													'".$tipo."',
													'".utf8_decode($juzgado)."', 
													'".$coddemandante."', 
													'".$tipo_pago."',  
													'".$estado."',  
													'".utf8_decode($comentarios)."', 
													'".$_SESSION['USUARIO_ACTUAL']."', 
													'$ahora')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		//	INSERTO LOS CONCEPTOS
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codconcepto, $porcentaje, $monto) = SPLIT( '[|]', $linea);
			if ($porcentaje != "0.00") { $tipo_descuento = "P"; $descuento = $porcentaje; }
			elseif ($monto != "0.00") { $tipo_descuento = "M"; $descuento = $monto; }
			
			//	INSERTO EL CONCEPTO DE LA RETENCION
			$sql = "INSERT INTO rh_retencionjudicialconceptos 
						(CodOrganismo,
						 CodRetencion,
						 TipoDescuento,
						 Descuento,
						 CodConcepto,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$codretencion."',
						 '".$tipo_descuento."',
						 '".$descuento."',
						 '".$codconcepto."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 '".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());			
			
			//	SELECCIONO LOS PROCESOS QUE APLICAN A ESTE CONCEPTO
			$sql = "SELECT * FROM pr_conceptoproceso WHERE CodConcepto = '".$codconcepto."'";
			$query_procesos = mysql_query($sql) or die ($sql.mysql_error());
			$procesos = "";
			while ($field_procesos = mysql_fetch_array($query_procesos)) {
				$procesos .= $field_procesos['CodTipoProceso']." "; 
			}
			
			//	ELIMINO CUALQUIER RETENCION JUDICIAL ASIGNADA IGUAL
			$sql = "DELETE FROM pr_empleadoconcepto WHERE CodConcepto = '".$codconcepto."' AND CodPersona = '".$codempleado."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			
			//	INSERTO EN NOMINA EL CONCEPTO
			$secuencia = getSecuencia("Secuencia", "CodPersona", "pr_empleadoconcepto", $codempleado);		
			$sql = "INSERT INTO pr_empleadoconcepto 
						(CodPersona,
						 CodConcepto,
						 Secuencia,
						 TipoAplicacion,
						 PeriodoDesde,
						 FlagTipoProceso,
						 Estado,
						 Procesos,
						 UltimoUsuario,
						 UltimaFecha) 
					VALUES 
						('".$codempleado."',
						 '".$codconcepto."',
						 '".$secuencia."',
						 'P',
						 '".$periodo."',
						 'N',
						 'A', 
						 '".$procesos."',
						 '".$_SESSION['USUARIO_ACTUAL']."', 
						 '$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	
	//	ACTUALIZAR REGISTRO
	elseif ($accion == "ACTUALIZAR") {
		//	ACTUALIZO EL REGISTRO
		$sql = "UPDATE rh_retencionjudicial 
				SET 
					CodOrganismo = '".$organismo."', 
					CodPersona = '".$codempleado."', 
					Expediente = '".$expediente."', 
					FechaResolucion = '".$fresolucion."', 
					TipoRetencion = '".$tipo."', 
					Juzgado = '".utf8_decode($juzgado)."', 
					Demandante = '".$coddemandante."', 
					CodTipoPago = '".$tipo_pago."', 
					Observaciones = '".utf8_decode($comentarios)."', 
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
					UltimaFecha = '$ahora'
				WHERE CodRetencion = '".$codretencion."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		//	ELIMINAR LOS CONCEPTOS
		$sql = "DELETE FROM rh_retencionjudicialconceptos WHERE CodOrganismo = '".$organismo."' AND CodRetencion = '".$codretencion."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		//	INSERTO LOS CONCEPTOS
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codconcepto, $porcentaje, $monto) = SPLIT( '[|]', $linea);
			if ($porcentaje != "0.00") { $tipo_descuento = "P"; $descuento = $porcentaje; }
			elseif ($monto != "0.00") { $tipo_descuento = "M"; $descuento = $monto; }
			
			//	INSERTO EL CONCEPTO DE LA RETENCION
			$sql = "INSERT INTO rh_retencionjudicialconceptos 
						(CodOrganismo,
						 CodRetencion,
						 TipoDescuento,
						 Descuento,
						 CodConcepto,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$codretencion."',
						 '".$tipo_descuento."',
						 '".$descuento."',
						 '".$codconcepto."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 '".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	
	//	ELIMINAR REGISTRO
	elseif ($accion == "ELIMINAR") {
		//	ACTUALIZO EL REGISTRO
		$sql = "DELETE FROM rh_retencionjudicialconceptos WHERE CodRetencion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM rh_retencionjudicial WHERE CodRetencion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
}

//	PROCESO DE JUBILACION
elseif ($modulo == "PROCESO-JUBILACION") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	
	//	GUARDAR
	if ($accion == "ABRIR-PROCESAR") {
		//	VERIFICO EL ESTADO DE LA JUBILACION DEL EMPLEADO
		$sql = "SELECT Estado FROM rh_proceso_jubilacion WHERE CodPersona = '".$codpersona."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) {
			$field = mysql_fetch_array($query);
			if ($field['Estado'] == "P") $error = "EL EMPLEADO YA SE ENCUENTRA EN PROCESO DE JUBILACION";
			elseif ($field['Estado'] == "A") $error = "LA JUBILACION DEL EMPLEADO YA SE ENCUENTRA APROBADO";
		}
	}
	elseif ($accion == "ABRIR-APROBAR") {
		//	VERIFICO EL ESTADO DE LA JUBILACION DEL EMPLEADO
		$sql = "SELECT Estado FROM rh_proceso_jubilacion WHERE CodPersona = '".$codpersona."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) {
			$field = mysql_fetch_array($query);
			if ($field['Estado'] == "A") $error = "LA JUBILACION DEL EMPLEADO YA SE ENCUENTRA APROBADO";
		} else $error = "AL EMPLEADO NO SE LE HA PROCESADO SU JUBILACION";
	}
	//	PRCCESAR JUBILACION
	elseif ($accion == "PROCESAR") {
		//	INSERTO EL NUEVO REGISTRO
		$sql = "INSERT INTO rh_proceso_jubilacion (CodOrganismo, 
													CodPersona, 
													AniosServicio, 
													Edad,
													MontoJubilacion, 
													Coeficiente, 
													Estado, 
													FechaProcesado, 
													ProcesadoPor, 
													ObsProcesado, 
													UltimoUsuario, 
													UltimaFecha)
											VALUES ('".$organismo."', 
													'".$codpersona."', 
													'".$anios_servicio."', 
													'".$edad."', 
													'".$monto_jubilacion."',
													'".$coeficiente."', 
													'P', 
													'".date("Y-m-d")."', 
													'".$procesadopor."', 
													'".$obsprocesado."', 
													'".$_SESSION['USUARIO_ACTUAL']."', 
													'$ahora')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	APROBAR JUBILACION
	elseif ($accion == "APROBAR") {
		list($d, $m, $a)=SPLIT( '[/.-]', $fcese); $fcese = $a.$m.$d;
		$a=(int) $a;
		$m=(int) $m;
		$d=(int) $d;
		$d++;
		$dias_mes = getDiasMes($a, $m);
		if ($d > $dias_mes) { $d=1; $m++; }
		if ($m > 12) { $m=1; $a++; }
		if ($m < 10) $m="0$m";
		if ($d < 10) $d = "0$d";
		$fecha_ingreso_jubilacion = "$a-$m-$d";
		
		//	ACTUALIZO EL ESTADO EN JUBILACACION
		$sql = "UPDATE rh_proceso_jubilacion SET Estado = 'A', 
													FechaAprobado = '".date("Y-m-d")."', 
													AprobadoPor = '".$aprobadopor."', 
													ObsAprobado = '".$obsaprobado."', 
													FechaJubilacion = '".$fecha_ingreso_jubilacion."', 
													UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
													UltimaFecha = '$ahora' 
											WHERE
													CodPersona = '".$codpersona."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		//	ACTUALIZO LOS DATOS DE PLANILLA DEL EMPLEADO
		$sql = "SELECT SueldoActual FROM mastempleado WHERE CodPersona = '".$codpersona."'";
		$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
		
		$sql = "UPDATE mastempleado SET CodTipoNom = '".$nomina."', 
										CodTipoTrabajador = '".$tipo_trabajador."', 
										MontoJubilacion = '".$monto_jubilacion."', 
										FechaJubilacion = '".$fecha_ingreso_jubilacion."', 
										Estado = '".$status."', 
										CodMotivoCes = '".$tcese."', 
										ObsCese = '".utf8_decode($explicacion)."', 
										Fegreso = '".$fcese."',
										SueldoAnterior = '".$field_sueldo['SueldoActual']."',
										SueldoActual = '".$monto_jubilacion."'
									WHERE 
										CodPersona = '".$codpersona."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		//	INSERTO EL HISTORIAL
		$sql="SELECT mp.CodPersona, me.Fingreso, me.Estado, me.Fegreso, me.ObsCese, mo.Organismo, md.Dependencia, tn.Nomina, rp.DescripCargo, rp.NivelSalarial, mmd.Descripcion AS CategoriaCargo, mtp.TipoPago, rtt.TipoTrabajador, rmc.MotivoCese FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN mastorganismos mo ON (me.CodOrganismo=mo.CodOrganismo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) INNER JOIN tiponomina tn ON (me.CodTipoNom=tn.CodTipoNom) INNER JOIN rh_tipotrabajador rtt ON (me.CodTipoTrabajador=rtt.CodTipoTrabajador) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastmiscelaneosdet mmd ON (rp.CategoriaCargo=mmd.CodDetalle AND mmd.CodMaestro='CATCARGO') INNER JOIN masttipopago mtp ON (me.CodTipoPago=mtp.CodTipoPago) LEFT JOIN rh_motivocese rmc ON (me.CodMotivoCes=rmc.CodMotivoCes) WHERE mp.CodPersona='".$codpersona."'";
		$query_datos=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_datos)!=0) $field=mysql_fetch_array($query_datos);
		//	-------------------------
		$secuencia=getSecuencia("Secuencia", "CodPersona", "rh_historial", $codpersona);
		$sql="INSERT INTO rh_historial (CodPersona, Secuencia, Periodo, Fingreso, Organismo, Dependencia, Cargo, NivelSalarial, CategoriaCargo, TipoNomina, TipoPago, TipoTrabajador, Estado, MotivoCese, Fegreso, ObsCese, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$secuencia."', '".date("Y-m")."', '".$fingreso."', '".utf8_decode($field['Organismo'])."', '".utf8_decode($field['Dependencia'])."', '".utf8_decode($field['DescripCargo'])."', '".$field['NivelSalarial']."', '".utf8_decode($field['CategoriaCargo'])."', '".utf8_decode($field['Nomina'])."', '".utf8_decode($field['TipoPago'])."', '".utf8_decode($field['TipoTrabajador'])."', '".$field['Estado']."', '".utf8_decode($field['MotivoCese'])."', '".$field['Fegreso']."', '".utf8_decode($field['ObsCese'])."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
	}
	echo $error;
}

//	MAESTRO DE FERIADOS
elseif ($modulo == "FERIADOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	Guardar registro....
	if ($accion == "GUARDAR") {
		if ($flagvariable == "N") $anio = "";
		$codigo = getCodigo("rh_feriados", "CodFeriado", 4);
		$sql = "INSERT INTO rh_feriados (CodFeriado, 
										AnioFeriado, 
										DiaFeriado, 
										Descripcion, 
										FlagVariable,
										Estado, 
										UltimoUsuario, 
										UltimaFecha) 
								VALUES ('".$codigo."', 
										'".$anio."', 
										'".$feriado."', 
										'".utf8_decode($descripcion)."', 
										'".$flagvariable."', 
										'".$estado."', 
										'".$_SESSION['USUARIO_ACTUAL']."', 
										'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	Modificar registro....
	elseif ($accion == "ACTUALIZAR") {
		if ($flagvariable == "N") $anio = "";
		$sql = "UPDATE rh_feriados SET AnioFeriado = '".$anio."', 
										DiaFeriado = '".$feriado."',  
										Descripcion = '".utf8_decode($descripcion)."',  
										FlagVariable = '".$flagvariable."', 
										Estado = '".$estado."', 
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',  
										UltimaFecha = '".date("Y-m-d H:i:s")."'
									WHERE 
										CodFeriado = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	Eliminar registro....
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM rh_feriados WHERE CodFeriado = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
}

//	MAESTRO DE GRADO SALARIAL
elseif ($modulo == "GRADO-SALARIAL") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//	Guardar registro....
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM rh_nivelsalarial WHERE CategoriaCargo = '".$categoria."' AND (Grado = '".$grado."' OR Descripcion = '".utf8_decode($descripcion)."')";
		$query_valido = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_valido) != 0) $error = "REGISTRO YA EXISTE";
		else {
			$sql = "INSERT INTO rh_nivelsalarial (Grado, 
													CategoriaCargo, 
													Descripcion, 
													SueldoMinimo, 
													SueldoMaximo, 
													SueldoPromedio, 
													Estado, 
													UltimoUsuario, 
													UltimaFecha) 
											VALUES ('".$grado."', 
													'".$categoria."', 
													'".utf8_decode($descripcion)."', 
													'".number_format($sueldo_minimo, 2, '.', '')."', 
													'".number_format($sueldo_maximo, 2, '.', '')."', 
													'".number_format($sueldo_promedio, 2, '.', '')."', 
													'".$estado."', 
													'".$_SESSION['USUARIO_ACTUAL']."', 
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	Modificar registro....
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM rh_nivelsalarial WHERE (Descripcion = '".utf8_decode($descripcion)."') AND CodNivel <> '".$codigo."'";
		$query_valido = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_valido) != 0) $error = "REGISTRO YA EXISTE";
		else {
			$sql = "UPDATE rh_nivelsalarial SET Descripcion = '".utf8_decode($descripcion)."', 
												SueldoMinimo = '".number_format($sueldo_minimo, 2, '.', '')."', 
												SueldoMaximo = '".number_format($sueldo_maximo, 2, '.', '')."', 
												SueldoPromedio = '".number_format($sueldo_promedio, 2, '.', '')."', 
												Estado = '".$estado."', 
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
												UltimaFecha = '".date("Y-m-d H:i:s")."' 
											WHERE 
												CodNivel = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	Eliminar registro....
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM rh_nivelsalarial WHERE Grado = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
}
//// _____________________________________________________
//// ELIMINAR DATOS MAESTROS CREADOS MODULO ACTIVOS FIJOS
//// _____________________________________________________
if($_POST['accion']=="ELIMINARCATEGORIA"){
  $sql="DELETE FROM af_categoriadeprec WHERE CodCategoria='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  
  $sql="DELETE FROM af_categoriacontabilidad WHERE CodCategoria='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
if($_POST['accion']=="ELIMINARSITU"){
  $sql="DELETE FROM af_situacionactivo WHERE CodSituactivo='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
if($_POST['accion']=="ELIMINARLIBROC"){
  $sql="DELETE FROM ac_librocontable WHERE CodLibroCont='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
if($_POST['accion']=="ELIMINARCONT"){
  $sql="DELETE FROM ac_contabilidades WHERE CodContabilidad='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
if($_POST['accion']=="ELIMINARTVEHICULOS"){
  $sql="DELETE FROM af_tipovehiculo WHERE CodTipoVehiculo='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
if($_POST['accion']=="ELIMINARTS"){
  $sql="DELETE FROM af_tiposeguro WHERE CodTipoSeguro='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
if($_POST['accion']=="ELIMINARCATASTRO"){
  $sql="DELETE FROM af_catastro WHERE CodCatastro='".$_POST['codigo']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// _____________________________________________________
?>