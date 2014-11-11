<? //	MUESTRO LA TABLA
       $montoP=$field[MontoAprobado];
	   $montoP=number_format($montoP,2,',','.');
	echo "
    <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPresupuesto']."'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$field['PreparadoPor']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
 echo"<td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>$fInicio</td>
	  <td align='center'>$fFin</td>
	  <td align='center'>$montoP</td>
	  <td align='center'>$estado</td>
    </tr>";
?>