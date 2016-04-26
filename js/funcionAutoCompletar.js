$(function(){
		var patentes = [ 

		 {value: "aaa111" , data: " 2016-04-16 21:43:31 " }, 
 {value: "aaa222" , data: " 2016-04-16 21:45:06 " }, 
 {value: "ttt003" , data: " 2016-04-18 16:52:45 " }, 


		];

		// setup autocomplete function pulling from patentes[] array
		$('#autocomplete').autocomplete({
		lookup: patentes,
		onSelect: function (suggestion) {
		var thehtml = '<strong>patente: </strong> ' + suggestion.value + ' <br> <strong>ingreso: </strong> ' + suggestion.data;
		$('#outputcontent').html(thehtml);
		$('#botonIngreso').css('display','none');
			console.log('aca llego');
		}
		});


		});