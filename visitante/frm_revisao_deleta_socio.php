<?php require_once('../Connections/sindicato.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$socio_rs_socio = "-1";
if (isset($_GET['socio'])) {
  $socio_rs_socio = $_GET['socio'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_socio = sprintf("SELECT    bl_socios.*,    bl_associado.* FROM    bl_socios INNER JOIN bl_associado ON bl_associado.ass_id = bl_socios.ass_id WHERE bl_socios.soc_id = %s", GetSQLValueString($socio_rs_socio, "int"));
$rs_socio = mysql_query($query_rs_socio, $sindicato) or die(mysql_error());
$row_rs_socio = mysql_fetch_assoc($rs_socio);
$totalRows_rs_socio = mysql_num_rows($rs_socio);


if ((isset($_GET['socio'])) && ($_GET['socio'] != "")) {
  $deleteSQL = sprintf("DELETE FROM bl_socios WHERE soc_id=%s",
                       GetSQLValueString($_GET['socio'], "int"));

  mysql_select_db($database_sindicato, $sindicato);
  $Result1 = mysql_query($deleteSQL, $sindicato) or die(mysql_error());

  $deleteGoTo = "frm_revisao_cadastro_socios.php?associado=" . $row_rs_socio['ass_cnpj'] . "&nro_socios=" . $row_rs_socio['ass_nro_socios'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.outra {
	font-family: Arial;
	font-size: 13px;
	color: #000;
}
.outra {
}
-->
</style></head>

<body>
<span class="outra"><?php echo $row_rs_socio['soc_id']; ?></span> - <span class="outra"><?php echo $row_rs_socio['soc_nome']; ?></span>
</body>
</html>
<?php
mysql_free_result($rs_socio);
?>
