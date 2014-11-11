<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
require('tcpdf/tcpdf.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
extract ($_POST);
extract ($_GET);

$filtro=strtr($filtro, "*", "'");

class PDF extends FPDF
{
//Page header
    function Header($Servicio,$Area){

            
            $this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
            $this->SetFont('Arial', 'B', 9);
            $this->SetXY(20, 10); $this->Cell(70, 5,utf8_decode('República Bolivariana de Venezuela'. $Parametros), 0, 1, 'L');
            $this->SetXY(20, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L'); 
            $this->Ln(4);

            $this->SetXY(200, 10);$this->Cell(10,5,'FORMULARIO BM-1',0,1,'');
            $this->SetXY(200, 15);$this->Cell(10,5,utf8_decode('Hoja N°'),0,1,'');
            //$this->SetXY(183, 20);$this->Cell(7,5,utf8_decode('Año:'),0,0,'');$this->Cell(6,5,date('Y'),0,1,'L');

            global $Periodo, $filtro;
        
            list($fano, $fmes) = SPLIT('[-]',$Periodo);
            switch ($fmes) {
                    case "01": $mes = Enero; break;  
                    case "02": $mes = Febrero;break; 
                    case "03": $mes = Marzo;break;   
                    case "04": $mes = Abril;break;   
                    case "05": $mes = Mayo;break;    
                    case "06": $mes = Junio;break;
                    case "07": $mes = Julio; break;
                    case "08": $mes = Agosto; break;
                    case "09": $mes = Septiembre; break;
                    case "10": $mes = Octubre; break;
                    case "11": $mes = Noviembre; break;
                    case "12": $mes = Diciembre; break;
            }

            $this->SetFont('Arial', 'B', 8);

            $sql = "select a.* from af_activo a where CodOrganismo<>'' $filtro"; //echo $sql;
            $qry = mysql_query($sql) or die ($sql.mysql_error());
            $field = mysql_fetch_array($qry); 

            $scon01 = "select 
                                             CodDependencia, Dependencia, CodPersona 
                                    from 
                                         mastdependencias a 
                               where 
                                         CodDependencia='".$field['CodDependencia']."'";
            $qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
            $fcon01 = mysql_fetch_array($qcon01);

            $scon02 = "select 
                                             a.*,
                                             b.DescripCargo,
                                             c.NomCompleto,
                                             c.CodPersona 
                                     from 
                                         rh_empleadonivelacion a 
                                             inner join rh_puestos b on (b.CodCargo=a.CodCargo) 
                                             inner join mastpersonas c on (c.CodPersona=a.CodPersona)
                                    where 
                                         a.Secuencia=(select max(Secuencia) from rh_empleadonivelacion where CodPersona='".$fcon01['CodPersona']."') and 
                                             a.CodPersona = '".$fcon01['CodPersona']."'";
             $qcon02 = mysql_query($scon02) or die ($scon02.mysql_error());
             $fcon02 = mysql_fetch_array($qcon02);

             $cod_personaDependencia=$fcon02['CodPersona']; 


            $s_cons = "select 
                                   b.CodOrganismo, 
                                                       b.Organismo,

                                                       d.Dependencia,
                                                       e.Descripcion
                                              from 
                                                   af_activo a
                                                   inner join mastorganismos b on (b.CodOrganismo=a.CodOrganismo)

                                                   inner join mastdependencias d on (d.CodDependencia=a.CodDependencia)
                                                    inner join af_ubicaciones e on (e.CodUbicacion=a.Ubicacion)
                                                      where 
                                                   a.CodOrganismo='".$field['CodOrganismo']."' and 

                                                       a.Ubicacion='".$field['Ubicacion']."' and  
                                                       a.CodDependencia='".$field['CodDependencia']."'";  //echo $s_organismo; 
            $q_cons = mysql_query($s_cons) or die ($s_cons.mysql_error());//      a.CentroCosto='".$field['CentroCosto']."' and    c.Descripcion as DescpCentroCosto,   inner join ac_mastcentrocosto c on (c.CodCentroCosto=a.CentroCosto)
            $r_cons = mysql_num_rows($q_cons);
            if($r_cons!="") $f_cons=mysql_fetch_array($q_cons);


            $s_estado = "select 
                                               a.Direccion,
                               d.Estado,
                                               c.Municipio 
                                       from 
                                            mastorganismos a
                                                    inner join mastciudades b on (b.CodCiudad = a.CodCiudad) 
                                                    inner join mastmunicipios c on (c.CodMunicipio = b.CodMunicipio) 
                                                    inner join mastestados d on (d.CodEstado = c.CodEstado) 
                                      where 
                                            a.CodOrganismo = '".$field['CodOrganismo']."'"; 
            $q_estado = mysql_query($s_estado) or die ($s_estado.mysql_error());
            $r_estado = mysql_num_rows($q_estado);

            if($r_estado!="")$f_estado=mysql_fetch_array($q_estado);


            $cadena=strtoupper(utf8_decode($f_cons['Descripcion']));

    // Para pasar a minúsculas
    $texto = strtolower($texto);
    // Para pasar a mayúsculas
    $texto = strtoupper($texto);
    // Para pasar a mayúsculas solo la primera letra de cada palabra
    $texto = ucwords($texto);
    // Para pasar a mayúsculas solo la primera letra de toda la cadena
    $texto = ucfirst($texto) ;

    
            $this->SetXY(10,22);$this->SetFont('Arial', 'B', 8);
            $this->Cell(35, 3, 'ENTIDAD PROPIETARIA:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);	$this->Cell(80, 3, utf8_decode($f_cons['Organismo']), 0, 0, 'L');
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(17, 3, 'SERVICIO:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);	
            $this->MultiCell(110, 3, $Servicio,  0, 'L');
            $this->SetXY(10,25);$this->SetFont('Arial', 'B', 8);
            $this->Cell(45, 3, 'UNIDAD DE TRABAJO O AREA:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->Cell(100, 3, $Area, 0, 1, 'L');
            $this->SetXY(10,28);$this->SetFont('Arial', 'B', 8);
            $this->Cell(16, 3, 'ESTADO:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);$this->Cell(35, 3, $f_estado['Estado'], 0, 0, 'L');
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(16, 3, 'DISTRITO:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);$this->Cell(35, 3, $f_estado['Estado'], 0, 0, 'L');
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(18, 3, 'MUNICIPIO:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);$this->Cell(35, 3, $f_estado['Municipio'], 0, 1, 'L');

            $this->SetXY(10,31);$this->SetFont('Arial', 'B', 8);
            $this->Cell(32, 3, 'DIRECCION O LUGAR:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);$this->Cell(100, 3, utf8_decode($f_estado['Direccion']), 0, 0, 'L');
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(12, 3, 'FECHA:', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);$this->Cell(10, 3, date("d/m/Y"), 0, 1, 'L'); $this->Ln(5);

        


            $this->SetFont('Arial', 'B', 8);
            $this->Cell(48, 7, utf8_decode('Clasificación(Código)'), 1, 0, 'C');
            $this->Cell(30, 14, utf8_decode('N° Identificación'), 1, 0, 'C');
            $this->Cell(130, 14, utf8_decode('Nombre y Descripción de los Elementos'), 1, 0, 'C');
            $this->Cell(30, 14, utf8_decode('Valor Unitario Bs.'), 1, 0, 'C');
            $this->SetXY(10,'46');
            $this->Cell(16, 7, utf8_decode('Grupo'), 1, 0, 'C'); 
            $this->Cell(16, 7, utf8_decode('SubGrupo'), 1, 0, 'C');
            $this->Cell(16, 7, utf8_decode('Sección'), 1, 1, 'C');
    }
    

//Page footer
    function Footer(){
        //Position at 1.5 cm from bottom
        $this->SetXY(164,13);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Page number
        $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
                    $this->SetFont('Arial', 'B', 8);

                    //$this->GetY(10, 227);
    $this->SetXY(10,'165');
                    $ancho = 65;
                    //Titulo del cuadro de firmas
                    $this->Cell(65 , 5, 'PREPARADO POR', 1, 0, 'C');
                    $this->Cell(65 , 5, 'CONFORMADO POR ', 1, 0, 'C');
                    $this->Cell(65 , 5, 'APROBADO POR', 1, 1, 'C');
                    //Firmantes
                    $this->SetFont('Arial', '', 8);
                $this->Cell($ancho , 5, utf8_decode('Libny Salazar'), 1, 0, 'C'); 
                    $this->Cell($ancho , 5, utf8_decode('Roxaida Estrada'), 1, 0, 'C');
                    $this->Cell($ancho , 5, utf8_decode('Freddy Cudjoe'), 1, 1, 'C');
                    //Cargos de los firmantes
                    $this->Cell($ancho , 5, utf8_decode('Asistente de Administración I'), 1, 0, 'C');
                    $this->Cell($ancho , 5, utf8_decode('Directora de Administración y Presupuesto (E)'), 1, 0, 'C');
                    $this->Cell($ancho , 5, utf8_decode('Contralor del estado Monagas (P)'), 1, 1, 'C');
            //Linea de firmas
                    $this->SetFont('Arial', 'I', 6);	
            $this->Cell($ancho , 15,'FIRMA', 1, 0, 'L');
                    $this->Cell($ancho , 15, 'FIRMA', 1, 0, 'L');
                    $this->Cell($ancho , 15, 'FIRMA', 1, 1, 'L');

            //Cuadro para el Sello	
            $this->SetXY(205,165);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        $this->Cell(43, 30, 'SELLO', 1, 1, 'C');
    }
}

//Instanciation of inherited class
$pdf=new PDF('L','mm','letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10,10,10,10);
$pdf->SetFont('Times','',12);



//// ---- Consulta para obtener datos 
$sactivo = "select 
				  a.*, 
                                  a.Ubicacion as Ubica,
				  b.Descripcion as DescpClasificacion20,
				  c.Descripcion as DescpUbicacion,
                                  c.CodUbicacion as CodUbicacion,
				  b.Nivel,
				  b.CodClasificacion,
                                  d.Dependencia as Dependencia
			  from
				  af_activo a 
				  inner join af_clasificacionactivo20 b on (b.CodClasificacion=a.ClasificacionPublic20) 
				  inner join af_ubicaciones c on (c.CodUbicacion=a.Ubicacion)
				  inner join mastdependencias d on (d.CodDependencia=a.CodDependencia)
			 where 
                               d.Estado = 'A'
			       $filtro
   			ORDER BY d.Dependencia, c.Descripcion"; //echo $sactivo;
$qactivo = mysql_query($sactivo) or die ($sactivo.mysql_error());
$ractivo = mysql_num_rows($qactivo);
$ub=0;
$ubs=0;
$MONTO_TOTAL=0;
$MONTOs=0;
$Prueba=1;
if($ractivo!=0){
    
   for($i=0; $i<$ractivo; $i++){
       
        $factivo = mysql_fetch_array($qactivo);
	  
        $cod = '0'.substr($factivo['CodigoInterno'], 0, 1); //echo 'cod=  '.$cod;   //Cola
        $cod1 = substr($factivo['CodigoInterno'], 1, 2);  
        $cod2 = substr($factivo['CodigoInterno'], 3, 2); //echo 'cod2=  '.$cod2; /// Punta 
        $cod3 = substr($factivo['CodigoInterno'], 5);
	  
         
         
        if($factivo['Ubica']!=$ub){
            $ub= $factivo['Ubica'];
            if($ubs!=0){
                
                if ( $pdf->GetY() > 1) {
                    
                    # cuando es 1
                    if ( $pdf->GetY() < 65) {
                        for($ii=0; $ii<26;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                   
                    
                    $pdf->Row(array('Total ss', number_format($MONTOs,2,',','.')));
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                     $MONTOs=0;
                     
                       
                    }elseif ( $pdf->GetY() < 68) { #Cuando es 2
                        for($ii=0; $ii<24;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $MONTOs=0;
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                    }elseif ( $pdf->GetY() < 75) { #Cuando es 7
                        for($ii=0; $ii<24;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                    }elseif ( $pdf->GetY() < 90) { #Cuando es 7
                        for($ii=0; $ii<19;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                        $MONTOs=0;
                    }elseif ( $pdf->GetY() < 110) { #Cuando es 7
                        for($ii=0; $ii<16;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ss',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                        $MONTOs=0;
                    }elseif ( $pdf->GetY() < 140) { #Cuando es 7
                        for($ii=0; $ii<6;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                        $MONTOs=0;
                    }elseif ( $pdf->GetY() < 150) { #Cuando es 7
                        for($ii=0; $ii<3;$ii++){
                            $pdf->SetFillColor(255, 255, 255); 
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->SetWidths(array(16,16,16,30,130,30));
                            $pdf->SetAligns(array('C','C','C','C','L','R'));
                            $pdf->Row(array('', '', '', '', '',''));
                        }
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                        $MONTOs=0;
                    }elseif ( $pdf->GetY() > 155) { #Cuando es 7
                        
                        $pdf->SetFillColor(200, 200, 200); 
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetWidths(array(208,30));
                    $pdf->SetAligns(array('R','R'));
                    
                    $pdf->Row(array('Total ',  number_format($MONTOs,2,',','.')));
                    
                    $MONTOs = $MONTOs + $factivo['MontoLocal'];
                        $MONTOs=0;
                    }
                    
                    
                    
                    $pdf->AddPage();
                    
                    
                }
            }
            $pdf->Header(utf8_decode($factivo['Dependencia']), utf8_decode($factivo['DescpUbicacion']));
            $ubs++;
            $MONTOs=0;
        }
          
        
          
                
          if ( $pdf->GetY() > 154){
              
              
                $pdf->SetFillColor(200, 200, 200); 
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetWidths(array(208,30));
                $pdf->SetAligns(array('R','R'));
                $pdf->Row(array('Total ', number_format($MONTOs,2,',','.')));
                
                $MONTOs = $MONTOs + $factivo['MontoLocal'];
                $MONTOs=0;
                $pdf->AddPage();
                
                $pdf->Header(utf8_decode($factivo['Dependencia']), utf8_decode($factivo['DescpUbicacion']));
          }
            $CodDependencia = $factivo['CodDependencia'];
           
            $MONTO = number_format($factivo['MontoLocal'],2,',','.');
            
            
	  $pdf->SetFillColor(255, 255, 255); 
	  $pdf->SetFont('Arial', '', 9);
	  $pdf->SetWidths(array(16,16,16,30,130,30));
	  $pdf->SetAligns(array('C','C','C','C','L','R'));
	  $pdf->Row(array($cod, $cod1, $cod2, $cod3, utf8_decode($factivo['Descripcion']),$MONTO));
         
            
          
         $MONTOs = $MONTOs + $factivo['MontoLocal'];

          
          
   }
           
             
            $pdf->SetFillColor(200, 200, 200); 
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetWidths(array(208,30));
            $pdf->SetAligns(array('R','R'));
            $pdf->Row(array('Total ', number_format($MONTOs,2,',','.')));
   
}
   $scon03 = "select 
   					 CodPersona
			    from 
				     mastdependencias
				where     
					CodDependencia=(select ValorParam from mastparametros where ParametroClave='FIRMAINVENTARIODEP') and 
					CodOrganismo='".$factivo['CodOrganismo']."' ";
   $qcon03 = mysql_query($scon03) or die ($scon03.mysql_error());
   $fcon03 = mysql_fetch_array($qcon03);
   
 
   
    $MONTO_TOTAL = number_format($MONTO_TOTAL,2,',','.');
    
    $pdf->Ln(4);
    
    
$pdf->Output();
?>  
