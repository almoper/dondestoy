
function redondear(numero, decimales)
{
	// Redondea el n�mero pasado como par�metro al n�mero de decimales definido.

	var redondeado;
	
	redondeado = Math.round(numero * Math.pow(10,decimales)) / Math.pow(10,decimales);

 	return redondeado;
}

