
    //<![CDATA[

	var map, cx, cy, marker, vel, dir;
	var pc1, pc2, pc3;
	var primera_vez;

	var zona_polilinea, zona_poligono;
	var num_vertices, minx, miny, maxx, maxy;

	var escuchador, escuchador2;

	cy = 40.4142;
	cx = -3.6836;

function Inicializa(){

    var WINDOW_HTML = '<div style="width: 210px; padding-right: 10px"><a href="http://www.google.com/apis/maps/signup.html">Suscr�base</a> para obtener la clave de la API de Google Maps (en ingl�s).</div>';

//		var icono_ruta = new GIcon(); 
//    icono_ruta.image = './imagenes/icono_posicion.gif';
//    icono_ruta.shadow = './imagenes/icono_posicion.gif';
//    icono_ruta.iconSize = new GSize(24, 24);
//    icono_ruta.shadowSize = new GSize(24, 24);
//    icono_ruta.iconAnchor = new GPoint(12, 12);
//    icono_ruta.infoWindowAnchor = new GPoint(5, 1);

		primera_vez= true;

    if (GBrowserIsCompatible()) {
      map = new GMap2(document.getElementById("map"));
      map.addControl(new GSmallMapControl());

      map.setCenter(new GLatLng(cy, cx), 13);
      map.setMapType(G_SATELLITE_MAP);

//      marker = new GMarker(new GLatLng(cy, cx), {icon:icono_ruta});
//      map.addOverlay(marker);

			escuchador2 = GEvent.addListener(map, 'mouseover', function(overlay, latlng) {
				document.getElementById("coordenadas").value = latlng.toUrlValue();
				return;});
    }
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

function inicia_captura_poligono()
{
	map.clearOverlays();
	zona_polilinea = new GPolyline([], '#FF0000', 3, 1, {clickable:false});
	map.addOverlay(zona_polilinea);

	num_vertices = 0;

    escuchador = GEvent.addListener(map, "click", function(overlay, latlng) {
    	var interseccion, j;

    	// A�adir punto a la polilinea.

			if(num_vertices == 0)
			{
				minx = latlng.lng();
				maxx = minx;
				miny = latlng.lat();
				maxy = miny;
			}
			else
			{
					if (latlng.lng() < minx)
						minx = latlng.lng();

					if (latlng.lng() > maxx)
						maxx = latlng.lng();

					if (latlng.lat() < miny)
						miny = latlng.lat();

					if (latlng.lat() > maxy)
						maxy = latlng.lat();
			}
			interseccion = false;
			for(j=0;j < (num_vertices - 1);j++)
			{
				if (interseccion_lineas(zona_polilinea.getVertex(j).lat(), zona_polilinea.getVertex(j).lng(), zona_polilinea.getVertex(j+1).lat(), zona_polilinea.getVertex(j+1).lng(), zona_polilinea.getVertex(num_vertices - 1).lat(), zona_polilinea.getVertex(num_vertices - 1).lng(), latlng.lat(), latlng.lng() ))
				{
					interseccion = true;
					j=num_vertices;
				}
			}

			if (interseccion)
			{
				alert("No se permiten intersecciones entre las aristas del pol�gono");
			}
			else
			{
	    	zona_polilinea.insertVertex(num_vertices, latlng);
	    	num_vertices = num_vertices + 1;

// 				document.getElementById("botonatras").value = num_vertices;

				if (num_vertices > 2)
					document.getElementById("botonatras").disabled=false;				
							
	    	if (num_vertices >= 3)
	    		document.getElementById("botonfin").disabled=false;
	    	}
    				} );
	document.getElementById("botoninicio").disabled=true;
	document.getElementById("botonguardar").disabled=true;
}

function finaliza_captura_poligono()
{
    	var interseccion2, k;

 		// !!!!!!!! Comprobar si hay intersecci�n entre la �ltima arista (autom�tica) y el resto.
		// Si es as� devolver un error, y no finalizar la definici�n del pol�gono.

			interseccion2 = false;

			for(k=0;k < (num_vertices - 1);k++)
			{
				if (interseccion_lineas(zona_polilinea.getVertex(k).lat(), zona_polilinea.getVertex(k).lng(), 
																zona_polilinea.getVertex(k+1).lat(), zona_polilinea.getVertex(k+1).lng(), 
																zona_polilinea.getVertex(num_vertices - 1).lat(), zona_polilinea.getVertex(num_vertices - 1).lng(), 
																zona_polilinea.getVertex(0).lat(), zona_polilinea.getVertex(0).lng() ))
				{
					interseccion2 = true;
					k=num_vertices;
				}
			}

			if (interseccion2)
			{
				alert("La �ltima aritsta (autom�tica) provoca una intersecci�n con otra l�nea del pol�gono. No se permiten intersecciones entre las aristas del pol�gono");
			}
			else
			{
				zona_poligono = new GPolygon([], '#0000FF', 2, 1, 1);
				map.addOverlay(zona_poligono);
				map.removeOverlay(zona_polilinea);
			
			
			    GEvent.removeListener(escuchador);
			
			    for (i=0;i<num_vertices;i++)
			    {
			        zona_poligono.insertVertex(i, zona_polilinea.getVertex(i));
			    }	
			    zona_poligono.insertVertex(i, zona_polilinea.getVertex(0));
				document.getElementById("botonfin").disabled=true;
				document.getElementById("botonguardar").disabled=false;
				document.getElementById("botoninicio").disabled=false;
				document.getElementById("botonatras").disabled=true;
		}
		
}

function guarda_poligono()
{
	var cuadrante_minimo;
	var datos;
	var argumentos = new Array();
		
/*			if( inclusion_poligono(cy, cx, zona_poligono))
			alert("SI ESTA INCLUIDO");
		else
			alert("NO ESTA INCLUIDO");
*/

/// PRUEBA: Pintar cuadrante m�nimo:

				cuadrante_minimo = new GPolygon([], '#FFFFFF', 2, 1, 0);
				map.addOverlay(cuadrante_minimo);
			
			  cuadrante_minimo.insertVertex(0, new GLatLng(miny, minx));
			  cuadrante_minimo.insertVertex(1, new GLatLng(maxy, minx));
			  cuadrante_minimo.insertVertex(2, new GLatLng(maxy, maxx));
			  cuadrante_minimo.insertVertex(3, new GLatLng(miny, maxx));

//Cierre
			  cuadrante_minimo.insertVertex(4, new GLatLng(miny, minx));

/// FIN PRUEBA

	document.getElementById("botoninicio").disabled=false;
	document.getElementById("botonfin").disabled=true;
	document.getElementById("botonguardar").disabled=true;
	document.getElementById("botonatras").disabled=true;
/*	
	argumentos[0] = minx;
	argumentos[1] = miny;
	argumentos[2] = maxx;
	argumentos[3] = maxy;
	argumentos[4] = zona_poligono;
*/	
argumentos[0] = cuadrante_minimo;
argumentos[1] = zona_poligono;

	datos = window.showModalDialog("dialogo_guardar.php",argumentos,'status:no;resizable:no;center:on;dialogWidth:280px;dialogHeight:340px');

//cuando cierres el showmodal seguir� por aqu�

//y podr�s trabajar con la variable datos que contendr� lo que hayas devuelto en el showmodal con la funci�n window.returnValue = valorDevuelto;


}

function undo_poligono()
{
	num_vertices = num_vertices - 1;
//  document.getElementById("botonatras").value = num_vertices;
	zona_polilinea.deleteVertex(num_vertices);

	if (num_vertices <= 2)
		document.getElementById("botonatras").disabled=true;
							
	if (num_vertices < 3)
		document.getElementById("botonfin").disabled=true;	
		
		// !!!!!!!! Al hacer UNDO puede ser necesario recalcular los puntos del cuadrante m�nimo.
}

//]]>

