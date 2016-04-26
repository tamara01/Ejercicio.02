<?php
session_start();
$_SESSION['mensaje'];
class estacionamiento
{
	public static function Guardar($patente,$foto)
	{

		$archivo=fopen("archivos/estacionados.txt", "a");//escribe y mantiene la informacion existente		
		$ahora=date("Y-m-d H:i:s"); 
		$renglon=$patente . "=>" . $ahora . "=>" . $foto . "\n";
		fwrite($archivo, $renglon); 		 
		fclose($archivo);
		$_SESSION['mensaje'] = "Se ha guardado el auto $patente en el estacionamiento";
	}


	public static function GuardarListado($listado)
	{

		$archivo=fopen("archivos/estacionados.txt", "w");//escribe y mantiene la informacion existente	
		foreach ($listado as $autitos) {
			fwrite($archivo, $autitos[0] . "=>" . $autitos[1] . "=>" . $autitos[2]);
		}	
		fclose($archivo);
	}

	public static function Sacar($patente)
	{
		$listaAutosArchivo = estacionamiento::Leer();
		$listaAutosGuardados = array();
		$esta = false;
		$patenteSacar;
		$fotoSacar;
		$estadia;
		foreach ($listaAutosArchivo as $autito) {
			if(trim($autito[0]) != trim($patente)){
				$listaAutosGuardados[] = $autito;
			}
			else
			{
				$esta = true;
				$ahora = date("Y-m-d H:i:s");
				$patenteSacar = $autito[0];
				$fotoSacar = $autito[2];
				$estadia = strtotime($ahora) - strtotime($autito[1]);
			}
		}
		if($esta)
		{
			$estadia = $estadia ;
			$importe = $estadia * 60;
			
			estacionamiento::GuardarFactura($patenteSacar,$importe,$fotoSacar);
			$_SESSION['mensaje'] = "El auto $patente ha estado $estadia segundos y debe abonar $importe";
		}
		estacionamiento::GuardarListado($listaAutosGuardados);

	}


	public static function GuardarFactura($patente,$estadia,$foto)
	{

		$archivo=fopen("archivos/facturacion.txt", "a");//escribe y mantiene la informacion existente		
		 
		$renglon=$patente."=> $".$estadia."=>".$foto."\n";
		fwrite($archivo, $renglon); 		 
		fclose($archivo);
	}

	public static function Leer()
	{
		$ListaDeAutosLeida = array();
		$archivo=fopen("archivos/estacionados.txt", "r");//escribe y mantiene la informacion existente

		while(!feof($archivo))
		{
			$renglon=fgets($archivo);
			$auto=explode("=>", $renglon);
			$auto[0]=trim($auto[0]);
			if($auto[0]!="")
			$ListaDeAutosLeida[]=$auto;
		}

		fclose($archivo);
		return $ListaDeAutosLeida;
	}


	public static function CrearTablaEstacionados()
	{
		if(file_exists("archivos/estacionados.txt"))
		{
			$cadena=" <table><th>Patente</th><th>Llegada</th><th>Foto</th>";

			$archivo=fopen("archivos/estacionados.txt", "r");

			while(!feof($archivo)){
				$archAux=fgets($archivo);
				$auto=explode("=>", $archAux);
				$auto[0]=trim($auto[0]);
				if($auto[0]!="")
					$cadena =$cadena."<tr> <td> ".$auto[0]."</td> <td>  ".$auto[1] ."</td><td><img src=".$auto[2]." height=100px with=100px/></td></tr>" ; 
			}
				$cadena =$cadena." </table>";
				fclose($archivo);

				$archivo=fopen("archivos/tablaestacionados.php", "w");
				fwrite($archivo, $cadena);

		}
		else{
			$cadena= "No hay autos en el garage";

			$archivo=fopen("archivos/tablaestacionados.php", "w");
			fwrite($archivo, $cadena);
		}

	}

	public static function CrearJSAutocompletar()
	{		
		$cadena="";

		$archivo=fopen("archivos/estacionados.txt", "r");

		while(!feof($archivo))
		{
			$archAux=fgets($archivo);
			$auto=explode("=>", $archAux);
			$auto[0]=trim($auto[0]);

			if($auto[0]!="")
			{
				$auto[1]=trim($auto[1]);
				$cadena=$cadena." {value: \"".$auto[0]."\" , data: \" ".$auto[1]." \" }, \n"; 
			}
		}
		fclose($archivo);

		$archivoJS="$(function(){
		var patentes = [ \n\r
		". $cadena."

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


		});";

		$archivo=fopen("js/funcionAutoCompletar.js", "w");
		fwrite($archivo, $archivoJS);
	}

	public static function GuardarArchivo($foto,$patente)
	{
		
		$extension = pathinfo($foto["archivo"]["name"], PATHINFO_EXTENSION);
		if($foto['archivo']['size'] > 1024000){
			$tamaño = $foto['archivo']['size'];
			$_SESSION['mensaje'] = "La foto supera el tamaño permitido ($tamaño/1024)";
			return;
		}
			
		if(file_exists("Fotitos/$patente.".$extension)){
			rename("Fotitos/$patente.". $extension,"OldFotitos/". date("Y-m-d") . "_$patente.".$extension);
		}
		
		if((strtoupper($extension) != 'JPEG') && (strtoupper($extension) != 'JPG') && (strtoupper($extension) != 'PNG') && (strtoupper($extension) != ' ')){
			$_SESSION['mensaje'] = "Formato no valido ($extension) ";
			return;
		}

		move_uploaded_file($foto['archivo']['tmp_name'] , "Fotitos/$patente.$extension");
		estacionamiento::Guardar($patente,"Fotitos/$patente.$extension");
	}

	public static function CrearTablaFacturado()
	{
		if(file_exists("archivos/facturacion.txt"))
		{
			$cadena=" <table><th>Patente</th><th>Importe</th><th>Foto</th>";

			$archivo=fopen("archivos/facturacion.txt", "r");

			while(!feof($archivo))
			{
				$archAux=fgets($archivo);
				//http://www.w3schools.com/php/func_filesystem_fgets.asp
				$auto=explode("=>", $archAux);
				//http://www.w3schools.com/php/func_string_explode.asp
				$auto[0]=trim($auto[0]);
				if($auto[0]!="")
					$cadena =$cadena."<tr> <td> ".$auto[0]."</td> <td>  ".$auto[1] ."</td><td><img src=".$auto[2]." height=100px with=100px/></td> </tr>" ; 
				}

				$cadena =$cadena." </table>";
				fclose($archivo);

				$archivo=fopen("archivos/tablaFacturacion.php", "w");
				fwrite($archivo, $cadena);
			}
		else{
			$cadena= "no hay facturación";

			$archivo=fopen("archivos/tablaFacturacion.php", "w");
			fwrite($archivo, $cadena);
		}
	}

}


?>