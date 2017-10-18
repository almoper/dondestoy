<?php 

/*
 * Alfonso Moreno. 2010.
 */

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

//	$header = 'From: noreply@limedomains.com' . "\r\n" . 'Reply-To: alfonso.moreno.perez@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
//	$header = 'From: noreply@limedomains.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'Date: Tue, 9 Mar 2010 13:03:41 +0100' . "\r\nMIME-Version: 1.0\r\nContent-Type: text/plain;\r\n\tcharset=\"us-ascii\"\r\n";
	$header = 'From: noreply@limedomains.com' . "\r\n" . 'To: alfonso.moreno.perez@juntadeandalucia.es' . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'Date: Tue, 9 Mar 2010 13:03:41 +0100' . "\r\nMIME-Version: 1.0\r\nContent-Type: text/plain;\r\n\tcharset=\"us-ascii\"\r\n";
	echo $header . "<BR>";

	mail('alfonso.moreno.perez@juntadeandalucia.es', 'Prueba de correo', 'Esto es una prueba de envio de correo.', $header);
	echo 'alfonso.moreno.perez@juntadeandalucia.es';
//	mail('alfonso.moreno.perez@gmail.com', 'Prueba de correo', 'Esto es una prueba de envio de correo.', $header);
//	echo 'alfonso.moreno.perez@gmail.com';

?>
