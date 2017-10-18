<?php 

/*
 * Alfonso Moreno. 2010.
 * Se utiliza la librería dir.h.php (author: rodrigo nannig m.), con algunos cambios.
 * $ Id: dir.h.php 2009 rnm$
 */

//define(PT_FILENAME_LINE, '/^.*<a.*href=\"(.*)\".*>\s*(.*)\s*<\/a>(\/?).*$/i');
define(PT_FILENAME_LINE, '/^.*<a.*href=\"(.*)\".*>\s*(.*)\s*<\/a>(\/?)\s*(.*)\s\s*(\S.*)\s*$/i');
define(PT_DRIVER_LINE, '/^\s*Driver\s*=\s*(.*)\s*$/i');
define(PT_SCENE_LINE, '/^\s*Scene\s*=\s*(.*)\s*$/i');
define(PT_SESION_LINE, '/^\s*Session\s*=\s*(.*)\s*$/i');


$curdir='';

function
__opendir($path)
{
  global $curdir;

  if (!preg_match('/\/$/', $path))
    $path .= '/';
  if (!($D = @file("{$path}", FILE_SKIP_EMPTY_LINES|FILE_TEXT)))
    return (false);
  if (!$curdir)
    $curdir = $path;
  $F = array(); $i = 0;
  foreach ($D as $l) {
    array_shift($D);
   	if (preg_match(PT_FILENAME_LINE, $l, $P)) {
      $P[1] = getfilename($P[1]);
      $P[2] .= ($P[3] == '/')? $P[3]: '';
//      if ($P[1] == $P[2]) {
        $F[$i]['fname'] = $P[1];
        $F[$i]['token'] = !$i? 1: 0;
        $F[$i]['fecha'] = $P[4];
        $F[$i]['size'] = $P[5];

        $i++;
//      }
    }
  }
  return ($F);
}

function
getfilename($path)
{
  $path = preg_replace('/%20/', ' ', $path);
  if (!preg_match('/\/$/', $path))
    return (basename($path));
  else
    return (basename($path).'/');
}

function
__readdir(&$D)
{
  for ($i = 0; $D[$i]; $i++) {
    if ($D[$i]['token']) {
      $D[$i]['token'] = 0;
      if ($D[$i + 1])
        $D[$i + 1]['token'] = 1;
//      return ($D[$i]['fname']);
      return ($D[$i]);
    }
  }
  return (false);
}

function
__scandir($D, $sort = 0)
{
  $F = array();
  $F[] = '.'; $F[] = '..';
  for ($i = 0; $D[$i]; $i++)
    $F[] = $D[$i]['fname'];
  !$sort? asort($F): arsort($F);
	return ($F);
}

function
__rewinddir(&$D)
{
  for ($i = 0; $D[$i]; $i++)
    $D[$i]['token'] = !$i? 1: 0;
  return (true);
}

function
__closedir(&$D)
{
  if (is_array($D))
    array_splice($D, 0, count($D));
}

function
__getcwd()
{
  global $curdir;
  return ($curdir);
}

function
__chdir($path)
{
  global $curdir;

  if (!preg_match('/\/$/', $path))
    $path .= '/';
  if (preg_match('/^http:\/\//', $path)) {
    $p = $path;
  } else {
    if (preg_match('/^\.\/(.*)$/', $path, $P))
      $path = $P[1];
  }
  $p = $curdir.$path;
  if (!@file($p))
    return (false);
  $curdir = $p;
  return (true);
}

function
__is_dir($dname)
{
  if (!preg_match('/\/$/', $dname))
    $dname .= '/';
  if (!@file("{$dname}"))
    return (false);
  return (true);
}

function
__is_file($dir)
{
  if (!preg_match('/\/$/', $dir))
    return (true);
  return (false);
}







// require_once 'dir.h.php';

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");


$ruta = 'http://www.onlineracers.es/liveview/logs';
$usuarios_buscar = array(0 => 'danisan99', 1 => 'el_celi', 2 => 'fonzo', 3 => 'ERB');
//$usuarios_buscar = array('danisan99', 'el_celi', 'fonzo');

$directorio = __opendir($ruta);

if ($directorio == FALSE)
{
		echo "No existe el directorio.<BR>";
}
	
//$directorio = opendir(".");

while($fic =  __readdir($directorio))
{
	$fichero = $fic['fname'];
//	echo date('l dS \o\f F Y h:i:s A', strtotime($fic['fecha'])) . ' - ' . $fic['size'] . '<BR>';

	if ((($fichero != '.') || ($fichero != '..')) && (time() - strtotime($fic['fecha'])) < 300)   // Fichero actualizado en los úlimos 5 minutos.
	{
  	$ruta_fichero = $ruta . '/' . $fichero;
  	if (preg_match('/_/', $fichero, $l))
  	{
  		$pos = strpos($fichero, '_');
  		$clase = substr($fichero, 0, $pos);
  		if ( $TABLA[$clase]['check'] != 1 || $TABLA[$clase]['check'] == NULL )
  		{
	  		$TABLA[$clase]['check'] = 1;
	  		$TABLA[$clase]['clase'] =  $clase;
	  	}

			$TABLA[$clase]['fichero']= $ruta_fichero;

		}
	}
}

			foreach ($TABLA as $linea)
			{
					$contenido_fichero = file(preg_replace('/ /', '%20', $linea['fichero']), FILE_SKIP_EMPTY_LINES|FILE_TEXT);
					
					if ($contenido_fichero)
					{
						$j = 0;
						foreach($contenido_fichero as $linea_fichero)
						{
							array_shift($contenido_fichero);
							
					   	if (preg_match(PT_SCENE_LINE, $linea_fichero, $scene))
					   	{
					   		$TABLA[$linea['clase']]['scene'] = $scene[1];
				      }
				      else if (preg_match(PT_SESION_LINE, $linea_fichero, $sesion))
					   	{
					   		$TABLA[$linea['clase']]['sesion'] = $sesion[1];
				      }
					   	else if (preg_match(PT_DRIVER_LINE, $linea_fichero, $driver))
					   	{
					   		$j++;
					   		$TABLA[$linea['clase']]['drivers'][$j] = trim($driver[1]);
				      }
						}
 			   		$TABLA[$linea['clase']]['ndrivers'] = $j;

					}
					else
					{
						echo "Fichero no encontrado: " . $linea['fichero'] . "<BR>";
						echo "<BR>";
					}

					echo "<B>" . $linea['clase'] . ' - ' . $TABLA[$linea['clase']]['scene'] . ' (' . $TABLA[$linea['clase']]['sesion'] . ')</B><BR>';
					
					if ($TABLA[$linea['clase']]['ndrivers'] > 0)
					{
						print_r($TABLA[$linea['clase']]['drivers']);
						echo "<BR>";

						foreach ($usuarios_buscar as $usuario_buscar)
						{
//							echo "Buscando usuario |" . $usuario_buscar . '|<BR>';
							
							if (array_search($usuario_buscar, $TABLA[$linea['clase']]['drivers']))
							{
									echo "$usuario_buscar esta conectado!!!.<BR>";
									
									// SI EL USUARIO NO ESTABA MARCADO, ENVIAR CORREO.
									// MARCAR USUARIO
							}
							else
							{
									// SI EL USUARIO ESTABA MARCADO, ENVIAR CORREO.
									// DESMARCAR USUARIO
							}
						}
							
					}
					else
					{
						echo "No hay usuarios conectados en esta categoría.<BR>";
					}

				echo "<BR>";
			}

__closedir($directorio);

//	$response = http_get("http://www.onlineracers.es/liveview/logs/");
//	print_r( $response);

?>