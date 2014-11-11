<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	items
if ($modulo == "items") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodItem = getCodigo("lg_itemmast", "CodItem", 10);
		$sql = "INSERT INTO lg_itemmast (
							CodItem,
							CodInterno,
							Descripcion,
							CodTipoItem,
							CodUnidad,
							CodUnidadComp,
							CodUnidadEmb,
							CodLinea,
							CodFamilia,
							CodSubFamilia,
							FlagLotes,
							FlagItem,
							FlagKit,
							FlagImpuestoVentas,
							FlagAuto,
							FlagDisponible,
							FlagPresupuesto,
							Imagen,
							CodMarca,
							Color,
							CodProcedencia,
							CodBarra,
							StockMin,
							StockMax,
							CtaInventario,
							CtaGasto,
							CtaVenta,
							PartidaPresupuestal,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodItem."',
							'".$CodInterno."',
							'".$Descripcion."',
							'".$CodTipoItem."',
							'".$CodUnidad."',
							'".$CodUnidadComp."',
							'".$CodUnidadEmb."',
							'".$CodLinea."',
							'".$CodFamilia."',
							'".$CodSubFamilia."',
							'".$FlagLotes."',
							'".$FlagItem."',
							'".$FlagKit."',
							'".$FlagImpuestoVentas."',
							'".$FlagAuto."',
							'".$FlagDisponible."',
							'".$FlagPresupuesto."',
							'".$Imagen."',
							'".$CodMarca."',
							'".$Color."',
							'".$CodProcedencia."',
							'".$CodBarra."',
							'".$StockMin."',
							'".$StockMax."',
							'".$CtaInventario."',
							'".$CtaGasto."',
							'".$CtaVenta."',
							'".$PartidaPresupuestal."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_itemmast
				SET
					CodInterno = '".$CodInterno."',
					Descripcion = '".$Descripcion."',
					CodTipoItem = '".$CodTipoItem."',
					CodUnidad = '".$CodUnidad."',
					CodUnidadComp = '".$CodUnidadComp."',
					CodUnidadEmb = '".$CodUnidadEmb."',
					CodLinea = '".$CodLinea."',
					CodFamilia = '".$CodFamilia."',
					CodSubFamilia = '".$CodSubFamilia."',
					FlagLotes = '".$FlagLotes."',
					FlagItem = '".$FlagItem."',
					FlagKit = '".$FlagKit."',
					FlagImpuestoVentas = '".$FlagImpuestoVentas."',
					FlagAuto = '".$FlagAuto."',
					FlagDisponible = '".$FlagDisponible."',
					FlagPresupuesto= '".$FlagPresupuesto."',
					Imagen = '".$Imagen."',
					CodMarca = '".$CodMarca."',
					Color = '".$Color."',
					CodProcedencia = '".$CodProcedencia."',
					CodBarra = '".$CodBarra."',
					StockMin = '".$StockMin."',
					StockMax = '".$StockMax."',
					CtaInventario = '".$CtaInventario."',
					CtaGasto = '".$CtaGasto."',
					CtaVenta = '".$CtaVenta."',
					PartidaPresupuestal = '".$PartidaPresupuestal."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodItem = '".$CodItem."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		$sql = "DELETE FROM lg_itemmast WHERE CodItem = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	commodity
elseif ($modulo == "commodity") {
	$Descripcion = changeUrl($Descripcion);
	$detalles = changeUrl($detalles);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CommodityMast = getCodigo("lg_commoditymast", "CommodityMast", 3);
		$sql = "INSERT INTO lg_commoditymast (
							Clasificacion,
							CommodityMast,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Clasificacion."',
							'".$CommodityMast."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		if ($detalles != "") {
			$linea = split(";", $detalles);	$_Linea=0;
			foreach ($linea as $registro) {	$_Linea++;
				list($_Codigo, $_CommoditySub, $_Descripcion, $_cod_partida, $_CodCuenta, $_CodClasificacion, $_CodUnidad, $_Estado) = split("[|]", $registro);
				$_Codigo = $CommodityMast.$_CommoditySub;
				
				//	inserto
				$sql = "INSERT INTO lg_commoditysub (
									CommodityMast,
									CommoditySub,
									Codigo,
									Descripcion,
									CodUnidad,
									cod_partida,
									CodCuenta,
									CodClasificacion,
									Estado,
									FlagPresupuesto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CommodityMast."',
									'".$_CommoditySub."',
									'".$_Codigo."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_cod_partida."',
									'".$_CodCuenta."',
									'".$_CodClasificacion."',
									'".$_Estado."',
									'".$FlagPresupuesto."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_commoditymast
				SET
					Clasificacion = '".$Clasificacion."',
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CommodityMast = '".$CommodityMast."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		if ($detalles != "") {
			$linea = split(";", $detalles);	$_Linea=0;
			foreach ($linea as $registro) {	$_Linea++;
				list($_Codigo, $_CommoditySub,$_FlagPresupuesto, $_Descripcion, $_cod_partida, $_CodCuenta, $_CodClasificacion, $_CodUnidad, $_Estado) = split("[|]", $registro);
				if ($_Codigo == "") {
					$_Codigo = $CommodityMast.$_CommoditySub;
					//	inserto
					$sql = "INSERT INTO lg_commoditysub (
										CommodityMast,
										CommoditySub,
										Codigo,
										Descripcion,
										CodUnidad,
										cod_partida,
										CodCuenta,
										CodClasificacion,
										Estado,
										FlagPresupuesto,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CommodityMast."',
										'".$_CommoditySub."',
										'".$_Codigo."',
										'".$_Descripcion."',
										'".$_CodUnidad."',
										'".$_cod_partida."',
										'".$_CodCuenta."',
										'".$_CodClasificacion."',
										'".$_Estado."',
										'".$_FlagPresupuesto."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	actualizo
					$sql = "UPDATE lg_commoditysub
							SET
								Descripcion = '".$_Descripcion."',
								CodUnidad = '".$_CodUnidad."',
								cod_partida = '".$_cod_partida."',
								CodCuenta = '".$_CodCuenta."',
								CodClasificacion = '".$_CodClasificacion."',
								Estado = '".$_Estado."',
								FlagPresupuesto='".$_FlagPresupuesto."',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE Codigo = '".$_Codigo."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		$sql = "DELETE FROM lg_commoditysub WHERE CommodityMast = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM lg_commoditymast WHERE CommodityMast = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	linea
elseif ($modulo == "linea") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodLinea = getCodigo("lg_claselinea", "CodLinea", 6);
		$sql = "INSERT INTO lg_claselinea (
							CodLinea,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodLinea."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_claselinea
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodLinea = '".$CodLinea."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		$sql = "DELETE FROM lg_claselinea WHERE CodLinea = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}	
}

//	familia
elseif ($modulo == "familia") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodFamilia = getCodigo_2("lg_clasefamilia", "CodFamilia", "CodLinea", $CodLinea, 6);
		$sql = "INSERT INTO lg_clasefamilia (
							CodLinea,
							CodFamilia,
							Descripcion,
							CuentaInventario,
							CuentaGasto,
							CuentaVentas,
							PartidaPresupuestal,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodLinea."',
							'".$CodFamilia."',
							'".$Descripcion."',
							'".$CuentaInventario."',
							'".$CuentaGasto."',
							'".$CuentaVentas."',
							'".$PartidaPresupuestal."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_clasefamilia
				SET
					Descripcion = '".$Descripcion."',
					CuentaInventario = '".$CuentaInventario."',
					CuentaGasto = '".$CuentaGasto."',
					CuentaVentas = '".$CuentaVentas."',
					PartidaPresupuestal = '".$PartidaPresupuestal."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		list($CodLinea, $CodFamilia) = split("[.]", $registro);
		$sql = "DELETE FROM lg_clasefamilia
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}	
}

//	sub_familia
elseif ($modulo == "subfamilia") {
	$Descripcion = changeUrl($Descripcion);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	inserto
		$CodSubFamilia = getCodigo_3("lg_clasesubfamilia", "CodSubFamilia", "CodLinea", "CodFamilia", $CodLinea, $CodFamilia, 6);
		$sql = "INSERT INTO lg_clasesubfamilia (
							CodLinea,
							CodFamilia,
							CodSubFamilia,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodLinea."',
							'".$CodFamilia."',
							'".$CodSubFamilia."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_clasesubfamilia
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."' AND
					CodSubFamilia = '".$CodSubFamilia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	eliminar
		list($CodLinea, $CodFamilia, $CodSubFamilia) = split("[.]", $registro);
		$sql = "DELETE FROM lg_clasesubfamilia
				WHERE
					CodLinea = '".$CodLinea."' AND
					CodFamilia = '".$CodFamilia."' AND
					CodSubFamilia = '".$CodSubFamilia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}	
}

//	requerimiento
elseif ($modulo == "requerimiento") {
	$Comentarios = changeUrl($Comentarios);
	$RazonRechazo = changeUrl($RazonRechazo);
	$NomProveedorSugerido = changeUrl($NomProveedorSugerido);
	$detalles = changeUrl($detalles);
	
	//	nuevo
	if ($accion == "nuevo") {
		//	valido errores
		if ($TipoRequerimiento == "01") {
			$i = 0;
			$detalle = split(";char:tr;", $detalles);
			foreach ($detalle as $linea) {
				list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_cod_partida) = split(";char:td;", $linea);
				$var = "$_CodItem.$_CodCentroCosto";
				$item[$i] = $var;
				$j = 0;
				$x = 0;
				for($j=0; $j<=$i; $j++) {
					if ($var == $item[$j]) $x++;
					if ($x > 1) die("Se encontraron varias lineas del Item <strong>$_CodItem</strong> dirigido al Centro de Costo <strong>$_CodCentroCosto</strong>");
				}
				$i++;
			}
		}
		
		//	inserto requerimiento
		##	genero el nuevo codigo
		$CodRequerimiento = getCodigo("lg_requerimientos", "CodRequerimiento", 10);
		$Correlativo = getCodigo_3("lg_requerimientos", "Secuencia", "Anio", "CodDependencia", $Anio, $CodDependencia, 3);
		$Secuencia = intval($Correlativo);
		$CodInternoDependencia = getCodInternoDependencia($CodDependencia);
		$CodInterno = "$CodInternoDependencia-$Correlativo-$Anio";
		##	inserto
		$sql = "INSERT INTO lg_requerimientos (
							CodRequerimiento,
							CodInterno,
							CodOrganismo,
							CodDependencia,
							CodCentroCosto,
							CodAlmacen,
							Clasificacion,
							Prioridad,
							TipoClasificacion,
							PreparadaPor,
							FechaRequerida,
							FechaPreparacion,
							Comentarios,
							Anio,
							Secuencia,
							FlagCajaChica,
							ProveedorSugerido,
							ClasificacionOC,
							ProveedorDocRef,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodRequerimiento."',
							'".$CodInterno."',
							'".$CodOrganismo."',
							'".$CodDependencia."',
							'".$CodCentroCosto."',
							'".$CodAlmacen."',
							'".$Clasificacion."',
							'".$Prioridad."',
							'".$TipoClasificacion."',
							'".$PreparadaPor."',
							'".formatFechaAMD($FechaRequerida)."',
							'".formatFechaAMD($FechaPreparacion)."',
							'".$Comentarios."',
							'".$Anio."',
							'".$Secuencia."',
							'".$FlagCajaChica."',
							'".$ProveedorSugerido."',
							'".$ClasificacionOC."',
							'".$ProveedorDocRef."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_cod_partida) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_requerimientosdet (
								CodRequerimiento,
								Secuencia,
								CodOrganismo,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								FlagExonerado,
								FlagCompraAlmacen,
								CodCentroCosto,
								Anio,
								Estado,
								CodCuenta,
								cod_partida,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodRequerimiento."',
								'".++$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodItem."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadPedida."',
								'".$_FlagExonerado."',
								'".$_FlagCompraAlmacen."',
								'".$_CodCentroCosto."',
								'".$Anio."',
								'PR',
								'".$_CodCuenta."',
								'".$_cod_partida."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		if ($detalles_anterior != "") {
			##	detalles seleccionados
			$detalle = split(";char:tr;", $detalles_anterior);
			foreach ($detalle as $linea) {
				list($_Requerimiento, $_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_CodCuenta, $_cod_partida, $_Comentarios) = split(";char:td;", $linea);
				list($_CodRequerimiento, $_Secuencia) = split("[.]", $_Requerimiento);
				##	actualizo
				$sql = "UPDATE lg_requerimientosdet 
						SET FlagCompraAlmacen = 'A'
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_Secuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		##
		die("|Se ha generado el Requerimiento <strong>Nro. $CodInterno</strong>");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	valido errores
		if ($TipoRequerimiento == "01") {
			$i = 0;
			$detalle = split(";char:tr;", $detalles);
			foreach ($detalle as $linea) {
				list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_cod_partida) = split(";char:td;", $linea);
				$var = "$_CodItem.$_CodCentroCosto";
				$item[$i] = $var;
				$j = 0;
				$x = 0;
				for($j=0; $j<=$i; $j++) {
					if ($var == $item[$j]) $x++;
					if ($x > 1) die("Se encontraron varias lineas del Item <strong>$_CodItem</strong> dirigido al Centro de Costo <strong>$_CodCentroCosto</strong>");
				}
				$i++;
			}
		}
		
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					CodCentroCosto = '".$CodCentroCosto."',
					CodAlmacen = '".$CodAlmacen."',
					Prioridad = '".$Prioridad."',
					FechaRequerida = '".formatFechaAMD($FechaRequerida)."',
					Comentarios = '".$Comentarios."',
					PreparadaPor = '".$PreparadaPor."',
					FlagCajaChica = '".$FlagCajaChica."',
					ProveedorSugerido = '".$ProveedorSugerido."',
					ClasificacionOC = '".$ClasificacionOC."',
					ProveedorDocRef = '".$ProveedorDocRef."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		##	elimino
		$sql = "DELETE FROM lg_requerimientosdet WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_FlagCompraAlmacen, $_CodCuenta, $_cod_partida) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_requerimientosdet (
								CodRequerimiento,
								Secuencia,
								CodOrganismo,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								FlagExonerado,
								FlagCompraAlmacen,
								CodCentroCosto,
								Anio,
								Estado,
								CodCuenta,
								cod_partida,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodRequerimiento."',
								'".++$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodItem."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadPedida."',
								'".$_FlagExonerado."',
								'".$_FlagCompraAlmacen."',
								'".$_CodCentroCosto."',
								'".$Anio."',
								'PR',
								'".$_CodCuenta."',
								'".$_cod_partida."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'RV',
					RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	conformar
	elseif ($accion == "conformar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					FlagCajaChica = '".$FlagCajaChica."',
					Estado = 'CN',
					ConformadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaConformacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	// VERIFICAR CAJA CHICA
	elseif ($accion == "verificarCC") {
		//	modifico requerimiento
		if ($FlagCajaChica=='N') 
		{
			$sql = "UPDATE lg_requerimientos
				SET
					FlagCC = 'S',
					FlagCajaChica = '".$FlagCajaChica."',
					-- Estado = 'CN',
					-- ConformadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					-- FechaConformacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
						
		} else {
						
			$sql = "UPDATE lg_requerimientos
				SET
					FlagCC = 'S',
					FlagCajaChica = '".$FlagCajaChica."',
					FechaCheckCajaChica  = NOW(),
					CheckCajaChicaPor  = '".$_SESSION["USUARIO_ACTUAL"]."',
					-- Estado = 'CN',
					-- ConformadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					-- FechaConformacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} 

	}
	//verificar de partidas
   elseif ($accion == "verificarP") {
			
			$sql = "UPDATE lg_requerimientos
				SET
					FlagP = '".$FlagP."',
					
					FechaCheckPresupuesto  = NOW(),
					CheckPresupuestoPor  = '".$_SESSION["USUARIO_ACTUAL"]."',
					
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));

	}
	//	aprobar
	elseif ($accion == "aprobar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'AP',
					AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	si va dirigido a sotck de almacen
		if ($Clasificacion == $_PARAMETRO["CLASTOCK"]) {
			$sql = "SELECT *
					FROM lg_requerimientosdet
					WHERE CodRequerimiento = '".$CodRequerimiento."'
					ORDER BY Secuencia";
			$query_det = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_det = mysql_fetch_array($query_det)) {
				$StockComprometido = $field_det['CantidadPedida'] * (-1);
				//	consulto si existe el item en el almacen ya ingresado
				$sql = "SELECT *
						FROM lg_itemalmacen
						WHERE
							CodItem = '".$field_det['CodItem']."' AND
							CodAlmacen = '".$CodAlmacen."'";
				$query_item = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_item) == 0) {
					//	si no existe inserto el item en el almacen
					$sql = "INSERT INTO lg_itemalmacen (
										CodItem,
										CodAlmacen,
										Estado,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$field_det['CodItem']."',
										'".$CodAlmacen."',
										'A',
										'".$_SESSION['USUARIO_ACTUAL']."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	inserto en el inventario
					$sql = "INSERT INTO lg_itemalmaceninv (
										CodItem,
										CodAlmacen,
										StockComprometido,
										DocReferencia,
										FechaIngreso,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$field_det['CodItem']."',
										'".$CodAlmacen."',
										'".$StockComprometido."',
										'RQ-$CodRequerimiento',
										NOW(),
										'".$_SESSION['USUARIO_ACTUAL']."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	actualizo el inventario
					$sql = "UPDATE lg_itemalmaceninv
							SET 
								StockComprometido = (StockComprometido + $StockComprometido),
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()
							WHERE
								CodAlmacen = '".$CodAlmacen."' AND
								CodItem = '".$field_det['CodItem']."'";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		
		//	si selecciono proveedor sugerido
		if ($ProveedorSugerido != "") {
			//	numero de cotizacion proveedor
			$Numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
			$NroCotizacionProv = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
			$CodFormaPago = getValorCampo("mastproveedores", "CodProveedor", "CodFormaPago", $ProveedorSugerido);
			$NroInvitaciones = 1;
			$FechaLimite = getFechaFin(formatFechaDMA(substr(ahora(), 0, 10)), $_PARAMETRO['DIASLIMCOT']);
			
			//	consulto detalles
			$sql = "SELECT *
					FROM lg_requerimientosdet
					WHERE CodRequerimiento = '".$CodRequerimiento."'
					ORDER BY Secuencia";
			$query_det = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_det = mysql_fetch_array($query_det)) {
				//	numero de invitacines y el numero de cotizacion
				$CotizacionNumero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
				##
				$CodFormaPago = getValorCampo("mastproveedores", "CodProveedor", "CodFormaPago", $ProveedorSugerido);
				//	inserto cotizacion
				$sql = "INSERT INTO lg_cotizacion (
									CodOrganismo,
									CodRequerimiento,
									Secuencia,
									CotizacionNumero,
									Numero,
									CodProveedor,
									NomProveedor,
									CodFormaPago,
									Observaciones,
									Cantidad,
									Estado,
									NroCotizacionProv,
									FlagAsignado,
									FlagExonerado,
									FechaInvitacion,
									FechaDocumento,
									UltimoUsuario,
									UltimaFecha,
									NumeroInvitacion,
									FechaEntrega,
									FechaLimite
						) VALUES (
									'".$field_det['CodOrganismo']."',
									'".$field_det['CodRequerimiento']."',
									'".$field_det['Secuencia']."',
									'".$CotizacionNumero."',
									'".$Numero."',
									'".$ProveedorSugerido."',
									'".($NomProveedorSugerido)."',
									'".$CodFormaPago."',
									'".($field_det['Comentarios'])."',
									'".$field_det['CantidadPedida']."',
									'A',
									'".$NroCotizacionProv."',
									'S',
									'".$field_det['FlagExonerado']."',
									NOW(),
									NOW(),
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW(),
									'AUTOMATICO',
									'".formatFechaAMD($FechaLimite)."',
									'".formatFechaAMD($FechaLimite)."'
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	
			$sql = "UPDATE lg_requerimientosdet
					SET CotizacionRegistros = '1'
					WHERE CodRequerimiento = '".$CodRequerimiento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'RE',
					RazonRechazo = '".$RazonRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	anular
	elseif ($accion == "anular") {
		if ($Estado == "PR") {
			$EstadoRequerimiento = "AN";
			$EstadoDetalle = "AN";
		}
		elseif ($Estado != "PR") {
			$EstadoRequerimiento = "PR";
			$EstadoDetalle = "PR";
		}
		
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = '".$EstadoRequerimiento."',
					RazonRechazo = '".$RazonRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = '".$EstadoDetalle."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_requerimientos
				SET
					Estado = 'CE',
					RazonRechazo = '".$RazonRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle") {
		list($CodRequerimiento, $Secuencia) = split("[.]", $registro);
		//	verifico los detalles
		$sql = "SELECT Estado
				FROM lg_requerimientosdet
				WHERE
					CodRequerimiento = '".$CodRequerimiento."' AND
					Secuencia = '".$Secuencia."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
		##
		//	modifico detalles
		$sql = "UPDATE lg_requerimientosdet
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodRequerimiento = '".$CodRequerimiento."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		//	consulto si no quedan pendientes en el requerimiento
		$sql = "SELECT Estado
				FROM lg_requerimientosdet
				WHERE
					CodRequerimiento = '".$CodRequerimiento."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) {
			//	consulto si se completaron algunas lineas en el requerimiento
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Estado = 'CO'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) != 0) {
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'CO'
						WHERE CodRequerimiento = '".$CodRequerimiento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'CE'
						WHERE CodRequerimiento = '".$CodRequerimiento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
}

//	orden de compra
elseif ($modulo == "orden_compra") {
	$Observaciones = changeUrl($Observaciones);
	$ObsDetallada = changeUrl($ObsDetallada);
	$MotRechazo = changeUrl($MotRechazo);
	$NomProveedor = changeUrl($NomProveedor);
	$MontoAfecto = ($MontoAfecto);
	$MontoNoAfecto = ($MontoNoAfecto);
	$MontoBruto = ($MontoBruto);
	$MontoIGV = ($MontoIGV);
	$MontoTotal = ($MontoTotal);
	$MontoPendiente = ($MontoPendiente);
	$MontoOtros = ($MontoOtros);
	$FechaPrometida = formatFechaAMD($FechaPrometida);
	$detalles = changeUrl($detalles);
	
	//	nuevo
	if ($accion == "nuevo") {
		$CodDependencia = getValorCampo("ac_mastcentrocosto", "CodCentroCosto", "CodDependencia", $_PARAMETRO["CCOSTOCOMPRA"]);
		$FaxProveedor = getValorCampo("mastpersonas", "CodPersona", "Fax", $CodProveedor);
		//	inserto orden
		##	genero el nuevo codigo
		$NroOrden = getCodigo("lg_ordencompra", "NroOrden", 10);
		
		##	inserto
		
		$sql = "INSERT INTO lg_ordencompra (
							Anio,
							CodOrganismo,
							NroOrden,
							Mes,
							Clasificacion,
							CodDependencia,
							CodProveedor,
							NomProveedor,
							FaxProveedor,
							CodAlmacen,
							FechaPrometida,
							PreparadaPor,
							FechaPreparacion,
							CodTipoServicio,
							MontoBruto,
							MontoIGV,
							MontoOtros,
							MontoTotal,
							MontoPendiente,
							MontoAfecto,
							MontoNoAfecto,
							CodFormaPago,
							CodAlmacenIngreso,
							NomContacto,
							FaxContacto,
							PlazoEntrega,
							DirEntrega,
							InsEntrega,
							Entregaren,
							Observaciones,
							ObsDetallada,
							TipoClasificacion,
							CodCuenta,
							cod_partida,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$NroOrden."',
							'".substr($Ahora, 5, 2)."',
							'".$Clasificacion."',
							'".$CodDependencia."',
							'".$CodProveedor."',
							'".$NomProveedor."',
							'".$FaxProveedor."',
							'".$CodAlmacen."',
							'".$FechaPrometida."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$CodTipoServicio."',
							'".$MontoBruto."',
							'".$MontoIGV."',
							'".$MontoOtros."',
							'".$MontoTotal."',
							'".$MontoPendiente."',
							'".$MontoAfecto."',
							'".$MontoNoAfecto."',
							'".$CodFormaPago."',
							'".$CodAlmacenIngreso."',
							'".$NomContacto."',
							'".$FaxContacto."',
							'".$PlazoEntrega."',
							'".$DirEntrega."',
							'".$InsEntrega."',
							'".$Entregaren."',
							'".$Observaciones."',
							'".$ObsDetallada."',
							'".$TipoClasificacion."',
							'".$_PARAMETRO["IVACTADEF"]."',
							'".$_PARAMETRO["IVADEFAULT"]."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_PrecioUnit, $_DescuentoPorcentaje, $_DescuentoFijo, $_FlagExonerado, $_PrecioUnitTotal, $_Total, $_FechaPrometida, $_CodCentroCosto, $_cod_partida, $_CodCuenta, $_Comentarios, $_CodRequerimiento, $_RequerimientoSecuencia) = split(";char:td;", $linea);
			$_PrecioCantidad = $_CantidadPedida * $_PrecioUnit;
			##	inserto
			if($_PrecioUnit!=0)
			$sql = "INSERT INTO lg_ordencompradetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								PrecioUnit,
								PrecioCantidad,
								Total,
								DescuentoPorcentaje,
								DescuentoFijo,
								FlagExonerado,
								CodCentroCosto,
								Comentarios,
								FechaPrometida,
								CodCuenta,
								cod_partida,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_CodItem."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_PrecioCantidad."',
								'".$_Total."',
								'".$_DescuentoPorcentaje."',
								'".$_DescuentoFijo."',
								'".$_FlagExonerado."',
								'".$_CodCentroCosto."',
								'".$_Comentarios."',
								'".formatFechaAMD($_FechaPrometida)."',
								'".$_CodCuenta."',
								'".$_cod_partida."',
								'PR',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si la orden la estoy generando desde cotizaciones
			if ($GenerarPendiente == "S") {
				//	actualizo detalle del requerimiento
				if($_PrecioUnit!=0)
				$sql = "UPDATE lg_requerimientosdet
						SET
							NroOrden = '".$NroOrden."',
							OrdenSecuencia = '".$_Secuencia."',
							CantidadOrdenCompra = '".$_CantidadPedida."',
							Estado = 'CO'
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_RequerimientoSecuencia."'";
				else
				$sql = "UPDATE lg_requerimientosdet
						SET
							NroOrden = '".$NroOrden."',
							OrdenSecuencia = '".$_Secuencia."',
							CantidadOrdenCompra = '".$_CantidadPedida."',
							Estado = 'DE'
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Secuencia = '".$_RequerimientoSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	verifico si completo todos los detalles del requerimeinto
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$_CodRequerimiento."' AND
							Estado = 'PE'";
				$query_requerimiento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_requerimiento) == 0) {
					//	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$_CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionoc (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_cod_partida."',
								'".$_CodCuenta."',
								'".$_Monto."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$anho = date('Y');
			$sql = "INSERT INTO lg_distribucioncompromisos (
								Anio,
								CodOrganismo,
								CodPresupuesto,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Mes,
								CodCentroCosto,
								cod_partida,
								Monto,
								Periodo,
								Origen,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$CodProveedor."',
								'OC',								
								'".$NroOrden."',
								'".$_Secuencia."',
								'1',
								'".substr($Ahora, 5, 2)."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_cod_partida."',
								'".$_Monto."',
								'".substr($Ahora, 0, 7)."',
								'OC',
								'PE',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo orden
		##	actualizo
		$sql = "UPDATE lg_ordencompra
				SET
					Clasificacion = '".$Clasificacion."',
					CodAlmacen = '".$CodAlmacen."',
					FechaPrometida = '".$FechaPrometida."',
					MontoBruto = '".$MontoBruto."',
					MontoIGV = '".$MontoIGV."',
					MontoOtros = '".$MontoOtros."',
					MontoTotal = '".$MontoTotal."',
					MontoPendiente = '".$MontoPendiente."',
					MontoAfecto = '".$MontoAfecto."',
					MontoNoAfecto = '".$MontoNoAfecto."',
					CodFormaPago = '".$CodFormaPago."',
					CodAlmacenIngreso = '".$CodAlmacenIngreso."',
					NomContacto = '".$NomContacto."',
					FaxContacto = '".$FaxContacto."',
					PlazoEntrega = '".$PlazoEntrega."',
					DirEntrega = '".$DirEntrega."',
					InsEntrega = '".$InsEntrega."',
					Entregaren = '".$Entregaren."',
					Observaciones = '".$Observaciones."',
					ObsDetallada = '".$ObsDetallada."',
					TipoClasificacion = '".$TipoClasificacion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		##	elimino detalles
		$sql = "DELETE FROM lg_ordencompradetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_PrecioUnit, $_DescuentoPorcentaje, $_DescuentoFijo, $_FlagExonerado, $_PrecioUnitTotal, $_Total, $_FechaPrometida, $_CodCentroCosto, $_cod_partida, $_CodCuenta, $_Comentarios) = split(";char:td;", $linea);
			$_PrecioCantidad = $_CantidadPedida * $_PrecioUnit;
			##	inserto
			$sql = "INSERT INTO lg_ordencompradetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								PrecioUnit,
								PrecioCantidad,
								Total,
								DescuentoPorcentaje,
								DescuentoFijo,
								FlagExonerado,
								CodCentroCosto,
								Comentarios,
								FechaPrometida,
								CodCuenta,
								cod_partida,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_CodItem."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_PrecioCantidad."',
								'".$_Total."',
								'".$_DescuentoPorcentaje."',
								'".$_DescuentoFijo."',
								'".$_FlagExonerado."',
								'".$_CodCentroCosto."',
								'".$_Comentarios."',
								'".formatFechaAMD($_FechaPrometida)."',
								'".$_CodCuenta."',
								'".$_cod_partida."',
								'PR',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	distribucion
		##	elimino detalles
		$sql = "DELETE FROM lg_distribucionoc
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionoc (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_cod_partida."',
								'".$_CodCuenta."',
								'".$_Monto."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$anho = date('Y');
			$sql = "INSERT INTO lg_distribucioncompromisos (
								Anio,
								CodOrganismo,
								CodPresupuesto,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Mes,
								CodCentroCosto,
								cod_partida,
								Monto,
								Periodo,
								Origen,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$CodProveedor."',
								'OC',								
								'".$NroOrden."',
								'".$_Secuencia."',
								'1',
								'".substr($Ahora, 5, 2)."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_cod_partida."',
								'".$_Monto."',
								'".substr($Ahora, 0, 7)."',
								'OC',
								'PE',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		##	genero el nuevo codigo
		$NroInterno = getCodigo("lg_ordencompra", "NroInterno", 10);
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'RV',
					RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo presupuesto
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$_Monto."
					WHERE
						Organismo = '".$CodOrganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
						cod_partida = '".$_cod_partida."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
		}
		die("|Se ha generado la Orden de Compra <strong>Nro. $NroInterno</strong>");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'AP',
					AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalle
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'RE',
					MotRechazo = '".$MotRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo presupuesto
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$_Monto."
					WHERE
						Organismo = '".$CodOrganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
						cod_partida = '".$_cod_partida."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
		}
	}
	
	//	anular
	elseif ($accion == "anular") {
		if ($Estado == "PR") {
			$EstadoOrden = "AN";
			$EstadoDetalle = "AN";
			$EstadoCompromiso = "AN";
		}
		elseif ($Estado != "PR") {
			$EstadoOrden = "PR";
			$EstadoDetalle = "PR";
			$EstadoCompromiso = "PE";
		}
		
		//	modifico orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = '".$EstadoOrden."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = '".$EstadoDetalle."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = '".$EstadoCompromiso."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		if ($Estado != "PR") {
			//	actualizo presupuesto
			$_Secuencia = 0;
			$detalle = split(";char:tr;", $detalles_partida);
			foreach ($detalle as $linea) {
				list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET MontoCompromiso = MontoCompromiso - ".$_Monto."
						WHERE
							Organismo = '".$CodOrganismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
							cod_partida = '".$_cod_partida."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}
		}
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		//	modifico requerimiento
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle") {
		list($Anio, $CodOrganismo, $NroOrden, $Secuencia) = split("[.]", $registro);
		//	verifico los detalles
		$sql = "SELECT Estado
				FROM lg_ordencompradetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$Secuencia."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
		##
		//	modifico detalles
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		//	consulto si no quedan pendientes en el requerimiento
		$sql = "SELECT Estado
				FROM lg_ordencompradetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) == 0) {
			//	consulto si se completaron algunas lineas en el requerimiento
			$sql = "SELECT Estado
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Estado = 'CO'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) != 0) {
				$sql = "UPDATE lg_ordencompra
						SET Estado = 'CO'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_ordencompra
						SET Estado = 'CE'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
}

//	orden de servicio
elseif ($modulo == "orden_servicio") {
	$NomProveedor = changeUrl($NomProveedor);
	$Descripcion = changeUrl($Descripcion);
	$DescAdicional = changeUrl($DescAdicional);
	$Observaciones = changeUrl($Observaciones);
	$MotRechazo = changeUrl($MotRechazo);
	$MontoOriginal = ($MontoOriginal);
	$MontoNoAfecto = ($MontoNoAfecto);
	$MontoIva = ($MontoIva);
	$TotalMontoIva = ($TotalMontoIva);
	$MontoGastado = ($MontoGastado);
	$MontoPendiente = ($MontoPendiente);
	$FechaEntrega = formatFechaAMD($FechaEntrega);
	$FechaValidoDesde = formatFechaAMD($FechaValidoDesde);
	$FechaValidoHasta = formatFechaAMD($FechaValidoHasta);
	$detalles = changeUrl($detalles);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	inserto orden
		##	genero el nuevo codigo
		$NroOrden = getCodigo("lg_ordenservicio", "NroOrden", 10);
		##	inserto
		$sql = "INSERT INTO lg_ordenservicio (
							Anio,
							CodOrganismo,
							NroOrden,
							Mes,
							CodDependencia,
							CodProveedor,
							NomProveedor,
							CodFormaPago,
							FechaDocumento,
							DiasPago,
							CodTipoPago,
							CodTipoServicio,
							PlazoEntrega,
							FechaEntrega,
							MontoOriginal,
							MontoNoAfecto,
							MontoIva,
							TotalMontoIva,
							MontoGastado,
							MontoPendiente,
							Descripcion,
							DescAdicional,
							Observaciones,
							FechaValidoDesde,
							FechaValidoHasta,
							CodCentroCosto,
							PreparadaPor,
							FechaPreparacion,
							CodCuenta,
							cod_partida,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$NroOrden."',
							'".substr($Ahora, 5, 2)."',
							'".$CodDependencia."',
							'".$CodProveedor."',
							'".$NomProveedor."',
							'".$CodFormaPago."',
							NOW(),
							'".$DiasPago."',
							'".$CodTipoPago."',
							'".$CodTipoServicio."',
							'".$PlazoEntrega."',
							'".$FechaEntrega."',
							'".$MontoOriginal."',
							'".$MontoNoAfecto."',
							'".$MontoIva."',
							'".$TotalMontoIva."',
							'".$MontoGastado."',
							'".$MontoPendiente."',
							'".$Descripcion."',
							'".$DescAdicional."',
							'".$Observaciones."',
							'".$FechaValidoDesde."',
							'".$FechaValidoHasta."',
							'".$CodCentroCosto."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$_PARAMETRO["IVACTADEF"]."',
							'".$_PARAMETRO["IVADEFAULT"]."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CommoditySub, $_Descripcion, $_CantidadPedida, $_PrecioUnit, $_FlagExonerado, $_Total, $_FechaEsperadaTermino, $_FechaTermino, $_CodCentroCosto, $_NroActivo, $_FlagTerminado, $_cod_partida, $_CodCuenta, $_Comentarios) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_ordenserviciodetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CommoditySub,
								Descripcion,
								CantidadPedida,
								PrecioUnit,
								Total,
								FechaEsperadaTermino,
								FechaTermino,
								CodCentroCosto,
								NroActivo,
								FlagExonerado,
								FlagTerminado,
								Comentarios,
								cod_partida,
								CodCuenta,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".formatFechaAMD($_FechaEsperadaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_CodCentroCosto."',
								'".$_NroActivo."',
								'".$_FlagExonerado."',
								'".$_FlagTerminado."',
								'".$_Comentarios."',
								'".$_cod_partida."',
								'".$_CodCuenta."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionos (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_cod_partida."',
								'".$_CodCuenta."',
								'".$_Monto."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$anho = date('Y');
			$sql = "INSERT INTO lg_distribucioncompromisos (
								Anio,
								CodOrganismo,
								CodPresupuesto,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Mes,
								CodCentroCosto,
								cod_partida,
								Monto,
								Periodo,
								Origen,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$CodProveedor."',
								'OS',
								'".$NroOrden."',
								'".$_Secuencia."',
								'1',
								'".substr($Ahora, 5, 2)."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_cod_partida."',
								'".$_Monto."',
								'".substr($Ahora, 0, 7)."',
								'OS',
								'PE',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	si la orden la estoy generando desde cotizaciones
		if ($GenerarPendiente == "S") {
			$sql = "SELECT
						rd.CodRequerimiento,
						rd.Secuencia,
						c.Cantidad
					FROM
						lg_cotizacion c
						INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
															   c.Secuencia = rd.Secuencia)
					WHERE c.NroCotizacionProv = '".$NroCotizacionProv."'
					ORDER BY c.Secuencia";
			$query_detalles = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_detalles = mysql_fetch_array($query_detalles)) {
				//	actualizo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET
							NroOrden = '".$NroOrden."',
							OrdenSecuencia = '".$field_detalles['Secuencia']."',
							CantidadOrdenCompra = '".$field_detalles['Cantidad']."',
							Estado = 'CO'
						WHERE
							CodRequerimiento = '".$field_detalles['CodRequerimiento']."' AND
							Secuencia = '".$field_detalles['Secuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	verifico si completo todos los detalles del requerimeinto
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$field_detalles['CodRequerimiento']."' AND
							Estado = 'PE'";
				$query_requerimiento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_requerimiento) == 0) {
					//	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$field_detalles['CodRequerimiento']."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	actualizo orden
		##	actualizo
		$sql = "UPDATE lg_ordenservicio
				SET
					CodFormaPago = '".$CodFormaPago."',
					DiasPago = '".$DiasPago."',
					CodTipoPago = '".$CodTipoPago."',
					PlazoEntrega = '".$PlazoEntrega."', 
					FechaEntrega = '".$FechaEntrega."', 
					MontoOriginal = '".$MontoOriginal."', 
					MontoNoAfecto = '".$MontoNoAfecto."', 
					MontoIva = '".$MontoIva."', 
					TotalMontoIva = '".$TotalMontoIva."', 
					MontoGastado = '".$MontoGastado."', 
					MontoPendiente = '".$MontoPendiente."', 
					Descripcion = '".$Descripcion."', 
					DescAdicional = '".$DescAdicional."', 
					Observaciones = '".$Observaciones."', 
					FechaValidoDesde = '".$FechaValidoDesde."', 
					FechaValidoHasta = '".$FechaValidoHasta."', 
					CodCentroCosto = '".$CodCentroCosto."', 
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles
		##	elimino detalles
		$sql = "DELETE FROM lg_ordenserviciodetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_CommoditySub, $_Descripcion, $_CantidadPedida, $_PrecioUnit, $_FlagExonerado, $_Total, $_FechaEsperadaTermino, $_FechaTermino, $_CodCentroCosto, $_NroActivo, $_FlagTerminado, $_cod_partida, $_CodCuenta, $_Comentarios) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_ordenserviciodetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CommoditySub,
								Descripcion,
								CantidadPedida,
								PrecioUnit,
								Total,
								FechaEsperadaTermino,
								FechaTermino,
								CodCentroCosto,
								NroActivo,
								FlagExonerado,
								FlagTerminado,
								Comentarios,
								cod_partida,
								CodCuenta,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".formatFechaAMD($_FechaEsperadaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_CodCentroCosto."',
								'".$_NroActivo."',
								'".$_FlagExonerado."',
								'".$_FlagTerminado."',
								'".$_Comentarios."',
								'".$_cod_partida."',
								'".$_CodCuenta."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	distribucion
		##	elimino detalles
		$sql = "DELETE FROM lg_distribucionos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo= '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	inserto distribucion
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			##	inserto
			$sql = "INSERT INTO lg_distribucionos (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".++$_Secuencia."',
								'".substr($Ahora, 5, 2)."',
								'".$_cod_partida."',
								'".$_CodCuenta."',
								'".$_Monto."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto
			$anho = date('Y');
			$sql = "INSERT INTO lg_distribucioncompromisos (
								Anio,
								CodOrganismo,
								CodPresupuesto,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Mes,
								CodCentroCosto,
								cod_partida,
								Monto,
								Periodo,
								Origen,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$CodProveedor."',
								'OS',
								'".$NroOrden."',
								'".$_Secuencia."',
								'1',
								'".substr($Ahora, 5, 2)."',
								'".$_PARAMETRO["CCOSTOCOMPRA"]."',
								'".$_cod_partida."',
								'".$_Monto."',
								'".substr($Ahora, 0, 7)."',
								'OS',
								'PE',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		mysql_query("BEGIN");
		##	genero el nuevo codigo
		$NroInterno = getCodigo("lg_ordenservicio", "NroInterno", 10);
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'RV',
					RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'CO',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo presupuesto
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$_Monto."
					WHERE
						Organismo = '".$CodOrganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
						cod_partida = '".$_cod_partida."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Orden de Servicio <strong>Nro. $NroInterno</strong>");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'AP',
					AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		mysql_query("BEGIN");
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'RE',
					MotRechazo = '".$MotRechazo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'S',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo presupuesto
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles_partida);
		foreach ($detalle as $linea) {
			list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$_Monto."
					WHERE
						Organismo = '".$CodOrganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
						cod_partida = '".$_cod_partida."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
		}
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		if ($Estado == "PR") {
			$EstadoOrden = "AN";
			$EstadoDetalle = "S";
			$EstadoCompromiso = "AN";
		}
		elseif ($Estado != "PR") {
			$EstadoOrden = "PR";
			$EstadoDetalle = "N";
			$EstadoCompromiso = "PE";
		}
		
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = '".$EstadoOrden."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = '".$EstadoDetalle."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico compromisos
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = '".$EstadoCompromiso."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		if ($Estado != "PR") {
			//	actualizo presupuesto
			$_Secuencia = 0;
			$detalle = split(";char:tr;", $detalles_partida);
			foreach ($detalle as $linea) {
				list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET MontoCompromiso = MontoCompromiso - ".$_Monto."
						WHERE
							Organismo = '".$CodOrganismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
							cod_partida = '".$_cod_partida."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}
		}
		mysql_query("COMMIT");
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		mysql_query("BEGIN");
		//	modifico orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico detalles
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'S',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
	
	//	confirmar
	elseif ($accion == "confirmar") {
		mysql_query("BEGIN");
		//	valores
		$FechaTermino = formatFechaAMD($FechaTermino);
		$PorRecibirTotal = setNumero($PorRecibirTotal);
		$CantidadTotal = setNumero($CantidadTotal);
		$SaldoTotal = setNumero($SaldoTotal);
		
		//	confirmo estado
		if (floatval($CantidadTotal) > floatval($SaldoTotal)) {
			$FlagTerminado = "N";
			$FechaTermino = "0000-00-00";
		}
		else {
			$FlagTerminado = "S";
			$FechaTermino = substr(ahora(), 0, 10);
		}
		
		//	inserto confirmacion
		$NroConfirmacion = getCodigo("lg_confirmacionservicio", "NroConfirmacion", 4);
		$DocumentoReferencia = "$NroOrden-$NroConfirmacion";
		$sql = "INSERT INTO lg_confirmacionservicio (
							Anio,
							CodOrganismo,
							NroOrden,
							Secuencia,
							NroConfirmacion,
							DocumentoReferencia,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$NroOrden."',
							'".$Secuencia."',
							'".$NroConfirmacion."',
							'".$DocumentoReferencia."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico servicio detalle
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					ConfirmadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
					FechaConfirmacion = NOW(),
					FlagTerminado = '".$FlagTerminado."',
					FechaTermino = '".$FechaTermino."',
					CantidadRecibida = (CantidadRecibida + ".floatval($PorRecibirTotal)."),
					FechaTermino = '".$FechaTermino."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo estado de la orden si confirme todos los servicios
		$sql = "SELECT *
				FROM lg_ordenserviciodetalle
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					FlagTerminado <> 'S'";
		$query_osd = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_osd) == 0) {
			$sql = "UPDATE lg_ordenservicio
					SET Estado = 'CO'
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	recalculo
		if (afectaTipoServicio($CodTipoServicio)) $FactorImpuesto = getPorcentajeIVA($CodTipoServicio);
		else $FactorImpuesto = 0;
		$PrecioCantidad = $PrecioUnit * $PorRecibirTotal;
		if ($FlagExonerado == "S") {
			$MontoAfecto = 0;
			$MontoNoAfecto = $PrecioCantidad;
		} else {
			$MontoAfecto = $PrecioCantidad;
			$MontoNoAfecto = 0;
		}
		
		##	consulto los montos de la orden de compra
		$sql = "SELECT
					MontoOriginal AS MontoAfecto,
					MontoNoAfecto,
					MontoIva AS MontoIGV
				FROM lg_ordenservicio
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_afecto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_afecto) != 0) $field_afecto = mysql_fetch_array($query_afecto);
		
		##	actualizo montos del documento
		if ($MontoAfecto != $field_afecto['MontoAfecto']) {
			$MontoImpuestos = round(($MontoAfecto * $field_afecto['MontoIGV'] / $field_afecto['MontoAfecto']), 2);
		} else {
			$MontoAfecto = $field_afecto['MontoAfecto'];
			$MontoNoAfecto = $field_afecto['MontoNoAfecto'];
			$MontoImpuestos = $field_afecto['MontoIGV'];
		}
		$MontoTotal = $MontoAfecto + $MontoNoAfecto + $MontoImpuestos;
		
		//	inserto el documento
		$sql = "INSERT INTO ap_documentos (
							Anio,
							CodOrganismo,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Fecha,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							MontoAfecto,
							MontoNoAfecto,
							MontoImpuestos,
							MontoTotal,
							MontoPendiente,
							Estado,
							TransaccionTipoDocumento,
							TransaccionNroDocumento,
							Comentarios,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$CodProveedor."',
							'".$_PARAMETRO['DOCREFOS']."',
							'".$DocumentoReferencia."',
							NOW(),
							'OS',
							'".$NroOrden."',
							'".$MontoAfecto."',
							'".$MontoNoAfecto."',
							'".$MontoImpuestos."',
							'".$MontoTotal."',
							'".$MontoTotal."',
							'PR',
							'OS',
							'".$NroConfirmacion."',
							'".$Descripcion."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto el documento detalle
		$sql = "INSERT INTO ap_documentosdetalle (
							Anio,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Secuencia,
							ReferenciaSecuencia,
							CommoditySub,
							Descripcion,
							Cantidad,
							PrecioUnit,
							PrecioCantidad,
							Total,
							CodCentroCosto,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodProveedor."',
							'".$_PARAMETRO['DOCREFOS']."',
							'".$DocumentoReferencia."',
							'1',
							'".$Secuencia."',
							'".$CommoditySub."',
							'".$Descripcion."',
							'".$PorRecibirTotal."',
							'".$PrecioUnit."',
							'".$PrecioCantidad."',
							'".$MontoTotal."',
							'".$CodCentroCosto."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
		
		echo "|$NroConfirmacion|$Anio.$CodProveedor.".$_PARAMETRO['DOCREFOS'].".$DocumentoReferencia";
	}
	
	//	desconfirmar
	elseif ($accion == "desconfirmar") {
		mysql_query("BEGIN");
		list($Anio, $CodProveedor, $DocumentoClasificacion, $DocumentoReferencia) = split("[.]", $registro);
		//	consulto documentos
		$sql = "SELECT Estado, TransaccionNroDocumento
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$DocumentoClasificacion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_doc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		if ($field_doc['Estado'] == "RV") die("No se puede desconfirmar un documento <strong>Facturado</strong>");
		
		//	consultom documentos detalle
		$sql = "SELECT Cantidad, ReferenciaSecuencia
				FROM ap_documentosdetalle
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$DocumentoClasificacion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_detalle) != 0) $field_detalle = mysql_fetch_array($query_detalle);
		
		//	actualizo servicio detalle
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'N',
					CantidadRecibida = (CantidadRecibida - ".floatval($field_detalle['Cantidad'])."),
					FechaTermino = '',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."' AND
					Secuencia = '".$field_detalle['ReferenciaSecuencia']."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo servicio
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino documentos
		$sql = "DELETE FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$DocumentoClasificacion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino documentos
		$sql = "DELETE FROM lg_confirmacionservicio
				WHERE NroConfirmacion = '".$field_doc['TransaccionNroDocumento']."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		mysql_query("COMMIT");
	}
}

//	almacen
elseif ($modulo == "almacen") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	despacho
	if ($accion == "despacho") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo_3("lg_transaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_transaccion (
							CodOrganismo,
							CodDocumento,
							NroDocumento,
							NroInterno,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							FlagManual,
							FlagPendiente,
							ReferenciaNroDocumento,
							DocumentoReferencia,
							DocumentoReferenciaInterno,
							CodDependencia,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$NroInterno."',
							'".$CodTransaccion."',
							'".$FechaDocumento."',
							'".$Periodo."',
							'".$CodAlmacen."',
							'".$CodCentroCosto."',
							'".$CodDocumentoReferencia."',
							'".$NroDocumentoReferencia."',
							'".$IngresadoPor."',
							'".$RecibidoPor."',
							'".$Comentarios."',
							'".$FlagManual."',
							'".$FlagPendiente."',
							'".$ReferenciaNroDocumento."',
							'".$DocumentoReferencia."',
							'".$DocumentoReferenciaInterno."',
							'".$CodDependencia."',
							'CO',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CodItem,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								CantidadRecibida,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CodItem."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadPedida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CodCentroCosto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto kardex
			$sql = "INSERT INTO lg_kardex (
								CodItem,
								CodAlmacen,
								CodDocumento,
								NroDocumento,
								Secuencia,
								Fecha,
								CodTransaccion,
								ReferenciaCodOrganismo,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								Cantidad,
								PrecioUnitario,
								MontoTotal,
								PeriodoContable,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$_CodItem."',
								'".$CodAlmacen."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$FechaDocumento."',
								'".$CodTransaccion."',
								'".$CodOrganismo."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$FechaDocumento."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	actualizo item en inventario
			$sql = "UPDATE lg_itemalmaceninv
					SET
						StockActual = (StockActual - ".floatval($_CantidadRecibida)."),
						StockComprometido = (StockComprometido + ".abs(floatval($_CantidadRecibida))."),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodAlmacen = '".$CodAlmacen."' AND
						CodItem = '".$_CodItem."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo requerimientos
			##	si se scompleto el despacho
			if ($_CantidadPendiente == $_CantidadRecibida) {
				##	completo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET Estado = 'CO'
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
		die("|$NroDocumento|$NroInterno");
	}
	
	//	recepcion
	elseif ($accion == "recepcion") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		##	documento
		$sql = "SELECT Estado
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";
		$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_documento) != 0) die("<strong>Doc. Ref / G. Remisión</strong> ya se encuentra registrado");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo_3("lg_transaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_transaccion (
							CodOrganismo,
							CodDocumento,
							NroDocumento,
							NroInterno,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							FlagManual,
							FlagPendiente,
							ReferenciaNroDocumento,
							DocumentoReferencia,
							DocumentoReferenciaInterno,
							CodDependencia,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$NroInterno."',
							'".$CodTransaccion."',
							'".$FechaDocumento."',
							'".$Periodo."',
							'".$CodAlmacen."',
							'".$CodCentroCosto."',
							'".$CodDocumentoReferencia."',
							'".$NroDocumentoReferencia."',
							'".$IngresadoPor."',
							'".$RecibidoPor."',
							'".$Comentarios."',
							'".$FlagManual."',
							'".$FlagPendiente."',
							'".$ReferenciaNroDocumento."',
							'".$DocumentoReferencia."',
							'".$DocumentoReferenciaInterno."',
							'".$CodDependencia."',
							'CO',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto documento
		$sql = "INSERT INTO ap_documentos (
							Anio,
							CodOrganismo,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Fecha,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							Estado,
							TransaccionTipoDocumento,
							TransaccionNroDocumento,
							Comentarios,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$CodProveedor."',
							'".$CodTransaccion."',
							'".$DocumentoReferencia."',
							NOW(),
							'OC',
							'".$ReferenciaNroDocumento."',
							'".$Estado."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$Comentarios."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_FlagExonerado, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CodItem,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								CantidadRecibida,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CodItem."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadPedida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CodCentroCosto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto kardex
			$sql = "INSERT INTO lg_kardex (
								CodItem,
								CodAlmacen,
								CodDocumento,
								NroDocumento,
								Secuencia,
								Fecha,
								CodTransaccion,
								ReferenciaCodOrganismo,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								Cantidad,
								PrecioUnitario,
								MontoTotal,
								PeriodoContable,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$_CodItem."',
								'".$CodAlmacen."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".formatFechaAMD($FechaDocumento)."',
								'".$CodTransaccion."',
								'".$CodOrganismo."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".formatFechaAMD($FechaDocumento)."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			if ($_FlagExonerado == "S") {
				$MontoNoAfecto += $_PrecioCantidad;
			} else {
				$MontoAfecto += $_PrecioCantidad;
			}
			
			##	inserto documento detalle
			$sql = "INSERT INTO ap_documentosdetalle (
								Anio,
								CodProveedor,
								DocumentoClasificacion,
								DocumentoReferencia,
								Secuencia,
								ReferenciaSecuencia,
								CodItem,
								Descripcion,
								Cantidad,
								PrecioUnit,
								PrecioCantidad,
								Total,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodProveedor."',
								'".$CodTransaccion."',
								'".$DocumentoReferencia."',
								'".$_Secuencia."',
								'".$_ReferenciaSecuencia."',
								'".$_CodItem."',
								'".$_Descripcion."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_PrecioCantidad."',
								'".$_Total."',
								'".$_CodCentroCosto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	actualizo inventario
			$sql = "SELECT *
					FROM lg_itemalmacen
					WHERE
						CodAlmacen = '".$CodAlmacen."' AND
						CodItem = '".$_CodItem."'";
			$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_almacen) == 0) {
				##	inserto item en almacen
				$sql = "INSERT INTO lg_itemalmacen (
									CodItem,
									CodAlmacen,
									Estado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$_CodItem."',
									'".$CodAlmacen."',
									'A',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	inserto item en inventario
				$sql = "INSERT INTO lg_itemalmaceninv (
									CodAlmacen,
									CodItem,
									Proveedor,
									FechaIngreso,
									StockIngreso,
									StockActual,
									PrecioUnitario,
									DocReferencia,
									IngresadoPor,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodAlmacen."',
									'".$_CodItem."',
									'".$CodProveedor."',
									NOW(),
									'".$_CantidadRecibida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_ReferenciaCodDocumento."-".$_ReferenciaNroDocumento."',
									'".$_SESSION["CODPERSONA_ACTUAL"]."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	actualizo item en inventario
				$sql = "UPDATE lg_itemalmaceninv
						SET
							StockActual = (StockActual + ".floatval($_CantidadRecibida)."),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CodItem = '".$_CodItem."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	actualizo orden
			##	si se scompleto el despacho
			if ($_CantidadPendiente == $_CantidadRecibida) {
				##	completo detalle de la orden
				$sql = "UPDATE lg_ordencompradetalle
						SET Estado = 'CO'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_ordencompradetalle
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo requerimiento
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_ordencompradetalle
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	consulto los montos de la orden de compra
			$sql = "SELECT
						MontoAfecto,
						MontoNoAfecto,
						MontoIGV
					FROM lg_ordencompra
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$ReferenciaNroDocumento."'";
			$query_afecto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_afecto) != 0) $field_afecto = mysql_fetch_array($query_afecto);
			
			##	actualizo montos del documento
			if ($MontoAfecto != $field_afecto['MontoAfecto']) {
				$MontoImpuestos = round(($MontoAfecto * $field_afecto['MontoIGV'] / $field_afecto['MontoAfecto']), 2);
			} else {
				$MontoAfecto = $field_afecto['MontoAfecto'];
				$MontoNoAfecto = $field_afecto['MontoNoAfecto'];
				$MontoImpuestos = $field_afecto['MontoIGV'];
			}
			$MontoTotal = $MontoAfecto + $MontoNoAfecto + $MontoImpuestos;
			
			##	actualizo montos del documento
			$sql = "UPDATE ap_documentos
					SET
						MontoAfecto = '".$MontoAfecto."',
						MontoNoAfecto = '".$MontoNoAfecto."',
						MontoImpuestos = '".$MontoImpuestos."',
						MontoTotal = '".$MontoTotal."',
						MontoPendiente = '".$MontoTotal."'
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						DocumentoClasificacion = '".$CodTransaccion."' AND
						DocumentoReferencia = '".$DocumentoReferencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
		die("|$NroDocumento|$NroInterno");
	}
	
	//	pasar requerimiento para compras
	elseif ($accion == "dirigir-compras") {
		 $sql = "UPDATE lg_requerimientosdet
				SET FlagCompraAlmacen = 'C'
				WHERE
					CodRequerimiento = '".$registro."' AND
					Estado = 'PE' AND
					FlagCompraAlmacen = 'A'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	pasar linea para compras
	elseif ($accion == "dirigir-compras-detalle") {
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($CodRequerimiento, $Secuencia) = split("[.]", $linea);
			$sql = "UPDATE lg_requerimientosdet
					SET FlagCompraAlmacen = 'X'
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle") {
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($CodRequerimiento, $Secuencia) = split("[.]", $linea);			
			//	modifico detalles
			$sql = "UPDATE lg_requerimientosdet
					SET
						Estado = 'CE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			//	consulto si no quedan pendientes en el requerimiento
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) {
				//	consulto si se completaron algunas lineas en el requerimiento
				$sql = "SELECT Estado
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'CO'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CE'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle-compras") {
		mysql_query("BEGIN");
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($Anio, $CodOrganismo, $NroOrden, $Secuencia) = split("[.]", $linea);
			//	verifico los detalles
			$sql = "SELECT Estado
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$Secuencia."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
			##
			//	modifico detalles
			$sql = "UPDATE lg_ordencompradetalle
					SET
						Estado = 'CE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			//	consulto si no quedan pendientes en el requerimiento
			$sql = "SELECT Estado
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) {
				//	consulto si se completaron algunas lineas en el requerimiento
				$sql = "SELECT Estado
						FROM lg_ordencompradetalle
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Estado = 'CO'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) {
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CE'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
	}
	
	//	cerrar linea
	elseif ($accion == "cerrar-detalle-requerimiento") {
		mysql_query("BEGIN");
		$detalle = split(";char:tr;", $registro);
		foreach ($detalle as $linea) {
			list($CodRequerimiento, $Secuencia) = split("[.]", $registro);
			//	verifico los detalles
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) die("Solo se pueden cerrar lineas en Estado <strong>Pendiente</strong>");
			##
			//	modifico detalles
			$sql = "UPDATE lg_requerimientosdet
					SET
						Estado = 'CE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			##
			//	consulto si no quedan pendientes en el requerimiento
			$sql = "SELECT Estado
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Estado = 'PE'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query) == 0) {
				//	consulto si se completaron algunas lineas en el requerimiento
				$sql = "SELECT Estado
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'CO'";
				$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query) != 0) {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CE'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("BEGIN");
	}
}

//	transaccion (almacen)
elseif ($modulo == "transaccion_almacen") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_transaccion (
							CodOrganismo,
							CodDocumento,
							NroDocumento,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							FlagManual,
							FlagPendiente,
							ReferenciaNroDocumento,
							DocumentoReferencia,
							DocumentoReferenciaInterno,
							CodDependencia,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$CodTransaccion."',
							'".$FechaDocumento."',
							'".$Periodo."',
							'".$CodAlmacen."',
							'".$CodCentroCosto."',
							'".$CodDocumentoReferencia."',
							'".$NroDocumentoReferencia."',
							'".$IngresadoPor."',
							'".$RecibidoPor."',
							'".$Comentarios."',
							'".$FlagManual."',
							'".$FlagPendiente."',
							'".$ReferenciaNroDocumento."',
							'".$DocumentoReferencia."',
							'".$DocumentoReferenciaInterno."',
							'".$CodDependencia."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CodItem,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								CantidadRecibida,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CodItem."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadRecibida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CodCentroCosto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	modifico transaccion
		$sql = "UPDATE lg_transaccion
				SET
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					RecibidoPor = '".$RecibidoPor."',
					Comentarios = '".$Comentarios."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodDependencia = '".$CodDependencia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$sql = "DELETE FROM lg_transacciondetalle
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			##	inserto detalle
			$sql = "INSERT INTO lg_transacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CodItem,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								CantidadRecibida,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CodItem."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadRecibida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CodCentroCosto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	ejecutar
	elseif ($accion == "ejecutar") {
		mysql_query("BEGIN");
		##	genero el nuevo codigo
		$NroInterno = getCodigo_3("lg_transaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		//	
		$sql = "UPDATE lg_transaccion
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'CO',
					EjecutadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaEjecucion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			if ($TipoMovimiento == "E"){ $_CantidadRecibida *= -1;}
			if ($TipoMovimiento == "I" && $_CantidadRecibida < 0){ $_CantidadRecibida *= -1;}
			if ($TipoMovimiento == "T" && $_CantidadRecibida > 0){ $_CantidadRecibida *= -1;}
			
			##	inserto kardex
			$sql = "INSERT INTO lg_kardex (
								CodItem,
								CodAlmacen,
								CodDocumento,
								NroDocumento,
								Secuencia,
								Fecha,
								CodTransaccion,
								ReferenciaCodOrganismo,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								Cantidad,
								PrecioUnitario,
								MontoTotal,
								PeriodoContable,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$_CodItem."',
								'".$CodAlmacen."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$FechaDocumento."',
								'".$CodTransaccion."',
								'".$CodOrganismo."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$_CantidadPedida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$FechaDocumento."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	actualizo inventario
			$sql = "SELECT *
					FROM lg_itemalmacen
					WHERE
						CodAlmacen = '".$CodAlmacen."' AND
						CodItem = '".$_CodItem."'";
			$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_almacen) == 0) {
				##	inserto item en almacen
				$sql = "INSERT INTO lg_itemalmacen (
									CodItem,
									CodAlmacen,
									Estado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$_CodItem."',
									'".$CodAlmacen."',
									'A',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	inserto item en inventario
				$sql = "INSERT INTO lg_itemalmaceninv (
									CodAlmacen,
									CodItem,
									Proveedor,
									FechaIngreso,
									StockIngreso,
									StockActual,
									PrecioUnitario,
									DocReferencia,
									IngresadoPor,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodAlmacen."',
									'".$_CodItem."',
									'".$CodProveedor."',
									NOW(),
									'".$_CantidadRecibida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_ReferenciaCodDocumento."-".$_ReferenciaNroDocumento."',
									'".$_SESSION["CODPERSONA_ACTUAL"]."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	actualizo item en inventario
				$sql = "UPDATE lg_itemalmaceninv
						SET
							StockActual = (StockActual + ".floatval($_CantidadRecibida)."),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CodItem = '".$_CodItem."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroInterno</strong>");
	}
}

//	commodity
elseif ($modulo == "almacen-commodity") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	recepcion
	if ($accion == "recepcion") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Anio = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		##	documento
		$sql = "SELECT Estado
				FROM ap_documentos
				WHERE
					Anio = '".$Anio."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_documento) != 0) die("<strong>Doc. Ref / G. Remisión</strong> ya se encuentra registrado");
		##	activos
		if ($FlagActivoFijo == "S") {
			$activo = split(";char:tr;", $activos);
			foreach ($activo as $linea) {
				list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
				//	consulto
				$sql = "SELECT Estado
						FROM lg_activofijo
						WHERE
							CodOrganismo = '".$CodOrganismo."' AND
							Anio = '".$Anio."' AND
							NroOrden = '".$ReferenciaNroDocumento."' AND
							Secuencia = '".$_Secuencia."' AND
							NroSecuencia = '".$_NroSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
				$query_activo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_activo) != 0) die("Se encontraron lineas en la ficha de <strong>Activos Asociados</strong> ya ingresados");
			}
		}
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo_3("lg_commoditytransaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_commoditytransaccion (
							CodOrganismo,
							CodDocumento,
							NroDocumento,
							NroInterno,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							ReferenciaNroDocumento,
							DocumentoReferencia,
							DocumentoReferenciaInterno,
							CodUbicacion,
							FlagActivoFijo,
							CodDependencia,
							Anio,
							FlagManual,
							FlagPendiente,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$NroInterno."',
							'".$CodTransaccion."',
							'".$FechaDocumento."',
							'".$Periodo."',
							'".$CodAlmacen."',
							'".$CodCentroCosto."',
							'".$CodDocumentoReferencia."',
							'".$NroDocumentoReferencia."',
							'".$IngresadoPor."',
							'".$RecibidoPor."',
							'".$Comentarios."',
							'".$ReferenciaNroDocumento."',
							'".$DocumentoReferencia."',
							'".$DocumentoReferenciaInterno."',
							'".$CodUbicacion."',
							'".$FlagActivoFijo."',
							'".$CodDependencia."',
							'".$Periodo."',
							'".$FlagManual."',
							'".$FlagPendiente."',
							'CO',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto documento
		$sql = "INSERT INTO ap_documentos (
							Anio,
							CodOrganismo,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Fecha,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							Estado,
							TransaccionTipoDocumento,
							TransaccionNroDocumento,
							Comentarios,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$CodProveedor."',
							'".$CodTransaccion."',
							'".$DocumentoReferencia."',
							NOW(),
							'OC',
							'".$ReferenciaNroDocumento."',
							'".$Estado."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$Comentarios."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_FlagExonerado, $_PrecioUnit, $_Total, $_CodClasificacion, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			
			##	
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			if ($_FlagExonerado == "S") {
				$MontoNoAfecto += $_PrecioCantidad;
			} else {
				$MontoAfecto += $_PrecioCantidad;
			}
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadKardex,
								Cantidad,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodAlmacen,
								CodCentroCosto,
								Anio,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadRecibida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$CodAlmacen."',
								'".$_CodCentroCosto."',
								'".$Periodo."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	inserto documento detalle
			$sql = "INSERT INTO ap_documentosdetalle (
								Anio,
								CodProveedor,
								DocumentoClasificacion,
								DocumentoReferencia,
								Secuencia,
								ReferenciaSecuencia,
								CommoditySub,
								Descripcion,
								Cantidad,
								PrecioUnit,
								PrecioCantidad,
								Total,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodProveedor."',
								'".$CodTransaccion."',
								'".$DocumentoReferencia."',
								'".$_Secuencia."',
								'".$_ReferenciaSecuencia."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_PrecioCantidad."',
								'".$_Total."',
								'".$_CodCentroCosto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if ($_FlagExonerado == "S") $_SumMontoAfecto += $_PrecioCantidad;
			else $_SumMontoNoAfecto += $_PrecioCantidad;
			$_SumMontoTotal += $_Total;
			$_SumMontoImpuestos += ($_Total - $_PrecioCantidad);
			
			//	actualizo orden
			##	si se scompleto el despacho
			if ($_CantidadPendiente == $_CantidadRecibida) {
				##	completo detalle de la orden
				$sql = "UPDATE lg_ordencompradetalle
						SET Estado = 'CO'
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_ordencompradetalle
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$NroOrden."' AND
							Estado = 'PE'";	fwrite($__archivo, $sql.";\n\n");
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo orden
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$Anio."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								NroOrden = '".$NroOrden."'";	fwrite($__archivo, $sql.";\n\n");
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_ordencompradetalle
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto el stock
			$sql = "SELECT *
					FROM lg_commoditystock
					WHERE
						CommoditySub = '".$_CommoditySub."' AND
						CodAlmacen = '".$CodAlmacen."'";	fwrite($__archivo, $sql.";\n\n");
			$query_stock = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_stock) == 0) {
				//	inserto
				$sql = "INSERT INTO lg_commoditystock (
									CodAlmacen,
									CommoditySub,
									Cantidad,
									PrecioUnitario,
									IngresadoPor,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodAlmacen."',
									'".$_CommoditySub."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$IngresadoPor."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				//	actualizo
				$sql = "UPDATE lg_commoditystock
						SET
							Cantidad = Cantidad + ".floatval($_CantidadRecibida).",
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CommoditySub = '".$_CommoditySub."' AND
							CodAlmacen = '".$CodAlmacen."'";	fwrite($__archivo, $sql.";\n\n");
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
			
		##	consulto los montos de la orden de compra
		$sql = "SELECT
					MontoAfecto,
					MontoNoAfecto,
					MontoIGV
				FROM lg_ordencompra
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$ReferenciaNroDocumento."'";	fwrite($__archivo, $sql.";\n\n");
		$query_afecto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_afecto) != 0) $field_afecto = mysql_fetch_array($query_afecto);
		
		##	actualizo montos del documento
		if ($MontoAfecto != $field_afecto['MontoAfecto']) {
			$MontoImpuestos = round(($MontoAfecto * $field_afecto['MontoIGV'] / $field_afecto['MontoAfecto']), 2);
		} else {
			$MontoAfecto = $field_afecto['MontoAfecto'];
			$MontoNoAfecto = $field_afecto['MontoNoAfecto'];
			$MontoImpuestos = $field_afecto['MontoIGV'];
		}
		$MontoTotal = $MontoAfecto + $MontoNoAfecto + $MontoImpuestos;
		
		##	actualizo montos del documento
		$sql = "UPDATE ap_documentos
				SET
					MontoAfecto = '".$MontoAfecto."',
					MontoNoAfecto = '".$MontoNoAfecto."',
					MontoImpuestos = '".$MontoImpuestos."',
					MontoTotal = '".$MontoTotal."',
					MontoPendiente = '".$MontoTotal."'
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					DocumentoClasificacion = '".$CodTransaccion."' AND
					DocumentoReferencia = '".$DocumentoReferencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	si es una transaccion de activos fijoss
		if ($FlagActivoFijo == "S") {
			//	inserto activos
			$activo = split(";char:tr;", $activos);
			foreach ($activo as $linea) {
				list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
				$_Descripcion = changeUrl($_Descripcion);
				
				//	consulto si ya fue facturado
				$sql = "SELECT Estado
						FROM ap_documentos
						WHERE
							ReferenciaTipoDocumento = 'OC' AND
							ReferenciaNroDocumento = '".$ReferenciaNroDocumento."' AND
							Anio = '".$Anio."' AND
							Estado = 'RV'";	fwrite($__archivo, $sql.";\n\n");
				$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_documento) != 0) {
					$field_documento = mysql_fetch_array($query_documento);
					//	consulto la fecha
					$sql = "SELECT FechaRegistro
							FROM ap_obligaciones
							WHERE
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$field_documento['ObligacionTipoDocumento']."' AND
								NroDocumento = '".$field_documento['ObligacionNroDocumento']."'";	fwrite($__archivo, $sql.";\n\n");
					$query_obligacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_obligacion) != 0) $field_obligacion = mysql_fetch_array($query_obligacion);				
					//	valores
					$_FlagFacturado = "S";
					$_ObligacionTipoDocumento = $field_documento['ObligacionTipoDocumento'];
					$_ObligacionNroDocumento = $field_documento['ObligacionNroDocumento'];
					$_ObligacionFechaDocumento = $field_obligacion['FechaRegistro'];
				} else {
					$_FlagFacturado = "N";
				}
				
				##	inserto activo
				$sql = "INSERT INTO lg_activofijo (
									CodOrganismo,
									Anio,
									NroOrden,
									Secuencia,
									NroSecuencia,
									CommoditySub,
									Descripcion,
									CodCentroCosto,
									CodClasificacion,
									CodBarra,
									NroSerie,
									Modelo,
									CodProveedor,
									CodDocumento,
									NroDocumento,
									Monto,
									CodUbicacion,
									FechaIngreso,
									FlagFacturado,
									CodMarca,
									Color,
									NroPlaca,
									Estado,
									ObligacionTipoDocumento,
									ObligacionNroDocumento,
									ObligacionFechaDocumento,
									Clasificacion,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$Anio."',
									'".$ReferenciaNroDocumento."',
									'".$_Secuencia."',
									'".$_NroSecuencia."',
									'".$_CommoditySub."',
									'".$_Descripcion."',
									'".$_CodCentroCosto."',
									'".$_CodClasificacion."',
									'".$_CodBarra."',
									'".$_NroSerie."',
									'".$_Modelo."',
									'".$CodProveedor."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Monto."',
									'".$_CodUbicacion."',
									'".formatFechaAMD($_FechaIngreso)."',
									'".$_FlagFacturado."',
									'".$_CodMarca."',
									'".$_Color."',
									'".$_NroPlaca."',
									'PR',
									'".$ObligacionTipoDocumento."',
									'".$ObligacionNroDocumento."',
									'".$ObligacionFechaDocumento."',
									'".$Clasificacion."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		mysql_query("COMMIT");
		die("|$NroDocumento|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroDocumento</strong>");
	}
	
	//	despacho
	elseif ($accion == "despacho") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Anio = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		$NroInterno = getCodigo_3("lg_commoditytransaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_commoditytransaccion (
							CodOrganismo,
							CodDocumento,
							NroDocumento,
							NroInterno,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							ReferenciaNroDocumento,
							DocumentoReferencia,
							DocumentoReferenciaInterno,
							CodUbicacion,
							FlagActivoFijo,
							CodDependencia,
							Anio,
							FlagManual,
							FlagPendiente,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$NroInterno."',
							'".$CodTransaccion."',
							'".$FechaDocumento."',
							'".$Periodo."',
							'".$CodAlmacen."',
							'".$CodCentroCosto."',
							'".$CodDocumentoReferencia."',
							'".$NroDocumentoReferencia."',
							'".$IngresadoPor."',
							'".$RecibidoPor."',
							'".$Comentarios."',
							'".$ReferenciaNroDocumento."',
							'".$DocumentoReferencia."',
							'".$DocumentoReferenciaInterno."',
							'".$CodUbicacion."',
							'".$FlagActivoFijo."',
							'".$CodDependencia."',
							'".$Periodo."',
							'".$FlagManual."',
							'".$FlagPendiente."',
							'CO',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadPedida, $_CantidadPendiente, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadKardex,
								Cantidad,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodAlmacen,
								CodCentroCosto,
								Anio,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadRecibida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$CodAlmacen."',
								'".$_CodCentroCosto."',
								'".$Periodo."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo requerimiento detalle
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadRecibida = CantidadRecibida + '".$_CantidadRecibida."'
					WHERE
						CodRequerimiento = '".$_CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto el stock
			$sql = "SELECT *
					FROM lg_commoditystock
					WHERE
						CommoditySub = '".$_CommoditySub."' AND
						CodAlmacen = '".$CodAlmacen."'";
			$query_stock = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_stock) == 0) {
				//	inserto
				$sql = "INSERT INTO lg_commoditystock (
									CodAlmacen,
									CommoditySub,
									Cantidad,
									PrecioUnitario,
									IngresadoPor,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodAlmacen."',
									'".$_CommoditySub."',
									'-".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$IngresadoPor."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				//	actualizo
				$sql = "UPDATE lg_commoditystock
						SET
							Cantidad = Cantidad - ".floatval($_CantidadRecibida).",
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CommoditySub = '".$_CommoditySub."' AND
							CodAlmacen = '".$CodAlmacen."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroDocumento</strong>");
	}
}

//	transaccion (commodity)
elseif ($modulo == "transaccion_commodity") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	inserto transaccion
		##	genero el nuevo codigo
		$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		##	inserto
		$sql = "INSERT INTO lg_commoditytransaccion (
							CodOrganismo,
							CodDocumento,
							NroDocumento,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							ReferenciaNroDocumento,
							DocumentoReferencia,
							DocumentoReferenciaInterno,
							CodDependencia,
							Anio,
							FlagManual,
							FlagPendiente,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$CodDocumento."',
							'".$NroDocumento."',
							'".$CodTransaccion."',
							'".$FechaDocumento."',
							'".$Periodo."',
							'".$CodAlmacen."',
							'".$CodCentroCosto."',
							'".$CodDocumentoReferencia."',
							'".$NroDocumentoReferencia."',
							'".$IngresadoPor."',
							'".$RecibidoPor."',
							'".$Comentarios."',
							'".$ReferenciaNroDocumento."',
							'".$DocumentoReferencia."',
							'".$DocumentoReferenciaInterno."',
							'".$CodDependencia."',
							'".$Periodo."',
							'".$FlagManual."',
							'".$FlagPendiente."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadKardex,
								Cantidad,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodAlmacen,
								CodCentroCosto,
								Anio,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadRecibida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$CodAlmacen."',
								'".$_CodCentroCosto."',
								'".$Periodo."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	periodo
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	modifico transaccion
		$sql = "UPDATE lg_commoditytransaccion
				SET
					FechaDocumento = '".$FechaDocumento."',
					Periodo = '".$Periodo."',
					CodAlmacen = '".$CodAlmacen."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodDocumentoReferencia = '".$CodDocumentoReferencia."',
					NroDocumentoReferencia = '".$NroDocumentoReferencia."',
					RecibidoPor = '".$RecibidoPor."',
					Comentarios = '".$Comentarios."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					ReferenciaNroDocumento = '".$ReferenciaNroDocumento."',
					DocumentoReferencia = '".$DocumentoReferencia."',
					DocumentoReferenciaInterno = '".$DocumentoReferenciaInterno."',
					CodDependencia = '".$CodDependencia."',
					FlagManual = '".$FlagManual."',
					FlagPendiente = '".$FlagPendiente."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$sql = "DELETE FROM lg_commoditytransacciondetalle
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			##	inserto detalle
			$sql = "INSERT INTO lg_commoditytransacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadKardex,
								Cantidad,
								PrecioUnit,
								Total,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								CodAlmacen,
								CodCentroCosto,
								Anio,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$_Secuencia."',
								'".$_CommoditySub."',
								'".$_Descripcion."',
								'".$_CodUnidad."',
								'".$_CantidadRecibida."',
								'".$_CantidadRecibida."',
								'".$_PrecioUnit."',
								'".$_Total."',
								'".$_ReferenciaCodDocumento."',
								'".$_ReferenciaNroDocumento."',
								'".$_ReferenciaSecuencia."',
								'".$CodAlmacen."',
								'".$_CodCentroCosto."',
								'".$Periodo."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
	
	//	ejecutar
	elseif ($accion == "ejecutar") {
		mysql_query("BEGIN");
		##	genero el nuevo codigo
		$NroInterno = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
		//	
		$sql = "UPDATE lg_commoditytransaccion
				SET
					NroInterno = '".$NroInterno."',
					Estado = 'CO',
					EjecutadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaEjecucion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodDocumento = '".$CodDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CommoditySub, $_Descripcion, $_CodUnidad, $_StockActual, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia, $_CodRequerimiento) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			if ($TipoMovimiento != "I") $_CantidadRecibida *= -1;
			
			##	actualizo inventario
			$sql = "SELECT *
					FROM lg_commoditystock
					WHERE
						CodAlmacen = '".$CodAlmacen."' AND
						CommoditySub = '".$_CommoditySub."'";
			$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_almacen) == 0) {
				##	inserto item en almacen
				$sql = "INSERT INTO lg_commoditystock (
									CommoditySub,
									CodAlmacen,
									Estado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$_CommoditySub."',
									'".$CodAlmacen."',
									'A',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	inserto item en inventario
				$sql = "INSERT INTO lg_commoditystock (
									CodAlmacen,
									CommoditySub,
									Cantidad,
									PrecioUnitario,
									IngresadoPor,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodAlmacen."',
									'".$_CommoditySub."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_SESSION["CODPERSONA_ACTUAL"]."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	actualizo item en inventario
				$sql = "UPDATE lg_commoditystock
						SET
							Cantidad = (Cantidad + ".floatval($_CantidadRecibida)."),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CommoditySub = '".$_CommoditySub."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroInterno</strong>");
	}
}

//	transaccion (caja chica)
elseif ($modulo == "transaccion-cajachica") {
	$Comentarios = changeUrl($Comentarios);
	$FechaDocumento = formatFechaAMD($FechaDocumento);
	
	//	recepcion
	if ($accion == "recepcion") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Anio = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	consulto si es un requerimiento de tipo autoreposicion
		$sql = "SELECT Clasificacion FROM lg_requerimientos WHERE CodRequerimiento = '".$CodRequerimiento."'";
		$query_rau = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_rau) != 0) $field_rau = mysql_fetch_array($query_rau);
		
		//	si es dirigido a commoditys
		if ($FlagCommodity == "S") {
			##	activos
			if ($FlagActivoFijo == "S") {
				$activo = split(";char:tr;", $activos);
				foreach ($activo as $linea) {
					list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
					//	consulto
					$sql = "SELECT Estado
							FROM lg_activofijo
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Anio = '".$Anio."' AND
								NroOrden = '".$ReferenciaNroDocumento."' AND
								Secuencia = '".$_Secuencia."' AND
								NroSecuencia = '".$_NroSecuencia."'";
					$query_activo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_activo) != 0) die("Se encontraron lineas en la ficha de <strong>Activos Asociados</strong> ya ingresados");
				}
			}
			
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo_3("lg_commoditytransaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			##	inserto
			$sql = "INSERT INTO lg_commoditytransaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodUbicacion,
								FlagActivoFijo,
								CodDependencia,
								Anio,
								FlagManual,
								FlagPendiente,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodUbicacion."',
								'".$FlagActivoFijo."',
								'".$CodDependencia."',
								'".$Periodo."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo_3("lg_transaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			##	inserto
			$sql = "INSERT INTO lg_transaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								FlagManual,
								FlagPendiente,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodDependencia,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodDependencia."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				##	inserto detalle
				$sql = "INSERT INTO lg_commoditytransacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CommoditySub,
									Descripcion,
									CodUnidad,
									CantidadKardex,
									Cantidad,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaSecuencia,
									CodAlmacen,
									CodCentroCosto,
									Anio,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CommoditySub."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadRecibida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaSecuencia."',
									'".$CodAlmacen."',
									'".$_CodCentroCosto."',
									'".$Periodo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	inserto detalle
				$sql = "INSERT INTO lg_transacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CodItem,
									Descripcion,
									CodUnidad,
									CantidadPedida,
									CantidadRecibida,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaSecuencia,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CodItem."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadPedida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaSecuencia."',
									'".$_CodCentroCosto."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	actualizo requerimientos (solo si es un requerimiento de autoreposicion)
			if ($field_rau['Clasificacion'] == $_PARAMETRO['REQRAU']) {
				##	si se scompleto el despacho
				if ($_CantidadPedida == $_CantidadRecibida) {
					##	completo detalle del requerimiento
					$sql = "UPDATE lg_requerimientosdet
							SET Estado = 'CO'
							WHERE
								CodRequerimiento = '".$CodRequerimiento."' AND
								Secuencia = '".$_ReferenciaSecuencia."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					##	si se completaron los detalles
					$sql = "SELECT *
							FROM lg_requerimientosdet
							WHERE
								CodRequerimiento = '".$CodRequerimiento."' AND
								Estado = 'PE'";
					$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_pendientes) == 0) {
						##	completo requerimiento
						$sql = "UPDATE lg_requerimientos
								SET Estado = 'CO'
								WHERE CodRequerimiento = '".$CodRequerimiento."'";
						$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
				##	actualizo cantidad recibida
				$sql = "UPDATE lg_requerimientosdet
						SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}			
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadOrdenCompra = (CantidadOrdenCompra + ".floatval($_CantidadRecibida).")
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				//	consulto el stock
				$sql = "SELECT *
						FROM lg_commoditystock
						WHERE
							CommoditySub = '".$_CommoditySub."' AND
							CodAlmacen = '".$CodAlmacen."'";
				$query_stock = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_stock) == 0) {
					//	inserto
					$sql = "INSERT INTO lg_commoditystock (
										CodAlmacen,
										CommoditySub,
										Cantidad,
										PrecioUnitario,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CommoditySub."',
										'".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$IngresadoPor."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	actualizo
					$sql = "UPDATE lg_commoditystock
							SET
								Cantidad = Cantidad + ".floatval($_CantidadRecibida).",
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CommoditySub = '".$_CommoditySub."' AND
								CodAlmacen = '".$CodAlmacen."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			} else {
				##	consulto el stock
				$sql = "SELECT *
						FROM lg_itemalmacen
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CodItem = '".$_CodItem."'";
				$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_almacen) == 0) {
					##	inserto item en almacen
					$sql = "INSERT INTO lg_itemalmacen (
										CodItem,
										CodAlmacen,
										Estado,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$_CodItem."',
										'".$CodAlmacen."',
										'A',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					##	inserto item en inventario
					$sql = "INSERT INTO lg_itemalmaceninv (
										CodAlmacen,
										CodItem,
										Proveedor,
										FechaIngreso,
										StockIngreso,
										StockActual,
										PrecioUnitario,
										DocReferencia,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CodItem."',
										'".$CodProveedor."',
										NOW(),
										'".$_CantidadRecibida."',
										'".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$_ReferenciaCodDocumento."-".$_ReferenciaNroDocumento."',
										'".$_SESSION["CODPERSONA_ACTUAL"]."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					##	actualizo item en inventario
					$sql = "UPDATE lg_itemalmaceninv
							SET
								StockActual = (StockActual + ".floatval($_CantidadRecibida)."),
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CodAlmacen = '".$CodAlmacen."' AND
								CodItem = '".$_CodItem."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		//	si es dirigido a commoditys
		if ($FlagCommodity == "S") {
			//	si es una transaccion de activos fijoss
			if ($FlagActivoFijo == "S") {
				//	inserto activos
				$activo = split(";char:tr;", $activos);
				foreach ($activo as $linea) {
					list($_Secuencia, $_NroSecuencia, $_CommoditySub, $_Descripcion, $_CodClasificacion, $_Monto, $_NroSerie, $_FechaIngreso, $_Modelo, $_CodBarra, $_CodUbicacion, $_CodCentroCosto, $_NroPlaca, $_CodMarca, $_Color) = split(";char:td;", $linea);
					$_Descripcion = changeUrl($_Descripcion);
					
					//	consulto si ya fue facturado
					$sql = "SELECT Estado
							FROM ap_documentos
							WHERE
								ReferenciaTipoDocumento = 'OC' AND
								ReferenciaNroDocumento = '".$ReferenciaNroDocumento."' AND
								Anio = '".$Anio."' AND
								Estado = 'RV'";
					$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_documento) != 0) {
						$field_documento = mysql_fetch_array($query_documento);
						//	consulto la fecha
						$sql = "SELECT FechaRegistro
								FROM ap_obligaciones
								WHERE
									CodProveedor = '".$CodProveedor."' AND
									CodTipoDocumento = '".$field_documento['ObligacionTipoDocumento']."' AND
									NroDocumento = '".$field_documento['ObligacionNroDocumento']."'";
						$query_obligacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
						if (mysql_num_rows($query_obligacion) != 0) $field_obligacion = mysql_fetch_array($query_obligacion);				
						//	valores
						$_FlagFacturado = "S";
						$_ObligacionTipoDocumento = $field_documento['ObligacionTipoDocumento'];
						$_ObligacionNroDocumento = $field_documento['ObligacionNroDocumento'];
						$_ObligacionFechaDocumento = $field_obligacion['FechaRegistro'];
					} else {
						$_FlagFacturado = "N";
					}
					
					##	inserto activo
					$sql = "INSERT INTO lg_activofijo (
										CodOrganismo,
										Anio,
										NroOrden,
										Secuencia,
										NroSecuencia,
										CommoditySub,
										Descripcion,
										CodCentroCosto,
										CodClasificacion,
										CodBarra,
										NroSerie,
										Modelo,
										CodProveedor,
										CodDocumento,
										NroDocumento,
										Monto,
										CodUbicacion,
										FechaIngreso,
										FlagFacturado,
										CodMarca,
										Color,
										NroPlaca,
										Estado,
										ObligacionTipoDocumento,
										ObligacionNroDocumento,
										ObligacionFechaDocumento,
										Clasificacion,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodOrganismo."',
										'".$Anio."',
										'".$ReferenciaNroDocumento."',
										'".$_Secuencia."',
										'".$_NroSecuencia."',
										'".$_CommoditySub."',
										'".$_Descripcion."',
										'".$_CodCentroCosto."',
										'".$_CodClasificacion."',
										'".$_CodBarra."',
										'".$_NroSerie."',
										'".$_Modelo."',
										'".$CodProveedor."',
										'".$CodDocumento."',
										'".$NroDocumento."',
										'".$_Monto."',
										'".$_CodUbicacion."',
										'".formatFechaAMD($_FechaIngreso)."',
										'".$_FlagFacturado."',
										'".$_CodMarca."',
										'".$_Color."',
										'".$_NroPlaca."',
										'PR',
										'".$ObligacionTipoDocumento."',
										'".$ObligacionNroDocumento."',
										'".$ObligacionFechaDocumento."',
										'".$Clasificacion."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroInterno</strong>");
	}
	
	//	despacho
	elseif ($accion == "despacho") {
		mysql_query("BEGIN");
		//	errores
		##	periodo
		$Anio = substr($FechaDocumento, 0, 4);
		$Periodo = substr($FechaDocumento, 0, 7);
		if (!periodoAbierto($CodOrganismo, $Periodo)) die("No se puede generar ninguna transacci&oacute;n porque no se ha abierto el periodo $Periodo.");
		
		//	si es dirigido a commoditys
		if ($FlagCommodity == "S") {
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo_3("lg_commoditytransaccion", "NroInterno", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			##	inserto
			$sql = "INSERT INTO lg_commoditytransaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodDependencia,
								Anio,
								FlagManual,
								FlagPendiente,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodDependencia."',
								'".$Periodo."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	inserto transaccion
			##	genero el nuevo codigo
			$NroDocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			$NroInterno = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $CodOrganismo, $CodDocumento, 6);
			##	inserto
			$sql = "INSERT INTO lg_transaccion (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								NroInterno,
								CodTransaccion,
								FechaDocumento,
								Periodo,
								CodAlmacen,
								CodCentroCosto,
								CodDocumentoReferencia,
								NroDocumentoReferencia,
								IngresadoPor,
								RecibidoPor,
								Comentarios,
								FlagManual,
								FlagPendiente,
								ReferenciaNroDocumento,
								DocumentoReferencia,
								DocumentoReferenciaInterno,
								CodDependencia,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$CodDocumento."',
								'".$NroDocumento."',
								'".$NroInterno."',
								'".$CodTransaccion."',
								'".$FechaDocumento."',
								'".$Periodo."',
								'".$CodAlmacen."',
								'".$CodCentroCosto."',
								'".$CodDocumentoReferencia."',
								'".$NroDocumentoReferencia."',
								'".$IngresadoPor."',
								'".$RecibidoPor."',
								'".$Comentarios."',
								'".$FlagManual."',
								'".$FlagPendiente."',
								'".$ReferenciaNroDocumento."',
								'".$DocumentoReferencia."',
								'".$DocumentoReferenciaInterno."',
								'".$CodDependencia."',
								'CO',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto detalles
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			$_Secuencia++;
			list($_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadRecibida, $_PrecioUnit, $_Total, $_CodCentroCosto, $_ReferenciaCodDocumento, $_ReferenciaNroDocumento, $_ReferenciaSecuencia) = split(";char:td;", $linea);
			$_Descripcion = changeUrl($_Descripcion);
			$_PrecioCantidad = $_CantidadRecibida * $_PrecioUnit;
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				##	inserto detalle
				$sql = "INSERT INTO lg_commoditytransacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CommoditySub,
									Descripcion,
									CodUnidad,
									CantidadKardex,
									Cantidad,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaSecuencia,
									CodAlmacen,
									CodCentroCosto,
									Anio,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CommoditySub."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadRecibida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaSecuencia."',
									'".$CodAlmacen."',
									'".$_CodCentroCosto."',
									'".$Periodo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				##	inserto detalle
				$sql = "INSERT INTO lg_transacciondetalle (
									CodOrganismo,
									CodDocumento,
									NroDocumento,
									Secuencia,
									CodItem,
									Descripcion,
									CodUnidad,
									CantidadPedida,
									CantidadRecibida,
									PrecioUnit,
									Total,
									ReferenciaCodDocumento,
									ReferenciaNroDocumento,
									ReferenciaSecuencia,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodOrganismo."',
									'".$CodDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'".$_CodItem."',
									'".$_Descripcion."',
									'".$_CodUnidad."',
									'".$_CantidadPedida."',
									'".$_CantidadRecibida."',
									'".$_PrecioUnit."',
									'".$_Total."',
									'".$_ReferenciaCodDocumento."',
									'".$_ReferenciaNroDocumento."',
									'".$_ReferenciaSecuencia."',
									'".$_CodCentroCosto."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	actualizo requerimientos
			##	si se scompleto el despacho
			if ($_CantidadPedida == $_CantidadRecibida) {
				##	completo detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET Estado = 'CO'
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Secuencia = '".$_ReferenciaSecuencia."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				##	si se completaron los detalles
				$sql = "SELECT *
						FROM lg_requerimientosdet
						WHERE
							CodRequerimiento = '".$CodRequerimiento."' AND
							Estado = 'PE'";
				$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes) == 0) {
					##	completo requerimiento
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE CodRequerimiento = '".$CodRequerimiento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			##	actualizo cantidad pendiente
			$sql = "UPDATE lg_requerimientosdet
					SET CantidadRecibida = (CantidadRecibida + ".floatval($_CantidadRecibida).")
					WHERE
						CodRequerimiento = '".$CodRequerimiento."' AND
						Secuencia = '".$_ReferenciaSecuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	
			
			//	si es dirigido a commoditys
			if ($FlagCommodity == "S") {
				//	consulto el stock
				$sql = "SELECT *
						FROM lg_commoditystock
						WHERE
							CommoditySub = '".$_CommoditySub."' AND
							CodAlmacen = '".$CodAlmacen."'";
				$query_stock = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_stock) == 0) {
					//	inserto
					$sql = "INSERT INTO lg_commoditystock (
										CodAlmacen,
										CommoditySub,
										Cantidad,
										PrecioUnitario,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CommoditySub."',
										'-".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$IngresadoPor."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	actualizo
					$sql = "UPDATE lg_commoditystock
							SET
								Cantidad = Cantidad - ".floatval($_CantidadRecibida).",
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CommoditySub = '".$_CommoditySub."' AND
								CodAlmacen = '".$CodAlmacen."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			} else {
				##	consulto el stock
				$sql = "SELECT *
						FROM lg_itemalmacen
						WHERE
							CodAlmacen = '".$CodAlmacen."' AND
							CodItem = '".$_CodItem."'";
				$query_almacen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_almacen) == 0) {
					##	inserto item en almacen
					$sql = "INSERT INTO lg_itemalmacen (
										CodItem,
										CodAlmacen,
										Estado,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$_CodItem."',
										'".$CodAlmacen."',
										'A',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					##	inserto item en inventario
					$sql = "INSERT INTO lg_itemalmaceninv (
										CodAlmacen,
										CodItem,
										Proveedor,
										FechaIngreso,
										StockIngreso,
										StockActual,
										PrecioUnitario,
										DocReferencia,
										IngresadoPor,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodAlmacen."',
										'".$_CodItem."',
										'".$CodProveedor."',
										NOW(),
										'-".$_CantidadRecibida."',
										'-".$_CantidadRecibida."',
										'".$_PrecioUnit."',
										'".$_ReferenciaCodDocumento."-".$_ReferenciaNroDocumento."',
										'".$_SESSION["CODPERSONA_ACTUAL"]."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					##	actualizo item en inventario
					$sql = "UPDATE lg_itemalmaceninv
							SET
								StockActual = (StockActual - ".floatval($_CantidadRecibida)."),
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								CodAlmacen = '".$CodAlmacen."' AND
								CodItem = '".$_CodItem."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		mysql_query("COMMIT");
		die("|Se ha generado la Transacci&oacute;n <strong>Nro. $CodDocumento-$NroInterno</strong>");
	}
}
?>
