<HTML>
<HEAD>
	<TITLE>Localización de terminal móvil</TITLE>

<!-- Map loader -->
  <script src="http://maps.google.es/maps?file=api&amp;v=2&amp;key=ABQIAAAAAVTKxI3HxxSgPlyvdbcxkhTbGTFVr2ORtenxJALOqERv93-W1hTUxkJ97or1-_Ndw2gLYz0COzSfvQ" type="text/javascript"></script>
 	<SCRIPT LANGUAGE="JavaScript">

    //<![CDATA[

	var map, cx, cy, marker, vel, dir;
	var pc1, pc2, pc3;
	var primera_vez;

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
				map.panTo(new GLatLng(cy, cx));
	
	//		alert("X: " + cx + "; Y: " + cy);
	//    document.getElementById("coordenadas").value = "X: " + cx + "; Y: " + cy;
	
			setTimeout("MostrarConsulta('xtra/consulta.php')", 2000);
			}
		}
//		primera_vez = false;
//	}
	ajax.send(null);

}

function Inicializa(){

    var WINDOW_HTML = '<div style="width: 210px; padding-right: 10px"><a href="http://www.google.com/apis/maps/signup.html">Suscríbase</a> para obtener la clave de la API de Google Maps (en inglés).</div>';

		var icono_ruta = new GIcon(); 
    icono_ruta.image = 'xtra/icono_posicion.gif';
    icono_ruta.shadow = 'xtra/icono_posicion.gif';
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
<BODY>  <!-- onload="Inicializa();MostrarConsulta('xtra/consulta.php')">  -->
<!-- <FORM>  --> 
<!-- <CENTER><B><H2>Localización en mapa de terminal móvil</H2></B></CENTER>  -->
<B> Coordenadas: </B> <input id="coordenadas" type="text" name="uname" size="20" maxlength="40" readonly="Yes">
<B> Velocidad (km/h): </B><input id="velocidad" type="text" name="uname" size="20" maxlength="40" readonly="Yes">
<B> Direccion: </B><input id="direccion" type="text" name="uname" size="20" maxlength="40" readonly="Yes">
<div id="map" style="border: 2px solid #979797; background-color: #e5e3df; width: 100%; height: 200px; margin: auto; margin-top: 2em; margin-bottom: 2em">
<div style="padding: 1em; color: gray">Cargando...</div>
</div>

<SCRIPT LANGUAGE="JavaScript">
	Inicializa();
	MostrarConsulta('xtra/consulta.php');
</SCRIPT>

</FORM>
</BODY>
</HTML>
