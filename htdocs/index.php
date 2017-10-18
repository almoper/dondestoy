<HTML>
<HEAD>
	<TITLE>Localización de terminal móvil</TITLE>

<!-- Map loader -->
  <script src="http://maps.google.es/maps?file=api&amp;v=2&amp;key=ABQIAAAAAVTKxI3HxxSgPlyvdbcxkhTbGTFVr2ORtenxJALOqERv93-W1hTUxkJ97or1-_Ndw2gLYz0COzSfvQ" type="text/javascript"></script>
  
  <script src="scripts/mathapi.js" type="text/javascript"></script>
  <script src="scripts/geoapi.js" type="text/javascript"></script>

 	<SCRIPT LANGUAGE="JavaScript">

    //<![CDATA[

	var map, cx, cy, marker, vel, dir;
	var pc1, pc2, pc3;
	var primera_vez;
	
	var num_zonas;
	var zonas = new Array();

	var poligonos = new Array(); 
	var desc_poligonos = new Array();
	var poligonos_activos = new Array();

	cy = 40.4142;
	cx = -3.6836;

	function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function MostrarConsulta(datos){

	var direccion1, Orientacion;
	var i;
	
//	if (primera_vez){
		divResultado = document.getElementById('coordenadas');
		ajax=objetoAjax();
		ajax.open("GET", datos, true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
	//			divResultado.innerHTML = ajax.responseText
				
				pc1 = ajax.responseText.indexOf(';');
				pc2 = ajax.responseText.indexOf(';', pc1 + 1);
				pc3 = ajax.responseText.indexOf(';', pc2 + 1);

				cy = ajax.responseText.substring(0, pc1 - 1);
				cx = ajax.responseText.substring(pc1 + 1, pc2 - 1);
				vel = ajax.responseText.substring(pc2 + 1, pc3 - 1);
				direccion1 = ajax.responseText.substring(pc3 + 1);

                                                if (direccion1 < 22.5 || direccion1 >= 337.5)
                                                {
                                                                Orientacion = "N";
                                                } else if (direccion1 >= 22.5 && direccion1 < 67.5)
                                                {
                                                                Orientacion = "NE";
                                                } else if (direccion1 >= 67.5 && direccion1 < 112.5)
                                                {
                                                                Orientacion = "E";
                                                }
                                                else if (direccion1 >= 112.5 && direccion1 < 157.5)
                                                {
                                                                Orientacion = "SE";
                                                }
                                                else if (direccion1 >= 157.5 && direccion1 < 202.5)
                                                {
                                                                Orientacion = "S";
                                                }
                                                else if (direccion1 >= 202.5 && direccion1 < 247.5)
                                                {
                                                                Orientacion = "SW";
                                                }
                                                else if (direccion1 >= 247.5 && direccion1 < 292.5)
                                                {
                                                                Orientacion = "W";
                                                }
                                                else if (direccion1 >= 292.5 && direccion1 < 337.5)
                                                {
                                                                Orientacion = "NW";
                                                }

				dir = direccion1 + "º [" +  Orientacion + "]";
			
				marker.setLatLng(new GLatLng(cy, cx));
				
				// Comprobar si la posición actual está dentro de algún polígono.

				estoy_en_zona = "";				
				for (i=0; i< poligonos.length; i++)
				{
					if(poligonos_activos[i] && inclusion_poligono(cy, cx, poligonos[i]))
					{
							estoy_en_zona = estoy_en_zona + desc_poligonos[i] + "; ";
					}
				}

				document.getElementById("textoubicacion").value = estoy_en_zona;
				
				if (!document.getElementById("bloqueamapa").checked)
						map.panTo(new GLatLng(cy, cx));
	
	//		alert("X: " + cx + "; Y: " + cy);
	//    document.getElementById("coordenadas").value = "X: " + cx + "; Y: " + cy;

			setTimeout("MostrarConsulta('consulta.php')", 2000);
			}
		}
//		primera_vez = false;
//	}
	ajax.send(null);

}

function cambiar_visualizacion_poligono(numero, onoff)
{
	if(onoff)
	{
			// Mostrar
			map.addOverlay(poligonos[numero]);
	}
	else
		{
				// Ocultar
				map.removeOverlay(poligonos[numero]);
		}

  poligonos_activos[numero] = onoff;
  
	return;
}

function cambiar_tipo_mapa(tipo){
	if (tipo == 1)
	{
	      map.setMapType(G_SATELLITE_MAP);
	}
	else if (tipo == 2)
		{
	      map.setMapType(G_NORMAL_MAP);
		}
	else if (tipo == 3)
		{
	      map.setMapType(G_HYBRID_MAP);
		}
	else if (tipo == 4)
		{
	      map.setMapType(G_PHYSICAL_MAP);
		}
	else if (tipo == 5)
		{
	      map.setMapType(G_SATELLITE_3D_MAP);
		}
	else
		{
	      map.setMapType(G_SATELLITE_MAP);
		}
	      	
	return;	      	
}
function Carga_zonas(){
	
	num_zonas = 0;
//	zonas[0]= ....
}

function Inicializa(){

    var WINDOW_HTML = '<div style="width: 210px; padding-right: 10px"><a href="http://www.google.com/apis/maps/signup.html">Suscríbase</a> para obtener la clave de la API de Google Maps (en inglés).</div>';

		var icono_ruta = new GIcon(); 
    icono_ruta.image = './imagenes/icono_posicion2.gif';
    icono_ruta.shadow = './imagenes/icono_posicion2.gif';
    icono_ruta.iconSize = new GSize(24, 24);
    icono_ruta.shadowSize = new GSize(24, 24);
    icono_ruta.iconAnchor = new GPoint(12, 12);
    icono_ruta.infoWindowAnchor = new GPoint(5, 1);

		primera_vez= true;

    if (GBrowserIsCompatible()) {
      map = new GMap2(document.getElementById("map"));
      map.addControl(new GSmallMapControl());

      map.setCenter(new GLatLng(cy, cx), 13);
      map.setMapType(G_SATELLITE_MAP);

      marker = new GMarker(new GLatLng(cy, cx), {icon:icono_ruta});
      map.addOverlay(marker);

      GEvent.addListener(map, "singlerightclick", function() {
		map.setcenter(new fromDivPixelToLatLng(point));
		document.getElementById("coordenadas").value = map.getCenter().toUrlValue();
		document.getElementById("velocidad").value = vel;
		document.getElementById("direccion").value = dir;} );

      GEvent.addListener(map, "moveend", function() {
		document.getElementById("coordenadas").value = map.getCenter().toUrlValue();
		document.getElementById("velocidad").value = vel;
		document.getElementById("direccion").value = dir;} );

    document.getElementById("coordenadas").value = map.getCenter().toUrlValue()
    document.getElementById("velocidad").value = 0.0;
		document.getElementById("direccion").value = 0.0;
    }

		//]]>
	
}
</SCRIPT>

</HEAD>
<BODY>  <!-- onload="Inicializa();MostrarConsulta('consulta.php')">  -->
<!-- <FORM>  --> 
<!-- <CENTER><B><H2>Localización en mapa de terminal móvil</H2></B></CENTER>  -->
	<DIV style="position:absolute;top:10px;left:10px;width:85%; height:90%">
		<DIV style="position:absolute;top:1px;width:140px">
			<IMG src="imagenes/ley_coordenadas.jpg" width="120px" height="25px"/><BR> <input id="coordenadas" type="text" name="uname" size="20" maxlength="40" style="width:120px;height:25px" readonly="Yes"><BR>
			<IMG src="imagenes/ley_velocidad.jpg" width="120px" height="25px"/><BR><input id="velocidad" type="text" name="uname" size="20" maxlength="40" style="width:120px;height:25px" readonly="Yes"><BR>
			<IMG src="imagenes/ley_direccion.jpg" width="120px" height="25px"/><BR><input id="direccion" type="text" name="uname" size="20" maxlength="40" style="width:120px;height:25px" readonly="Yes"><BR>
			<IMG src="imagenes/ley_tipomapa.jpg" width="120px" height="25px"/><BR>
			<select id="tipomapa" name="uname" style="width:120px;height:25px" onchange="cambiar_tipo_mapa(this.value)">
			<option value="1">Satélite</option>
			<option value="2">Callejero</option>
			<option value="3">Híbrido</option>
			<option value="4">Relieve</option>
			<option value="5">3D</option>
			</select><BR>
			<IMG src="imagenes/ley_bloqueamapa.jpg" width="120px" height="25px"/><BR>
			<CENTER><input id="bloqueamapa" type="CHECKBOX" name="uname" style="width:25px;height:25px" value="SI"><BR></CENTER>
			<IMG src="imagenes/ley_zonas.jpg" width="120px" height="25px"/><BR>

<?php
			
			// Código para rellenar la tabla de todas las zonas definidas en la ruta.

			$orden = 0;

			echo "<TABLE ID=\"tabla_zonas\" BORDER=\"0\" cellspacing=\"0\" WIDTH=\"120px\" style=\"font-family: Verdana, Helvetica; font-weight: bold; font-size:9px\"><THEAD><TD>" . "<INPUT style=\"border:0;width:15px;height:12px;font-family: Verdana, Helvetica; font-weight: bold; font-size:9px\" VALUE=\"Cod\" READONLY=\"Yes\">" . "</TD><TD>"  . "<INPUT style=\"border:0;width:70px;height:12px;font-family: Verdana, Helvetica; font-weight: bold; font-size:9px\" VALUE=\"Nombre\" READONLY=\"Yes\">" .  "</TD><TD>" . "<INPUT style=\"border:0;width:15px;height:12px;font-family: Verdana, Helvetica; font-weight: bold; font-size:9px\" VALUE=\"Visible\" READONLY=\"Yes\">" . "</TD><TD style=\"display: none\">Orden</TD><TD style=\"display: none\">Descripcion</TD><TD style=\"display: none\">xmin</TD><TD style=\"display: none\">ymin</TD><TD style=\"display: none\">xmax</TD><TD style=\"display: none\">ymax</TD></THEAD><TBODY>";
			
			$con = mysql_connect("mysql.webcindario.com","dondestoy","moreno1");
			if (!$con)
			  {
			  die('Imposible conectar a la base de datos: ' . mysql_error());
			  }

			mysql_select_db("dondestoy", $con);
			mysql_query("FLUSH TABLES");

			$result = mysql_query("select codigo, nombre, descripcion, xmin, ymin, xmax, ymax from zonas");
			
			$codigo_pinta_poligonos = "<script type=\"text/javascript\">";
			
			while(($row = mysql_fetch_array($result)))
			{
				echo "<TR><TD>" . "<INPUT style=\"border:0;width:15px;height:12px;font-family: Verdana, Helvetica; font-size:9px\" VALUE=\"". $row['codigo'] . "\" READONLY=\"Yes\">" . "</TD><TD>" . "<INPUT style=\"border:0;width:70px;height:12px;font-family: Verdana, Helvetica; font-size:9px\" VALUE=\"" . $row['nombre'] . "\" READONLY=\"Yes\">" . "</TD><TD>" . "<input type=CHECKBOX value=SI CHECKED onclick=\"cambiar_visualizacion_poligono(" . $orden . ", this.checked);\">" . "</TD>";
				echo "<TD style=\"display: none\">" . $orden . "</TD><TD style=\"display: none\">" . $row['descripcion'] . "</TD><TD style=\"display: none\">" . $row['xmin'] . "</TD><TD style=\"display: none\">" . $row['ymin'] . "</TD><TD style=\"display: none\">" . $row['xmax'] . "</TD><TD style=\"display: none\">" . $row['ymax'] . "</TD>";
				echo "</TR>";

				// *** Código para generar el javascript que carga los polígonos en el mapa.

				$codigo_pinta_poligonos .= "poligonos[" . $orden . "] = new GPolygon([], '#0000FF', 2, 1, 1); ";
				$codigo_pinta_poligonos .= "map.addOverlay(poligonos[" . $orden . "]); ";
				$codigo_pinta_poligonos .= "desc_poligonos[" . $orden . "] = \"" . $row['descripcion'] . "\"; ";
				$codigo_pinta_poligonos .= "poligonos_activos[" . $orden . "] = true; ";
				
			
				$result2 = mysql_query("select orden, lat, lng from vertices_zonas where codigo_zona = " . $row['codigo'] . " order by orden");

				$vertices = 0;
								
				while (($punto = mysql_fetch_array($result2)))
				{
					$codigo_pinta_poligonos .= "poligonos[" . $orden . "].insertVertex(" . $vertices . ", new GLatLng(" . $punto['lat'] . ", " . $punto['lng'] . "));";
					$vertices++;
				}
				
				$orden++;
			}

			$codigo_pinta_poligonos .= "</script>";
			
			echo "</TBODY></TABLE>";
?>

		</DIV>
		<div id="map" style="position:absolute;top:61px;left:150px;border: 2px solid #979797; background-color: #e5e3df; width: 100%; height: 90%">
			<div style="padding: 1em; color: gray">Cargando...</div>
		</div>
		<DIV id="Ubicacion" style="position:absolute; top:1px; left:150px; width:100%; height:50px">
			<input id="textoubicacion" type="text" name="uname" style="position:relative;top:1px;left:1px;width:100%;height:100%;font-family: Verdana, Helvetica; font-weight: bold; font-size:30px; color: #0000ff" readonly="Yes" value="Zona actual ...">
		</DIV>
	</DIV>
<SCRIPT LANGUAGE="JavaScript">
	Inicializa();
	MostrarConsulta('consulta.php');
</SCRIPT>

<?php

			// Aquí escribimos el código generado en ***
			echo $codigo_pinta_poligonos;
?>

</FORM>
</BODY>
</HTML>
