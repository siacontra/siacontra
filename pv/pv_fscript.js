/// ---------------------------------------------------------------------
/// ACTIVA Y DESACTIVA CAMPOS RP_EJECUCIONPRESUPUESTARIA
function enabledPeriodoReporteEjecucion(form){
  if(form.chkPeriodo.checked) form.fPeriodo.disabled = false;
  else{ form.fPeriodo.disabled=true; form.fPeriodo.value= '';}
}
function enabledPresupuestoReporteEjecucion(form){
  if(form.chkPresupuesto.checked) form.fPresupuesto.disabled = false;
  else{ form.fPresupuesto.disabled = true; form.fPresupuesto.value='';}
}