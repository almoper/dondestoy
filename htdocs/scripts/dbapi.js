
function objetoAjax()
{
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


function ejecutar_db(datos)
{
		var retorno;
		
		retorno = "";
		ajax=objetoAjax();
		
		ajax.open("GET", datos, true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {

				retorno = ajax.responseText;
			}
		}
		
		ajax.send(null);
		
		return retorno;
}

