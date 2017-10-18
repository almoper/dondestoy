<?
require_once 'dir.h.php';

  if (!__is_dir($_GET['dirname'])) {
    echo "No es un directrio";
    exit;
  }

  $D = __opendir($_GET['dirname']);
  while ($F = __readdir($D))
    if (($F != '.') || ($F != '..'))
      echo "{$F}<br>";
  __closedir($D);
?>