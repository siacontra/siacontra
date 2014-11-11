<? session_start(); //inicias session

extract($_REQUEST); // recojes las variables que enviaste por post o get...  puedes hacerlo también de la forma normal  $_POST['input'];  o $_GET['input']; 

if(isset($_SESSION['formapago'])) // si la variable esta definida
    $formapago=$_SESSION['formapago']; // pasas los datos que ya contenía a otro array

// a continuación creas un array con los datos seleccionados en tu pagina.. 
    $formapago['pago']=array('documento'=>$documento, 'forma_pago'=>$forma_pago,'cantidad_cheque'=>$cantidad_cheque,'dias_cheque'=>$dias_cheque, 'banco'=>$banco,'banco2'=>$banco2); 


    $_SESSION['formapago']=$formapago;  // Pasa lel array a la variable de session el que se encargará de llevar los datos por todas las paginas..

    header("Location:../resumen_compra.php?".SID); // redireccionas donde quieras  
?>
<!-- ///////////////////////////// ************************ ///////////////////////// *************************  -->
Para extraer los datos desde la variable de session, colocas
<!-- ///////////////////////////// ************************ ///////////////////////// *************************  -->
<? session_start(); //inicias session

if(isset($_SESSION['formapago'])) // si la variable esta definida
    $formapago=$_SESSION['formapago']; // pasas los datos que ya contenía a otro array

 // y manipulas los datos a tu antojo,,.
print_r($formapago); //así imprimes para ver los datos que contiene tu array ,, para k veas si logras cargarle datos  
?>

<!-- ///////////////////////////// ************************ ///////////////////////// *************************  -->
este es mi codigo completo, aprovecho y lo comparto es muy util.
<!-- ///////////////////////////// ************************ ///////////////////////// *************************  -->
<?php
//CONEXION BASE DE DATOS
function conectar() { 
    $base_de_datos = "";
    $db_usuario = ""; 
    $db_password = ""; 
   
    if (!($link = mysql_connect("", $db_usuario, $db_password))) 
    { 
        echo "Error conectando a la base de datos."; 
        exit(); 
    } 
    if (!mysql_select_db($base_de_datos, $link)) 
    { 
        echo "Error seleccionando la base de datos."; 
        exit(); 
    } 
    return $link; 
} 
$db = conectar();   

require ("../funciones.php");                        
                              
if($_GET['p']){
$pagina = $_GET['p'];    
}
$muestraindex = 12;
if (!$pagina) { 
    $inicio = 0; 
    $pagina = 1; 
} 
else { 
    $inicio = ($pagina - 1) * $muestraindex; 
}

$url = ''; //venta=ON&alquiler=ON&barrio=none&funcion=Buscar
if($_GET['orden']){
$url .=    "&orden=".$_GET['orden']."";

$orden = "ORDER BY ".strtolower($_GET['orden'])." ASC";
    
}

if($_GET['funcion']){
$busca = 0;
$where ='WHERE ';
$url .=    "&funcion=Buscar";
if(($_GET['barrio'] != 'none') && ($_GET['barrio'] != '')){
    $where .= "barrio='".$_GET['barrio']."'";
$url .=    "&barrio=".$_GET['barrio'].""; 
$busca = 1;    
}
    
if($_GET['venta'] && $_GET['alquiler']){
if(($_GET['barrio'] != 'none') && ($_GET['barrio'] != '')){
    $where .= " AND ";
}    
$where .= "(condicion='Venta' OR condicion='Alquiler' AND domingo = 'dom')";
$url .=    "&venta=ON&alquiler=ON";
$busca = 1;    
}
else{
if($_GET['venta']){
if(($_GET['barrio'] != 'none') && ($_GET['barrio'] != '')){
    $where .= " AND ";
}    
$where .= "condicion='Venta' AND domingo = 'dom'";
$url .=    "&venta=ON";
$busca = 1;        
}
if($_GET['alquiler']){
if(($_GET['barrio'] != 'none') && ($_GET['barrio'] != '')){
    $where .= " AND ";
}    
$where .= "condicion='Alquiler' AND domingo = 'dom'";
$url .=    "&alquiler=ON";
$busca = 1;        
}
}

if($busca == 0){
$where = '';    
}

                                
//echo $where;
//echo "<br>buskeda: SELECT * FROM clasificados $where $orden LIMIT $inicio, $muestraindex<br>";
$resultados = mysql_query("SELECT * FROM clasificados $where $orden LIMIT $inicio, $muestraindex");        
}
else{
//echo "<br>normal: SELECT * FROM clasificados $orden LIMIT $inicio, $muestraindex<br>";
$resultados = mysql_query("SELECT * FROM clasificados $orden LIMIT $inicio, $muestraindex");    
}

$filas = 1;
echo "<table border=\"0\"><tr><td width='225' valign='top'>"; 

if(mysql_num_rows($resultados) > 0){
while($row=mysql_fetch_array($resultados)) {
if($filas == 7){
echo "</td><td width='225' valign='top' align='left'>";    
}    
            echo "<table width='215' border='0' cellspacing='0' cellpadding='0'>
    <tr>
      <td><div align='left'>
        <table width='100%'  border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td><strong><span class='Estilo3'>$row[barrio] $row[ambientes] </span></strong></td>
            <td><div align='right'><strong><span class='Estilo3'>$row[signo] $row[valor]</span></strong><span class='Estilo3'></span></div></td>
          </tr>
        </table>
<span class='Estilo1'>$row[descripsion]</strong></span><br />
              <span class='Estilo1'>$row[direccion] / $row[telefono]</span><br />
              <span class='Estilo200'><a href='http://websinmob.argenprop.com.ar/buscador/busqueda_codigo.asp?be=$row[citec]' target='_blank'><img src='../clasificados/vermas.gif' width='79' height='18' border='0' align='absmiddle'/></a> <input name='seleccion[]' type='checkbox' value=$row[id]></span>       
      </div>
        <hr align='left' width='215' size='1' noshade></td>
    </tr>
  </table>";
  $filas++;
}

 
echo "</td></tr></table>";
echo "<center>";
$resultados = mysql_query("SELECT id FROM clasificados $where");
//echo "<br>paginado: SELECT id FROM clasificados $where<br>";
$total_registros = mysql_num_rows($resultados); 
$total_paginas = ceil($total_registros / $muestraindex); 
    if($total_registros) {
        if(($pagina - 1) > 0) {
echo '<a href=\'?p='.($pagina-1).$url.'\' style="font-size:13pt;color:black;text-decoration:none;">< Anterior </a> ';
        }
        
for ($i = 1; $i <= $total_paginas; $i++) {

if($i == $pagina){
echo ' | <b>'.$i.'</b> ';    
}
else{
echo ' | <a href=\'?p='.$i.$url.'\' style="font-size:13pt;color:black;text-decoration:none;">'.$i.'</a> ';    
}

}        
        
        if(($pagina + 1)<=$total_paginas) {
echo ' | <a href=\'?p='.($pagina+1).$url.'\' style="font-size:13pt;color:black;text-decoration:none;"> Siguiente ></a>';
        }

    }
echo "</center>";

}
else{
echo "No hay resultados que concuerden con su busqueda. Intente nuevamente.";    
}
?> 