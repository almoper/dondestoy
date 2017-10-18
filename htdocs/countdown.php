<HTML>
	<HEAD>
	<TITLE>Cuenta atrás Fórmula 1</title>
	</HEAD>
	<BODY><CENTER>
<?php 
//http://simplehtmldom.sourceforge.net/ 
//require("simplehtmldom/simple_html_dom.php");

$url="http://www.formula1.com/index.html";
$html=file_get_contents($url);

 /*** a new dom object ***/ 
    $dom = new domDocument;

    /*** load the html into the object ***/ 
    $dom->loadHTML($html); 

    /*** discard white space ***/
    $dom->preserveWhiteSpace = false; 

    /*** the table by its tag name ***/ 
    $horas = $dom->getElementById('hours');

		$otro = new domDocument;
		$otro->loadHTML($horas);
		
    echo "Horas: ".$otro->saveHTML.".";
    echo '<br />'; 
?> 
</BODY>
</HTML>