// JavaScript Document
function evento(){
 var select = document.getElementById("0");
  var municipio = document.getElementById(select.selectedIndex);
  var ciudad = document.getElementById("21");
  switch (parseInt(municipio.id)){
    case 0:
      ciudad.options.length=0; 
      alert("Elija la Opcion Correcta");
    break;
 
 
    case 1:
       	ciudad.disabled=false;
        ciudad.options.length=0;  // BORRAMOS TODOS LOS OPTION'S !!!!!!
        variable = new Option("Santa Isabel","Santa Isabel");
        ciudad.options[0]=variable;
        variable = new Option("Araguaney","Araguaney");
        ciudad.options[1]=variable;
        variable = new Option("El Jaguito","El Jaguito");
        ciudad.options[2]=variable;
        variable = new Option("La Esperanza","La Esperanza ");
        ciudad.options[3]=variable;  
		    
    break;
 
    case 2:
        ciudad.disabled=false;
        ciudad.options.length=0;
        variable = new Option("Bocono","Bocono");
        ciudad.options[0]=variable;
        variable = new Option("El Carmen","El Carmen");
        ciudad.options[1]=variable;
        variable = new Option("Mosquey","Mosquey");
        ciudad.options[2]=variable;
		variable = new Option("Ayacucho","Ayacucho");
        ciudad.options[3]=variable;
		variable = new Option("Burbusay","Burbusay");
        ciudad.options[4]=variable; 
		variable = new Option("Mosquey","Mosquey");
        ciudad.options[5]=variable;
		variable = new Option("General Rivas","General Rivas");
        ciudad.options[6]=variable;
		variable = new Option("Guaramacal","Guaramacal");
        ciudad.options[7]=variable;
		variable = new Option("Vega de Guaramacal","Vega de Guaramacal");
        ciudad.options[8]=variable;
		variable = new Option("Monseñor Jauregui","Monseñor Jauregui");
        ciudad.options[9]=variable;
		variable = new Option("Rafael Rangel","Rafael Rangel");
        ciudad.options[10]=variable;
		variable = new Option("San Miguel","San Miguel");
        ciudad.options[11]=variable;
		variable = new Option("San Jose","San Jose");
        ciudad.options[12]=variable;         
    break;
 
    case 3:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Sabana Grande","Sabana Grande");
      ciudad.options[0]=variable;
	  variable = new Option("Cheregue","Cheregue");
      ciudad.options[1]=variable;
	  variable = new Option("Granados","Granados");
      ciudad.options[1]=variable;
    break;
 
    case 4:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Chejende","Chejende");
      ciudad.options[0]=variable;
      variable = new Option("Arnoldo Gabaldon","Arnoldo Gabaldon");
      ciudad.options[1]=variable;
      variable = new Option("Bolivia","Bolivia");
      ciudad.options[2]=variable;
      variable = new Option("Carillo","Carillo");
      ciudad.options[3]=variable;
      variable = new Option("Cegarra","Cegarra");
      ciudad.options[4]=variable;
	  variable = new Option("Manuel Salvador Ulloa","Manuel Salvador Ulloa");
      ciudad.options[5]=variable;
	  variable = new Option("San Jose","San Jose");
      ciudad.options[6]=variable;      
    break; 
	
	case 5:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Carache","Carache");
      ciudad.options[0]=variable;
      variable = new Option("Cuicas","Cuicas");
      ciudad.options[1]=variable;
      variable = new Option("La Concepcion","La Concepcion");
      ciudad.options[2]=variable;
      variable = new Option("Panamericana","Panamericana");
      ciudad.options[3]=variable;
      variable = new Option("Santa Cruz","Santa Cruz");
      ciudad.options[4]=variable;
    break; 
	
	case 6:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Escuque","Escuque");
      ciudad.options[0]=variable;
      variable = new Option("La Union","La Union");
      ciudad.options[1]=variable;
      variable = new Option("Sabana Libre","Sabana Libre");
      ciudad.options[2]=variable;
      variable = new Option("Santa Rita","Santa Rita");
      ciudad.options[3]=variable;
    break; 
	
	case 7:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("El Socorro","El Socorro");
      ciudad.options[0]=variable;
      variable = new Option("Antonio Jose de Sucre","Antonio Jose de Sucre");
      ciudad.options[1]=variable;
      variable = new Option("Los Caprichos","Los Caprichos");
      ciudad.options[2]=variable;
    break; 
	
	case 8:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Campo Elias","Campo Elias");
      ciudad.options[0]=variable;
      variable = new Option("Arnaldo Gabaldon","Arnaldo Gabaldon");
      ciudad.options[1]=variable;
    break; 
 
 	case 9:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Santa Apolonia","Santa Apolonia");
      ciudad.options[0]=variable;
      variable = new Option("El Progreso","El Progreso");
      ciudad.options[1]=variable;
      variable = new Option("La Ceiba","La Ceiba");
      ciudad.options[2]=variable;
      variable = new Option("Tres de Febrero","Tres de Febrero");
      ciudad.options[3]=variable;
    break; 
	
	case 10:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("El Dividive","El Dividive");
      ciudad.options[0]=variable;
      variable = new Option("Agua Santa","Agua Santa");
      ciudad.options[1]=variable;
      variable = new Option("Agua Caliente","Agua Caliente");
      ciudad.options[2]=variable;
      variable = new Option("El Cenizo","El Cenizo");
      ciudad.options[3]=variable;
	  variable = new Option("Valerita","Valerita");
      ciudad.options[4]=variable;
    break; 
	
	case 11:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Monte Carmelo","Monte Carmelo");
      ciudad.options[0]=variable;
      variable = new Option("Buena Vista","Buena Vista");
      ciudad.options[1]=variable;
      variable = new Option("Santa Maria del Horcon","Santa Maria del Horcon");
      ciudad.options[2]=variable;
    break; 
	
	case 12:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Motatan","Motatan");
      ciudad.options[0]=variable;
      variable = new Option("El Baño","El Baño");
      ciudad.options[1]=variable;
      variable = new Option("Jalisco","Jalisco");
      ciudad.options[2]=variable;
    break; 
	
	case 13:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Pampan","Pampan");
      ciudad.options[0]=variable;
      variable = new Option("Flor de Patria","Flor de Patria");
      ciudad.options[1]=variable;
      variable = new Option("La Paz","La Paz");
      ciudad.options[2]=variable;
      variable = new Option("Santa Ana","Santa Ana");
      ciudad.options[3]=variable;
    break; 
	
	case 14:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Pampanito","Pampanito");
      ciudad.options[0]=variable;
      variable = new Option("La Concepcion","La Concepcion");
      ciudad.options[1]=variable;
      variable = new Option("Pampanito II","Pampanito II");
      ciudad.options[2]=variable;
      
    break; 
	
	case 15:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Betijoque","Betijoque");
      ciudad.options[0]=variable;
      variable = new Option("La Pueblita","La Pueblita");
      ciudad.options[1]=variable;
      variable = new Option("Los Cedros","Los Cedros");
      ciudad.options[2]=variable;
      variable = new Option("Jose Gregorio Hernandez","Jose Gregorio Hernandez");
      ciudad.options[3]=variable;
    break; 
	
	case 16:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Carvajal","Carvajal");
      ciudad.options[0]=variable;
      variable = new Option("Antonio Nicolas Briceño","Antonio Nicolas Briceño");
      ciudad.options[1]=variable;
      variable = new Option("Campo Alegre","Campo Alegre");
      ciudad.options[2]=variable;
      variable = new Option("Jose Leonardo Suarez","Jose Leonardo Suarez");
      ciudad.options[3]=variable;
    break; 
	
	case 17:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Sabana de Mendoza","Sabana de Mendoza");
      ciudad.options[0]=variable;
      variable = new Option("El Paraiso","El Paraiso");
      ciudad.options[1]=variable;
      variable = new Option("Junin","Junin");
      ciudad.options[2]=variable;
      variable = new Option("Valmore Rodriguez","Valmore Rodriguez");
      ciudad.options[3]=variable;
    break; 
	
	case 18:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Andres Linares","Andres Linares");
      ciudad.options[0]=variable;
      variable = new Option("Chiquinquira","Chiquinquira");
      ciudad.options[1]=variable;
      variable = new Option("Cristobal Mendoza","Cristobal Mendoza");
      ciudad.options[2]=variable;
      variable = new Option("Cruz Carillo","Cruz Carillo");
      ciudad.options[3]=variable;
      variable = new Option("Matriz","Matriz");
      ciudad.options[4]=variable;
	  variable = new Option("Monseñor Carrillo","Monseñor Carrillo");
      ciudad.options[5]=variable;
	  variable = new Option("Tres Esquinas","Tres Esquinas");
      ciudad.options[6]=variable;      
    break; 
	
	case 19:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("La Quebrada","La Quebrada");
      ciudad.options[0]=variable;
      variable = new Option("Cabimbu","Cabimbu");
      ciudad.options[1]=variable;
      variable = new Option("Jajo","Jajo");
      ciudad.options[2]=variable;
      variable = new Option("La Mesa","La Mesa");
      ciudad.options[3]=variable;
      variable = new Option("Santiago","Santiago");
      ciudad.options[4]=variable;
	  variable = new Option("Tuñame","Tuñame");
      ciudad.options[5]=variable;      
    break; 
	
	case 20:
      ciudad.disabled=false;
      ciudad.options.length=0;
      variable = new Option("Juan Ignacio Montilla","Juan Ignacio Montilla");
      ciudad.options[0]=variable;
      variable = new Option("La Beatriz","La Beatriz");
      ciudad.options[1]=variable;
      variable = new Option("Mercedes Diaz","Mercedes Diaz");
      ciudad.options[2]=variable;
      variable = new Option("San Luis","San Luis");
      ciudad.options[3]=variable;
      variable = new Option("La Puerta","La Puerta");
      ciudad.options[4]=variable;
	  variable = new Option("Mendoza","Mendoza");
      ciudad.options[5]=variable;      
    break; 
	
    default:
    break;
  }
}