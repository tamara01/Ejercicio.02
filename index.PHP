<?php
require "php/alumno.php";
$path = "gestion.php";

// estacionamiento::CrearJSAutocompletar();
// estacionamiento::CrearTablaFacturado();
Alumnos::CrearTablaAlumnos();

?>
<!doctype html>
<html lang="en-US">
<head>

  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <title>Axel Cores</title>

  <link rel="stylesheet" type="text/css" href="css/estilo.css">
  <link rel="stylesheet" type="text/css" href="css/animacion.css">
  <link rel="stylesheet" type="text/css" media="all" href="css/style.css">
  
  <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
  <script type="text/javascript" src="js/funcionAutoCompletar.js"></script>
  <!--script type="text/javascript" src="js/currency-autocomplete.js"></script-->
</head>
	<body>
    <div class="CajaUno animated bounceInRight">
        <form action="<?php echo $path; ?>" method="post" enctype="multipart/form-data">
        <div>
          <input type="text" class= "CajaDatos" name="nombre" placeholder="Nombre"/> 
          <input type="text" class= "CajaDatos" name="apellido" placeholder="Apellido"/> 
          <input type="number" class= "CajaDatos" name="legajo" placeholder="Legajo"/><br><br>
          <input type="file" class= "CajaDatos" name="archivo" class="MiBotonUTN" />
        </div>
        <div>
          <input type="submit" class="MiBotonAlta" value="Ingreso" name="Gestion"/>  
          <input type="submit" class="MiBotonModificacion" value="Modificacion" name="Gestion"/> 
          <input type="submit" class="MiBotonBaja" value="Egreso" name="Gestion"/>
        </div>
        </form>
        <div id="outputbox">
          <p id="outputcontent">
          <?php 

           ?>
          </p>
        </div>
    </div>
    
      <nav class="CajaEnunciado animated bounceInLeft">
        <h2>Alumnos:</h2>
        <?php 
          include("archivos/tablaalumnos.php");
        ?>
      </div>
	</body>
</html>