<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/
include ("../fphp_nomina_py.php");
//include("../fphp.php");
connect();




switch ($accion) {
	case 'GUARDAR':
	    $error=0;
		//	Guardar registro...

		$sql = "INSERT INTO py_proceso
							(
								CodProyeccion,
								CodTipoNom,
								CodTipoProceso,
								Periodo,
								Anio,
								Descripcion
							)
							VALUES (
							'".$CodProyeccion."',
							'".$tx_nomina."',
							'".$tx_proceso."',
							'".$tx_periodo."',
							'".$tx_anio."',
							'".$tx_descripcion."'
							)";
		$query = mysql_query($sql) or die ($sql.mysql_error());


	break;

	case 'ELIMINAR':
	    $error=0;
		//	Eliminar registro....
		$sql = "DELETE FROM py_proceso  			WHERE CodProyeccion= '".$CodProyeccion."' AND  CodTipoNom= '".$tx_nomina."'AND
        CodTipoProceso= '".$tx_proceso."' AND Periodo= '".$tx_periodo."'";
 
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		
				$sql = "DELETE FROM `py_empleadoprocesoconcepto`   			
				WHERE 
				CodProyeccion= '".$CodProyeccion."' AND 
				CodTipoNom= '".$tx_nomina."'AND
				CodTipoProceso= '".$tx_proceso."'
				AND Periodo= '".$tx_periodo."'";
 
		$query = mysql_query($sql) or die ($sql.mysql_error());


	break;

	case 'ACTUALIZAR':
	    $error=0;

			//	Modificar registro...
			$sql = "UPDATE py_proceso SET 
											CodProyeccion= '".$tx_proyeccion."',
											CodTipoNom= '".$tx_tiponomina."',
											CodTipoProceso= '".$tx_tipoproceso."',
											Periodo= '".$tx_perido."',
											Anio= '".$tx_anio."',
											Descripcion = '".$tx_descripcion."'
											
											WHERE
											CodProyeccion= '".$CodProyeccion."' AND
											CodTipoNom= '".$CodTipoNom."'AND
											CodTipoProceso= '".$CodTipoProceso."' AND
											Periodo= '".$Periodo."'";
											
			$query = mysql_query($sql) or die ($sql.mysql_error());

	break;


	///////////////////////////////////////////
	case 'QUITAR-SEL-NOMINA':


		$seleccion = explode("|;|", $seleccionados);
		foreach ($seleccion as $registro) {
			list($codpersona)=SPLIT( '[|:|]', $registro);
			//	Elimino los datos del empleado si tiene...
							$sql = "DELETE FROM `py_empleadoprocesoconcepto`   			
				WHERE 
				CodPersona = '".$codpersona."' AND
				CodProyeccion= '".$CodProyeccion."' AND 
				CodTipoNom= '".$CodTipoNom."'AND
				CodTipoProceso= '".$CodTipoProceso."' AND 
				Periodo= '".$Periodo."'";
 
		$query = mysql_query($sql) or die ($sql.mysql_error());
			//	---------------------------
			$sql = "DELETE FROM py_empleadoproceso
					WHERE
					CodPersona = '".$codpersona."' AND
					CodProyeccion= '".$CodProyeccion."' AND 
					CodTipoNom= '".$CodTipoNom."'AND
					CodTipoProceso= '".$CodTipoProceso."' AND 
					Periodo= '".$Periodo."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}

    break;

    ///////////////////////////////////////////
	case 'AGREGAR-SEL-NOMINA':


		$seleccion = explode("|;|", $seleccionados);
		foreach ($seleccion as $registro) {
			list($codpersona)=SPLIT( '[|:|]', $registro);
			//	agrego los datos del empleado si tiene...


		$sql = "INSERT INTO `py_empleadoproceso`
		        (`CodProceso`, `CodPersona`, `SueldoBasico`, `TotalIngresos`, 
		         `TotalEgreso`, `TotalPatronales`, `TotalNeto`,
		          Periodo, CodProyeccion, CodTipoNom, CodTipoProceso)
		        VALUES ('".$proceso."', '".$codpersona."', '0.00', '0.00', '0.00', '0.00', '0.00',
		               '".$Periodo."', '".$CodProyeccion."', '".$CodTipoNom."', '".$CodTipoProceso."')	";

			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}

    break;

     ///////////////////////////////////////////
	case 'PROCESAR-NOMINA':

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*	$empleado = explode("|:|", $aprobados);

	//	Recorro cada empleado seleccionado
	foreach ($empleado as $codpersona) {


	//	Elimino los datos del empleado si tiene...
	$sql = "DELETE FROM py_empleadoprocesoconcepto
			WHERE
				CodPersona = '".$codpersona."' AND
				CodProceso='".$proceso."'";
   $query_delete = mysql_query($sql) or die ($sql.mysql_error());
*/
	/*	$sql ="	SELECT
			tnec.CodTipoNom,
			tnec.Periodo,
			tnec.CodPersona,
			tnec.CodOrganismo,
			tnec.CodTipoProceso,
			tnec.CodConcepto,
			tnec.Monto,
			tnec.Cantidad,
			tnec.Saldo,
			tnec.UltimoUsuario,
			tnec.UltimaFecha
			FROM
			pr_tiponominaempleadoconcepto AS tnec

			where tnec.Periodo = '2014-02' AND
			tnec.CodPersona = '".$codpersona."' AND
			tnec. CodTipoProceso ='FIN'";

			$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
			$rows_conceptos = mysql_num_rows($query_concepto);
			*/
			/*while ($field_concepto = mysql_fetch_array($query_concepto)) {


				$query_insert ="INSERT INTO `py_empleadoprocesoconcepto`
				      (
				      `CodProceso`,
				      `CodPersona`,
				      `CodConcepto`,
				      `Monto`,
				      `MontoP`,
				      `Cantidad`,
				      `Saldo`)
				      VALUES
				      ('".$proceso."',
				       '".$codpersona."',
				       '".$field_concepto['CodConcepto']."',
				       '".$field_concepto['Monto']."',
				       '".$field_concepto['Monto']."',
				       '".$field_concepto['Cantidad']."',
				       '".$field_concepto['Saldo']."')";
				$query_insert = mysql_query($query_insert) or die ($query_insert.mysql_error());

			}*/

      // }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/*"&proceso="+ftproceso+
		          "&ftiponom="+ftiponom+
		          "&fperiodo="+fperiodo+
		          "&ftproyeccion="+ftproyeccion*/

		include ("../funciones_globales_nomina_py.php");

		$organismo= '0001';  // ENVIARLO POR PARAMETROS

		$_PARAMETROS = PARAMETROS();
		$TOTAL_ASIGNACIONES = 0;
		$TOTAL_DEDUCCIONES = 0;
		$TOTAL_PATRONALES = 0;
		$TOTAL_PROVISIONES = 0;
		$PORCENTAJE_PY = 0;
		$ALIVAC = 0;
		$empleado = explode("|:|", $aprobados); //Lsto
		
		//////////////PROYECCION /////////////////////////////////////////////////////////////
				$sql= "SELECT * FROM py_conceptoporcentaje where 
				       py_conceptoporcentaje.CodProyeccion = '".$ftproyeccion."'  
				       AND py_conceptoporcentaje.Desde >= '".$periodo."' 
				       AND '".$periodo."' <=py_conceptoporcentaje.Hasta 
				       AND py_conceptoporcentaje.CodConcepto ='0001'";
				$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
				$rows_conceptos = mysql_num_rows($query_concepto);
				while ($field_concepto = mysql_fetch_array($query_concepto)) {
					$PORCENTAJE_PY = $field_concepto['Porcentaje']/100;
				}
        //////////////////////////////////////////////////////////////////////////////////////

		//	Variables usadas en la formula...
		
		$_ARGS['PROYECCION'] = $ftproyeccion; //Lsto.
		$_ARGS['NOMINA'] = $tiponom; //Lsto
		$_ARGS['PERIODO'] = $periodo; //Lsto
		$_ARGS['PROCESO'] = $proceso; //Lsto
		$_PROCESO = $proceso;
		$_ARGS['ORGANISMO'] = $organismo; //Lsto - falta enviarlo por parametros

		//list($_ARGS['DESDE'], $_ARGS['HASTA']) = FECHA_PROCESO($_ARGS);
		list($_ARGS['DESDE'], $_ARGS['HASTA']) = array($_ARGS['PERIODO']."-16", $_ARGS['PERIODO']."-30");
		$_ARGS['DIAS_PROCESO'] = 15;

		/*$_ARGS['DIAS_PROCESO'] = DIAS_PROCESO($_ARGS);
		if ($_ARGS['DIAS_PROCESO'] == 28 || $_ARGS['DIAS_PROCESO'] == 29) {
			$_ARGS['DIAS_PROCESO'] = 30;
			list($a, $m, $d) = split("[-]", $_ARGS['HASTA']);
			$_ARGS['HASTA'] = "$a-$m-$_PARAMETROS[MAXDIASMES]";
		}	*/
		list($_ANO_PROCESO, $_MES_PROCESO, $_DIA_PROCESO) = split("-", $_ARGS['HASTA']);
		$_NOMINA = $tiponom;

		//	Actualizo el proceso



		//	Recorro cada empleado seleccionado
		foreach ($empleado as $codpersona) {


			//	Variables usadas en la formula....
			$_ARGS['TRABAJADOR'] = $codpersona;
			$_ARGS['FECHA_INGRESO'] = FECHA_INGRESO($_ARGS); // FALTA
			$_FECHA_INGRESO = $_ARGS['FECHA_INGRESO'];

			list($_ANO_INGRESO, $_MES_INGRESO, $_DIA_INGRESO) = split("-", $_ARGS['FECHA_INGRESO']);

			$_DIAS_SUELDO_BASICO = DIAS_SUELDO_BASICO($_ARGS); //FALTA
			$_SUELDO_BASICO = SUELDO_BASICO($_ARGS)+ $PORCENTAJE_PY * (SUELDO_BASICO($_ARGS))  ; //FALTA
			
			

			$_SUELDO_BASICO_DIARIO = $_SUELDO_DIARIO / 30; $_SUELDO_BASICO_DIARIO = REDONDEO($_SUELDO_BASICO_DIARIO, 2);
			$_SUELDO_NORMAL_COMPLETO = TOTAL_ASIGNACIONES($_ARGS); // FALTA

			$_SUELDO_NORMAL = 0;

			$_SUELDO_NORMAL_DIARIO = 0;
			$_ARGS['ASIGNACIONES'] = 0;
			$_ARGS['DEDUCCIONES'] = 0;
			$_ARGS['PATRONALES'] = 0;
			$_ARGS['PROVISIONES'] = 0;



			//	Elimino los datos del empleado si tiene...

				$sql = "DELETE FROM py_empleadoprocesoconcepto
				WHERE
				CodPersona = '".$codpersona."' AND
				CodTipoNom = '".$tiponom."' AND
				Periodo = '".$periodo."' AND
				CodProyeccion = '".$_ARGS['PROYECCION']."' AND
				CodTipoProceso = '".$_ARGS['PROCESO']."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());

			//	Obtengo los conceptos del empleado
			$sql = "(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'1' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'I') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'1' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'I' AND pc.FlagAutomatico = 'S' AND
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'I') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'2' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'D') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'2' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'D' AND pc.FlagAutomatico = 'S' AND
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'D') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'3' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'A') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'3' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'A' AND pc.FlagAutomatico = 'S' AND
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'A') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'4' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'P') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'4' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'P' AND pc.FlagAutomatico = 'S' AND
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'P') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					ORDER BY Orden, PlanillaOrden";
//echo $sql;

			$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
			$rows_conceptos = mysql_num_rows($query_concepto);
			while ($field_concepto = mysql_fetch_array($query_concepto)) {
				$_ARGS['CONCEPTO'] = $field_concepto['CodConcepto'];
				$_CODCONCEPTO = $field_concepto['CodConcepto'];
				if (trim($field_concepto['Formula']) == "") {
					$_ARGS['FORMULA'] = "";

					$_ARGS['MONTO'] = $field_concepto['Monto'];

					/////////////////CALCULO PARA LA PRIMA DE AÑOS DE SERVICIOS///////////////////////////////////////////////////
					if($field_concepto['CodConcepto'] == '0001'){}
					////////////////////////////////////////////////////////////////////////////////////////////////////////

					$_ARGS['CANTIDAD'] = $field_concepto['Cantidad'];
				} else {
					//	Variables usadas en la formula....
					$_ARGS['FORMULA'] = $field_concepto['Formula'];
					$_ARGS['FLAGBONO'] = $field_concepto['FlagBono'];
					//if ($_ARGS['FLAGBONO'] == "S") $_ARGS['ASIGNACIONES'] = TOTAL_ASIGNACIONES($_ARGS);
					$_SUELDO_NORMAL = $_ARGS['ASIGNACIONES'];
					$_SUELDO_NORMAL_DIARIO = $_SUELDO_NORMAL / 30; $_SUELDO_NORMAL_DIARIO = REDONDEO($_SUELDO_NORMAL_DIARIO, 2);

					$_MONTO = 0;
					$_CANTIDAD = 0;
					//	Ejecuto la formula del concepto...

					eval($_ARGS['FORMULA']);
                      	
					$_ARGS['MONTO'] = REDONDEO($_MONTO, 2);
					if($field_concepto['CodConcepto'] == '0001'){ $_ARGS['MONTO']  = $_ARGS['MONTO'] +$_ARGS['MONTO'] ;} 
					$_ARGS['CANTIDAD'] = REDONDEO($_CANTIDAD, 2);
					$_CONCEPTO[$_CODCONCEPTO] = $_ARGS['MONTO'];

				}

				if ($_ARGS['MONTO'] != 0) {
					//	Dependendiendo del tipo de concepto (I:INGRESOS; D:DESCUENTOS; A:PATRONALES) voy sumando
					if ($field_concepto['Tipo'] == "I") $TOTAL_ASIGNACIONES += $_ARGS['MONTO'];
					elseif ($field_concepto['Tipo'] == "D") $TOTAL_DEDUCCIONES += $_ARGS['MONTO'];
					elseif ($field_concepto['Tipo'] == "A") $TOTAL_PATRONALES += $_ARGS['MONTO'];
					elseif ($field_concepto['Tipo'] == "P") $TOTAL_PROVISIONES += $_ARGS['MONTO'];

					//	Variables usadas en la formula....
					$_ARGS['ASIGNACIONES'] = $TOTAL_ASIGNACIONES;
					$_ARGS['DEDUCCIONES'] = $TOTAL_DEDUCCIONES;
					$_ARGS['PATRONALES'] = $TOTAL_PATRONALES;
					$_ARGS['PROVISIONES'] = $TOTAL_PROVISIONES;

					// Inserto los valores del concepto del trabajador....
					$sql = "INSERT INTO py_empleadoprocesoconcepto (

										CodTipoNom,
										Periodo,
										CodPersona,
										CodProyeccion,
										CodConcepto,
										CodTipoProceso,
										Monto,
										MontoP,
										Cantidad,
										Saldo

									)
							VALUES (
										'".$_ARGS['NOMINA']."',
										'".$_ARGS['PERIODO']."',
										'".$_ARGS['TRABAJADOR']."',
										'".$_ARGS['PROYECCION']."',
										'".$_ARGS['CONCEPTO']."',
										'".$_ARGS['PROCESO']."',
										'".$_ARGS['MONTO']."',
										'".$_ARGS['MONTO']."',
										'".$_ARGS['CANTIDAD']."',
										'')";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}


			}


			$TOTAL_ASIGNACIONES = 0;
			$TOTAL_DEDUCCIONES = 0;
			$TOTAL_PATRONALES = 0;
			$TOTAL_PROVISIONES = 0;
			$ALIVAC = 0;
			$_ARGS['MONTO'] = 0;
			$_ARGS['CANTIDAD'] = 0;

		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    break;



}


?>
