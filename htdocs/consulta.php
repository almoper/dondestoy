<?php

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

			$con = mysql_connect("localhost","root","root");
			if (!$con)
			  {
			  die('Imposible conectar a la base de datos: ' . mysql_error());
			  }
			
			mysql_select_db("test", $con);
			
			mysql_query("FLUSH TABLES");
			
			$result = mysql_query("SELECT * FROM localizacion where fecha = (select max(fecha) from localizacion)");
			
			$row = mysql_fetch_array($result);
			
				//echo "<SCRIPT LANGUAGE='JavaScript'>";
			  //echo "cx = " . $row['x'] . ";";
			  //echo "cy = " . $row['y'] . ";";
			  //echo "</SCRIPT>";

				echo $row['y'] . ";" . $row['x']  . ";" . $row['velocidad']  . ";" . $row['direccion'];
			mysql_close($con);

?>