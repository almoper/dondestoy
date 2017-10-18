<?
/*
 * dir.h.php - library
 * author: rodrigo nannig m.
 * $ Id: dir.h.php 2009 rnm$
 */
define(PT_FILENAME_LINE, '/^.*<a.*href=\"(.*)\".*>\s*(.*)\s*<\/a>(\/?).*$/i');
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
      if ($P[1] == $P[2]) {
        $F[$i]['fname'] = $P[1];
        $F[$i]['token'] = !$i? 1: 0;
        $i++;
      }
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
      return ($D[$i]['fname']);
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
?>
