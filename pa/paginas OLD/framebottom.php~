<?php
$navegador=EREG("Firefox", $HTTP_USER_AGENT);
if ($navegador==1) {
	echo "
	<html>
	<head>
	<title>Sistema Integral Administrativo [S.I.A]x</title>
	</head>
	<frameset id='frmSet' frameborder='no' border='0' rows='38px, *'>
	<frame noresize scrolling='no'  src='menu.php'>
	<frame noresize src='framemain.php' name='main' id='main'>
	</frameset>
	<noframes></noframes>
	<body></body>
	</html>";
} else {
	$navegador=EREG("Opera", $HTTP_USER_AGENT);
	if ($navegador==1) {
		echo "
		<html>
		<head>
		<title>Sistema Integral Administrativo [S.I.A]xx</title>
		</head>
		<frameset id='frmSet' frameborder='no' border='0' rows='30px, *'>
		<frame noresize scrolling='no'  src='menu.php'>
		<frame noresize src='framemain.php' name='main' id='main'>
		</frameset>
		<noframes></noframes>
		<body></body>
		</html>";
	} else {
		echo "
		<html>
		<head>
		<title>Sistema Integral Administrativo [S.I.A]xxx</title>
		</head>
		<frameset id='frmSet' frameborder='no' border='0' rows='37px, *'>
		<frame noresize scrolling='no'  src='menu.php'>
		<frame noresize src='framemain.php' name='main' id='main'>
		</frameset>
		<noframes></noframes>
		<body></body>
		</html>";
	}
}



?>