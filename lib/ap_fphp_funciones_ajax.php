<?php
include("fphp.php");
include("ap_fphp.php");
extract($_POST);
extract($_GET);
//	--------------------------
if ($accion == "getCuentaBancariaDefault") {
	echo getCuentaBancariaDefault($codorganismo, $codtipopago);
}
?>