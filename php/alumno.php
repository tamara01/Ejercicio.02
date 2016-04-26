<?php 

	class Alumnos
	{
		public $nombre;
		public $apellido;
		public $legajo;
		public $foto;

		function __construct($p_nom,$p_ap,$p_leg,$p_fot)
		{
			$this->nombre = $p_nom;
			$this->apellido = $p_ap;
			$this->legajo = $p_leg;
			$this->foto = $p_fot;
		}

		function Guardar(){
			$archivo=fopen("archivos/alumnos.txt", "a");//escribe y mantiene la informacion existente		
			$renglon= $this->legajo . "=>" . $this->nombre . "=>" . $this->apellido . "=>" . $this->foto . "\n";
			fwrite($archivo, $renglon); 		 
			fclose($archivo);
			return true;
		}

		public static function Modificar($p_alumno)
		{
			$array = Alumnos::TraerTodos();
			$arrayIN = array();
			foreach ($array as $alumnito) {
				if(trim($alumnito[0]) == $p_alumno->legajo){
					$arrayIN[] = ((array)$p_alumno);
				}else{
					$arrayIN[] = $alumnito;
				}
			}

			Alumnos::GuardarListado($arrayIN);
			return true;
		}

		private static function GuardarListado($listado)
		{
			$archivo=fopen("archivos/alumnos.txt", "w");
			foreach ($listado as $alumnito) {
				if(isset($alumnito[0])){
					$renglon = $alumnito[0] . "=>" . $alumnito[1] . "=>" . $alumnito[2] . "=>" . $alumnito[3] . "\n";
				}else{
					$renglon = $alumnito["legajo"] . "=>" . $alumnito["nombre"] . "=>" . $alumnito["apellido"] . "=>" . $alumnito["foto"] . "\n";	
				}


				
				fwrite($archivo,$renglon);

			}	
			fclose($archivo);
		}



		public static function Borrar($p_alumno)
		{
			
			$array = Alumnos::TraerTodos();
			$arrayIN = array();
			
			foreach ($array as $alumnito) {
				if($alumnito[0] != $p_alumno->legajo)
					$arrayIN[] = $alumnito;
				else{
					Archivo::Borrar($p_alumno->foto);
				}
			}

			Alumnos::GuardarListado($arrayIN);
			return true;
			
		}

		public static function CrearTablaAlumnos()
		{
			if(file_exists("archivos/alumnos.txt"))
			{
				$cadena=" <table><th>Legajo</th><th>Nombre</th><th>Apellido</th><th>Foto</th>";

				$archivo=fopen("archivos/alumnos.txt", "r");

				while(!feof($archivo)){
					$archAux=fgets($archivo);
					$alumno=explode("=>", $archAux);
					
					if(trim($alumno[0]) != ""){
						$cadena =$cadena."<tr><td>".$alumno[0]."</td><td>".$alumno[1] ."</td><td>".$alumno[2] ."</td><td><img src=".$alumno[3]." height=100px with=100px/></td></tr>" ; 
					}
				}
				$cadena =$cadena." </table>";
				fclose($archivo);

				$archivo=fopen("archivos/tablaalumnos.php", "w");
				fwrite($archivo, $cadena);

			}
			else{
				$cadena= "No hay autos en el garage";
				$archivo=fopen("archivos/tablaalumnos.php", "w");
				fwrite($archivo, $cadena);
			}
		}

		public static function TraerUnAlumno($legajo)
		{
			$array = Alumnos::TraerTodos();
			foreach ($array as $alumnito) {
				if(trim(trim($alumnito[0])) == trim($legajo))
					return new Alumnos($alumnito[1],$alumnito[2],$alumnito[0],$alumnito[3]);
			}
		}

		public static function TraerTodos()
		{
			$archivo = fopen("archivos/alumnos.txt", "r");
			$listaAlumnos = array();

			while(!feof($archivo))
			{
				$renglon = fgets($archivo);
				$renglon = trim($renglon);
				if($renglon != ""){
					$alumno = explode("=>", $renglon);
					$listaAlumnos[] = $alumno;
				}
			}
			return $listaAlumnos;
		}

		
	}
 ?>