<?php
	require 'php/alumno.php';
	require 'archivo.php';

	$accion = $_POST['Gestion'];
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$legajo = $_POST['legajo'];
	$foto = $_FILES['archivo'];

	switch ($accion) {
		case 'Ingreso':
			$arrayRta = Archivo::Subir($nombre,$apellido,$legajo);
			if($arrayRta['Exito']){
				$unAlumno = new Alumnos($nombre,$apellido,$legajo,$arrayRta['PathTemporal']); 
				if($unAlumno->Guardar($nombre,$apellido,$legajo)){
					$requireModificacion = true;
				}
			}
			break;
			
		case 'Egreso':

			$unAlumno = Alumnos::TraerUnAlumno($legajo);
			if(isset($unAlumno))
				if(Alumnos::Borrar($unAlumno))
					$requireModificacion = true;
			break;
		case 'Modificacion':
			$unAlumno = Alumnos::TraerUnAlumno($legajo);
			$unAlumno->nombre = $nombre;
			$unAlumno->apellido = $apellido;
			if(isset($unAlumno))
				if(Alumnos::Modificar($unAlumno))
					$requireModificacion = true;
			# code...
			break;

	}
	if($requireModificacion){
		Alumnos::CrearTablaAlumnos();
	}


	header("location:index.php");
?>
