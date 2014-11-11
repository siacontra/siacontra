<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("fphp_ap.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reportes Generados del Pago</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<table width="1000" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS -->
			<li><a onclick="tab_reporte('ap_pagos_imprimir_transferir_sustento_pdf.php?nroproceso=<?=$nroproceso?>&codtipopago=<?=$codtipopago?>&nrocuenta=<?=$nrocuenta?>&codproveedor=<?=$codproveedor?>');" href="#">Sustento</a></li>
            
			<li><a onclick="tab_reporte('ap_pagos_imprimir_transferir_voucher_pdf.php?nroproceso=<?=$nroproceso?>&codtipopago=<?=$codtipopago?>&nrocuenta=<?=$nrocuenta?>&codproveedor=<?=$codproveedor?>');" href="#">Voucher</a></li>
            
            <?php
			if ($codtipopago == "02") {
				?><li><a onclick="tab_reporte('ap_pagos_imprimir_transferir_cheques_pdf.php?nroproceso=<?=$nroproceso?>&codtipopago=<?=$codtipopago?>&nrocuenta=<?=$nrocuenta?>&codproveedor=<?=$codproveedor?>');" href="#">Cheque/Carta</a></li><?
			} else {
				?><li><a onclick="tab_reporte('ap_pagos_imprimir_transferir_nocheques_pdf.php?nroproceso=<?=$nroproceso?>&codtipopago=<?=$codtipopago?>&nrocuenta=<?=$nrocuenta?>&codproveedor=<?=$codproveedor?>');" href="#">Cheque/Carta</a></li><?
			}
			?>
            
			<li><a onclick="tab_reporte('ap_pagos_imprimir_transferir_retenciones_pdf.php?nroproceso=<?=$nroproceso?>&codtipopago=<?=$codtipopago?>&nrocuenta=<?=$nrocuenta?>&codproveedor=<?=$codproveedor?>');" href="#">Retenciones</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:650px;" src="ap_pagos_imprimir_transferir_voucher_pdf.php?nroproceso=<?=$nroproceso?>&codtipopago=<?=$codtipopago?>&nrocuenta=<?=$nrocuenta?>&codproveedor=<?=$codproveedor?>"></iframe>
</center>



</body>
</html>