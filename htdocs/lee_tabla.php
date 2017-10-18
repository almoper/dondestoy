<HTML>
	<HEAD>
	<TITLE>Visualizaci�n de ruta UPS</title>
	</HEAD>
	<BODY>
<?php 
//http://simplehtmldom.sourceforge.net/ 
//require("simplehtmldom/simple_html_dom.php");


//$url="http://wwwapps.ups.com/WebTracking/processRequest?HTMLVersion=5.0&Requester=NES&AgreeToTermsAndConditions=yes&loc=es_ES&tracknum=1Z7F546F6842196403";
$url="http://wwwapps.ups.com/WebTracking/processRequest?HTMLVersion=5.0&Requester=NES&AgreeToTermsAndConditions=yes&loc=es_ES&tracknum=1Z7F546F6846323660";

$html=file_get_contents($url);

 /*** a new dom object ***/ 
    $dom = new domDocument;

    /*** load the html into the object ***/ 
    $dom->loadHTML($html); 

    /*** discard white space ***/
    $dom->preserveWhiteSpace = false; 

    /*** the table by its tag name ***/ 
    $tables = $dom->getElementById('showPackageProgress')->getElementsByTagName('table'); 

    /*** get all rows from the table ***/
    $rows = $tables->item(0)->getElementsByTagName('tr'); 

		$cadena='';
		$destino = '';
		$primera_vez = true;
						
    /*** loop over the table rows ***/ 
    foreach ($rows as $row) 
    {    	
      /*** get each column by tag name ***/ 
      $cols = $row->getElementsByTagName('td');

			$destino_anterior = $destino;
			$destino = trim($cols->item(0)->nodeValue);
			$estado = trim($cols->item(3)->nodeValue);

			if ($destino == '')
			{
				$destino = $destino_anterior;
			}

/* Traza...
			echo '<br>';
			echo $destino . ' - ' . $estado;
*/
    	if ( ($estado != 'INFORMACIÓN DE FACTURACIÓN RECIBIDA') && $destino != $destino_anterior)
    	{
        /*** echo the values ***/ 
/*
        echo $cols->item(0)->nodeValue.'|';
        echo $cols->item(1)->nodeValue.'|';
        echo $cols->item(2)->nodeValue.'|';
        echo $cols->item(3)->nodeValue;
        echo '<br />'; 
*/
        if (! $primera_vez)
        {
        	$cadena = $destino . '+to:' . $cadena;
        }
        else
        {
        	$hoy = $destino;
        	$estado_actual = $estado;
        	$cadena = $destino;
        	$primera_vez = false;
        }
      }
    }
    $cadena = 'http://maps.google.com/maps?f=d&source=s_d&daddr=' . $cadena ;

    echo '<CENTER>El paquete est� hoy en: <B>' . $hoy . ' (' . $estado_actual . ')';
    echo '<br><br>';
    echo '<A HREF="' . $cadena . '">Visualiza ruta ...</A>';
    echo '<br><br>';
    echo '<A HREF="' . $url . '">Detalle de ruta ...</A>';

//    getElementById('maparuta').loadHTML(file_get_contents($cadena));
?> 
</BODY>
</HTML>