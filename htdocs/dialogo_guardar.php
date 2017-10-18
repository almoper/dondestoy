<HTML>
<HEAD>
	<TITLE>Guardar zona</TITLE>

  <script src="scripts/dbapi.js" type="text/javascript"></script>
  <script src="scripts/mathapi.js" type="text/javascript"></script>
	<SCRIPT LANGUAGE="JavaScript">

		var datos=new Array(); 

		function recuperar_parametros()
		{
				datos=dialogArguments; 
				document.getElementById("element_3").value = redondear(datos[0].getVertex(0).lat(), 3) + ', ' + redondear(datos[0].getVertex(0).lng(), 3);
				document.getElementById("element_4").value = redondear(datos[0].getVertex(2).lat(), 3) + ', ' + redondear(datos[0].getVertex(2).lng(), 3);
				document.getElementById("element_3").disabled = true;
				document.getElementById("element_4").disabled = true;
		}		
		
		function guardar_poligono(poligono)
		{
			var i, puntos, direccion, resultado;
			
			puntos = "";
			for (i=0;i<poligono.getVertexCount();i++)
			{
					puntos = puntos + poligono.getVertex(i).lat() + '|' + poligono.getVertex(i).lng() + '|';
			}

			direccion = 'guardar_poligono.php?nombre=' + document.getElementById("element_1").value + '&descripcion=' + document.getElementById("element_2").value + '&xmin=' + datos[0].getVertex(0).lng() + '&ymin=' + datos[0].getVertex(0).lat() + '&xmax=' + datos[0].getVertex(2).lng() + '&ymax=' + datos[0].getVertex(2).lat() + '&vertices=' + puntos;
			
			resultado = ejecutar_db(direccion);
		}
	</SCRIPT>
</HEAD>
<BODY onload="recuperar_parametros();">
<CENTER><BR><B>Guardar zona.</B>
<BR><BR>
		<label for="element_1"><B>Nombre </B></label>
		<div>
			<input id="element_1" name="element_1" type="text" maxlength="100" value="" style="width:200px;height:25px"/>
		</div>

		<label for="element_2"><B>Descripcion </B></label>
		<div>
			<input id="element_2" name="element_2" type="text" maxlength="255" value="" style="width:200px;height:25px"/>
		</div> 

		<label for="element_3"><B>Esquina 1 </B></label>
		<div>
			<input id="element_3" name="element_3" type="text" maxlength="30" value="" style="width:200px;height:25px"/> 
		</div> 
		<label for="element_4"><B>Esquina 2 </B></label>
		<div>
			<input id="element_4" name="element_4" type="text" maxlength="30" value="" style="width:200px;height:25px"/> 
		</div> 
<BR>
<input id="botonguardar" type="button" value="Guardar zona" style="width:120px;height:25px" onclick='guardar_poligono(datos[1]); window.close();'/>
<input id="botoncancelar" type="button" value="Cancelar" style="width:120px;height:25px" onclick='window.close();'/>
</CENTER>
</BODY>
</HTML>
