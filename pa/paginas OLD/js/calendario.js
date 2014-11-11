// JavaScript Document
		/* create an array of days which need to be disabled */
			var disabledDays = ["2-23-2011","2-24-2011","3-27-2011","3-28-2011","3-3-2011","3-17-2011","4-2-2011","4-3-2011","4-4-2011","4-5-2011",
"5-3-2011","6-26-2011","6-7-2011","8-8-2011","8-11-2011","8-9-2011","7-21-2011","7-23-2011","7-30-2011","7-6-2011",
"7-23-2011","8-24-2011","9-27-2011","11-28-2011","9-3-2011","9-17-2011","10-2-2011","10-3-2011","11-4-2011","11-5-2011","2-23-2010","2-24-2010","3-27-2010","3-28-2010","3-3-2010","3-17-2010","4-2-2010","4-3-2010","4-4-2010","4-5-2010",
"5-3-2010","6-26-2010","6-7-2010","8-8-2010","8-11-2010","8-9-2010","7-21-2010","7-23-2010","7-30-2010","7-6-2010",
"7-23-2010","8-24-2010","9-27-2010","11-28-2010","9-3-2010","9-17-2010","10-2-2010","10-3-2010","11-4-2010","11-5-2010","2-23-2012","2-24-2012","3-27-2012","3-28-2012","3-3-2012","3-17-2012","4-2-2012","4-3-2012","4-4-2012","4-5-2012",
"5-3-2012","6-26-2012","6-7-2012","8-8-2012","8-11-2012","8-9-2012","7-21-2012","7-23-2012","7-30-2012","7-6-2012",
"7-23-2012","8-24-2012","9-27-2012","11-28-2012","9-3-2012","9-17-2012","10-2-2012","10-3-2012","11-4-2012","11-5-2012"];	

			/* utility functions */
			function nationalDays(date) {
				var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
				console.log('Checking (raw): ' + m + '-' + d + '-' + y);
				for (i = 0; i < disabledDays.length; i++) {
					if(ArrayContains(disabledDays,(m+1) + '-' + d + '-' + y) || new Date() > date) {
						//console.log('bad:  ' + (m+1) + '-' + d + '-' + y + ' / ' + disabledDays[i]);
						return [false];
					}
				}
				console.log('good:  ' + (m+1) + '-' + d + '-' + y);
				return [true];
			}
			function noWeekendsOrHolidays(date) {
				var noWeekend = jQuery.datepicker.noWeekends(date);
				return noWeekend[0] ? nationalDays(date) : noWeekend;
			}

			/* taken from mootools */
			function ArrayIndexOf(array,item,from){
				var len = array.length;
				for (var i = (from < 0) ? Math.max(0, len + from) : from || 0; i < len; i++){
					if (array[i] === item) return i;
				}
				return -1;
			}
			/* taken from mootools */
			function ArrayContains(array,item,from){
				return ArrayIndexOf(array,item,from) != -1;
			}


			/* create datepicker */
			jQuery(document).ready(function() {
			
 					jQuery(function($){
						$.datepicker.regional['es'] = {
									clearText: 'Limpiar', clearStatus: '',
									closeText: 'Cerrar', closeStatus: '',
									prevText: '&#x3c;Ant', prevStatus: '',
									prevBigText: '&#x3c;&#x3c;', prevBigStatus: '',
									nextText: 'Sig&#x3e;', nextStatus: '',
									nextBigText: '&#x3e;&#x3e;', nextBigStatus: '',
									currentText: 'Hoy', currentStatus: '',
									monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
									'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
									monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
									'Jul','Ago','Sep','Oct','Nov','Dic'],
									monthStatus: '', yearStatus: '',
									weekHeader: 'Sm', weekStatus: '',
									dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
									dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
									dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
									dayStatus: 'DD', dateStatus: 'D, M d',
									dateFormat: 'dd/mm/yy', firstDay: 1,
									initStatus: '', isRTL: false,
									showMonthAfterYear: false, yearSuffix: ''
									};
						$.datepicker.setDefaults($.datepicker.regional['es']);
					});
					
				 	jQuery('#date').datepicker({
						minDate: new Date(2000, 0, 1),
						maxDate: new Date(2015, 5, 31),
						dateFormat: 'd-m-yy',
						constrainInput: true,
						beforeShowDay: noWeekendsOrHolidays 
					});
				
			});
		
