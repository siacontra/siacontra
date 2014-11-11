UPDATE pv_reformulacionppto 
         SET Estado='AP',
		     UltimoUsuario='EJBOLIVAR',
			 UltimaFechaModif='2012-06-12 17:37:55' 
	   WHERE CodPresupuesto='0003' AND 
	         Organismo='0001'  AND 
			 CodRef='0003';

SELECT * FROM 
                    pv_reformulacionpptodet 
			  WHERE 
			        CodPresupuesto='0003' AND 
	                Organismo='0001'  AND 
			        CodRef='0003';

SELECT * FROM 
                    pv_reformulacionpptodet 
			  WHERE 
			        CodPresupuesto='0003' AND 
	                Organismo='0001'  AND 
			        CodRef='0003';

