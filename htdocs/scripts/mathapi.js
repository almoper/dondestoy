
function redondear(numero, decimales)
{
	// Redondea el número pasado como parámetro al número de decimales definido.

	var redondeado;
	
	redondeado = Math.round(numero * Math.pow(10,decimales)) / Math.pow(10,decimales);

 	return redondeado;
}

