<?php
// Recibe como parámetros: nombre, descripcion, xmin, ymin, xmax, ymax, vertices(lat, lng)

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");


			$con = mysql_connect("mysql.webcindario.com","dondestoy","moreno1");
			if (!$con)
			  {
			  die('Imposible conectar a la base de datos: ' . mysql_error());
			  }

			mysql_select_db("dondestoy", $con);
			
			mysql_query("FLUSH TABLES");

			mysql_query("INSERT INTO zonas (nombre, descripcion, xmin, ymin, xmax, ymax) VALUES ('$nombre', '$descripcion', $xmin, $ymin, $xmax, $ymax)");

			$codigo_zona = mysql_insert_id();   // Devuelve el último código usado como autoincrement en el último INSERT.


			$array_vertices = explode("|", $vertices);

			echo "Nombre: $nombre<BR>";
			echo "Descripcion: $descripcion<BR>";
			echo "Xmin: $xmin<BR>";
			echo "Ymin: $ymin<BR>";
			echo "Xmax: $xmax<BR>";
			echo "Ymax: $ymax<BR><BR>";
			
			$indicador = 0;
			$num_vertices = 0;
			
			foreach ($array_vertices as $coordenada)
			{
				array_shift($array_vertices);

				if ($indicador == 0)
				{
					$latitud = $coordenada;
					$num_vertices++;
					$indicador = 1;
				}
				else
				{
					$longitud = $coordenada;
					$indicador = 0;
// echo "Punto " . $num_vertices ." ($latitud, $longitud)<BR>";
					$vertices2[$num_vertices]['lat'] = $latitud;
					$vertices2[$num_vertices]['lng'] = $longitud;
				}
			}
			
			for ($i=1; $i <= $num_vertices; $i++)
			{
				mysql_query("INSERT INTO vertices_zonas (codigo_zona, orden, lat, lng) VALUES ($codigo_zona,$i," . $vertices2[$i]['lat'] . "," . $vertices2[$i]['lng'] . ")");
//					echo("INSERT INTO vertices_zonas (codigo_zona, orden, lat, lng) VALUES ('$codigo_zona','$i'," . $vertices2[$i]['lat'] . "," . $vertices2[$i]['lng'] . ")<BR>");
			}
			mysql_close($con);

?>
