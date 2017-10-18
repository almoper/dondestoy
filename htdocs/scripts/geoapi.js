
function check_tri_clock_dir(a1, b1, a2, b2, a3, b3)
{

	// Devuelve el sentido de un triángulo definido por sus tres vértices.

	var test0;
	
	test0 = (((a2 - a1)*(b3 - b1)) - ((a3 - a1)*(b2 - b1)));

	if (test0 > 0) return 1;
	else if(test0 < 0) return -1;
	else return 0;
 	return 0;
}

function distancia(y1, x1, y2, x2)
{

	// Devuelve la distancia en metros entre dos puntos geodésicos

 var degtorad, radtodeg, dlong, dvalue, dd, metros;

 degtorad = 0.01745329;
 radtodeg = 57.29577951;

 dlong = (x1 - x2); 
 dvalue = (Math.sin(y1 * degtorad) * Math.sin(y2 * degtorad)) + (Math.cos(y1 * degtorad) * Math.cos(y2 * degtorad) * Math.cos(dlong * degtorad)); 
    
  dd = Math.acos(dvalue) * radtodeg; 

  metros = (dd * 111.302) * 1000;

  return metros;
}


function interseccion_lineas(a1, b1, a2, b2, a3, b3, a4, b4)
{
	
		// Se ha modificado la función para no devolver que intersecta cuando las líneas comparten uno de los puntos [AB2 = AB3] o [AB1 0 AB4]
		
   var test1_a, test1_a, test2_a, test2_a;

		if ((a2 == a3 && b2 == b3) || (a1 == a4 && b1 == b4))
		{
			// Las líneas comparten un punto extremo
			// Medir ángulo entre las líneas. Si es 0 entonces intersectan.
			// El ángulo será 0 si el lado opuesto del triángulo mide igual a la suma de los otros dos lados
			// D[(a1,b1),(a4,b4)] = D[(a1,b1),(a2,b2)] + D[(a3,b3),(a4,b4)]
			// Aplicar algún redondeo (ej: a precisión metros).
			
			if ((distancia(a1,b1,a4,b4) == distancia(a1,b1,a2,b2) + distancia(a3,b3,a4,b4)) || 
						(distancia(a2,b2,a3,b3) == distancia(a1,b1,a2,b2) + distancia(a3,b3,a4,b4)))
				return true;
			else
				return false;
		}
		else
		{
		   test1_a = check_tri_clock_dir(a1, b1, a2, b2, a3, b3);
		   test1_b = check_tri_clock_dir(a1, b1, a2, b2, a4, b4);
		
		   if (test1_a != test1_b)
		   {
		      test2_a = check_tri_clock_dir(a3, b3, a4, b4, a1, b1);
		      test2_b = check_tri_clock_dir(a3, b3, a4, b4, a2, b2);
		      if (test2_a != test2_b)
		      {
		         return true;
		      }
		   }
		 }
   return false;

}

function inclusion_poligono(y, x, pol)
{
	var intersecciones, j, vertices;

	// !!!!! Ahora estoy haciendo solo el test con un punto externo. Mejor hacerlo con varios puntos externos y tomar el mínimo número de intersecciones.

	
	intersecciones = 0;

	vertices = pol.getVertexCount();
			
	for(j=0; j < (vertices - 1);j++)
	{
		if (interseccion_lineas(pol.getVertex(j).lat(), pol.getVertex(j).lng(), pol.getVertex(j+1).lat(), pol.getVertex(j+1).lng(),
														y, x, 0, 0 ))
		{
			intersecciones++;
		}
	}

	if (intersecciones%2 == 0)
		return false;
	else
		return true;
}
