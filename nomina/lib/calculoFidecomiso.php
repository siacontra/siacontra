<?php

class CalculoFidecomiso {
	
	public $dias;
	public $meses;
	public $anios;
	public $periodo; 
	public $hasta;
	public $fingreso;
	public $fecha_actual;
	
	public $begin ;
	public $iterador;
	public $end;
    public $tabla;
    public $sueldoBasico;

public  function inicializar (  $fecha_ingreso,  $dias, $mes , $anios, $CodPersona)
		
		{
			
		$this->begin = 		new DateTime($fecha_ingreso);
        $this->iterador =   new DateTime($fecha_ingreso);
        $this->end =        new DateTime($fecha_ingreso);
        
        $this->end = $this->end->modify( '+'.$dias.' day' );
		$this->end = $this->end->modify( '+'.$mes.' month' );
		$this->end = $this->end->modify( '+'.$anios.' year' );
		$this->tabla =  array();

}

public function calcularPeriodos ()
	{
$interval = new DateInterval('P1M');
$daterange = new DatePeriod($this->begin, $interval ,$this->end);


$inicio=$this->iterador->format("Y-m-d");
$this->iterador = $this->iterador->modify( '+1 month' );
$this->iterador = $this->iterador->modify( '-1 day' );
$fin =$this->iterador->format("Y-m-d");
$this->iterador = $this->iterador->modify( '+1 day' );	
$i=0;

$diasTrimestres=5;
$j=0;


foreach($daterange as $date){
	
//echo $inicio." al ".$fin. "\n";


$this->tabla[$i]['inicio'] = $inicio;
$this->tabla[$i]['fin'] = $fin;
$this->tabla[$i]['dias'] = 5;
$this->tabla[$i]['diasTrimestres'] = $diasTrimestres;
$i++;
$inicio=$this->iterador->format("Y-m-d");
$this->iterador = $this->iterador->modify( '+1 month' );
$this->iterador = $this->iterador->modify( '-1 day' );
$fin =$this->iterador->format("Y-m-d");
$this->iterador = $this->iterador->modify( '+1 day' );

    
}


}



public function setSueldo($sueldo)
{
	
for($i=0; $i< count ($this->tabla); $i++)

		{
		$this->tabla[$i]['SueldoNormal'] = $sueldo;
			
		}

	
} 




public function getTabla()
{
	
return $this->tabla;	

	
} 


}//clase



//$fingreso = '2013-08-16';
//$dias=120;
//$meses=0;
//$anios=0;

//$obj =  new CalculoFidecomiso;
//$obj->inicializar( $fingreso,  $dias, $meses , $anios);
//$obj->calcularPeriodos();

//$obj->getTabla();
//~ 
//~ 
//~ $iterador = new DateTime( '2010-01-16' );
//~ $end = new DateTime( '2010-01-16' );
//~ 
//~ $end = $end->modify( '+0 day' );
//~ $end = $end->modify( '+0 month' );
//~ $end = $end->modify( '+0 year' );


?>




