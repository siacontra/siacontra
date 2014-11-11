<?php
session_start("SIACEDA");
extract($_POST);
extract($_GET);
include("fphp.php");
//	-------------------------------
//	-------------------------------
list($im, $_error) = copiarImagenTMP($_imagen);
echo "$im $_error";
?>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js" charset="utf-8"></script>
<script>
$(document).ready(function() {
	parent.$("#<?=$imgFoto?>").attr("src", "../imagenes/tmp/<?=$im?>");
	setTimeout(function() {
		$.ajax({
			type: "POST",
			url: "fphp_funciones_ajax.php",
			data: "accion=unlink&url=../imagenes/tmp/<?=$im?>",
			async: false,
			success: function(resp) {}
		});
	}, 4000);
});
</script>