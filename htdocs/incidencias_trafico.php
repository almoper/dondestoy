<?php
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?> 

<HTML>
	<HEAD>
	<TITLE>Visualización de incidencias tráfico en las provincias de CADIZ y SEVILLA</title>
	</HEAD>
	<BODY>
<?php 
//http://simplehtmldom.sourceforge.net/ 
//require("simplehtmldom/simple_html_dom.php");

$url="http://www.dgt.es/portal/informacion_carreteras/incidencias.do?lang=&accion=buscar&ca=1&totalPag=1";
$html=file_get_contents($url);

 /*** a new dom object ***/ 
    $dom = new domDocument;
		
    /*** load the html into the object ***/ 
    $dom->loadHTML($html);

    /*** discard white space ***/
    $dom->preserveWhiteSpace = false; 

    /*** the table by its tag name ***/ 
    /* $tables = $dom->getElementById('tablaResultados')->getElementsByTagName('table');  */

    $tables = $dom->getElementsByTagName('table');


    /*** get all rows from the table ***/
    $rows = $tables->item(0)->getElementsByTagName('tr');

		$cadena='';
		$lugar = '';
		$carretera = '';
		$primera_vez = true;
						
    /*** loop over the table rows ***/
    foreach ($rows as $row) 
    {    	
			$nodo = new DOMDocument('1.0');

      /*** get each column by tag name ***/
      $cols = $row->getElementsByTagName('td');

			//$tipo_nivel = ((DOMElement)$cols->item(0)->lastChild)->getAttribute('img');
			//$tipo_nivel = $cols->item(0)->lastChild->tagName;
	
			$root = $nodo->createElement('html');
			$root = $nodo->appendChild($root);

			$body = $nodo->createElement('body');
			$body = $root->appendChild($body);

			$img = $cols->item(0);
			
//			echo $img->saveHTML();
			
//			$img = $body->appendChild($img);

//			$tipo_nivel = $img->saveHTML();
			
			//echo $tipo_nivel;
/*			
			preg_match_all('/<img[^>]+>/i',$html, $result);

			foreach( $result as $img_tag) 
			{
				preg_match_all('/(alt)=("[^"]*")/i',$img_tag, $alternativo);
				echo $alternativo[1][0];
			}

//			echo $alternativo;
*/
			$causas = trim($cols->item(1)->nodeValue);
			$lugar = trim($cols->item(2)->nodeValue);
			$carretera = trim($cols->item(4)->nodeValue);
			$km = trim($cols->item(5)->nodeValue);
			$sentido = trim($cols->item(6)->nodeValue);
			
			if ( substr_count($lugar, "MEDINA-SIDONIA") > 0 )
			{			
				echo $tipo_nivel . ';  ' . $causas . ';  ' . $lugar . ';  ' . $carretera . ';  ' . $km . ';  ' . $sentido . '<BR>';

/*        if (! $primera_vez)
        {
        	$cadena = $cadena . ' ; ';
        	$cadena = $cadena . $carretera;
        }
        else
        {
        	$primera_vez = false;
        	$cadena = $carretera;
        } */
      }
		}
    
    echo $cadena;

/***    $cadena = 'http://maps.google.com/maps?f=d&source=s_d&daddr=' . $cadena ; ***/

?> 
</BODY>
</HTML>