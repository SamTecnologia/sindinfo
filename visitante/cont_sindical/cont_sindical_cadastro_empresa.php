<?php require_once('../../Connections/sindicato.php'); ?>
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

$empresa_rs_csind_empresa = "Nenhum";
if (isset($_POST['empresa'])) {
  $empresa_rs_csind_empresa = $_POST['empresa'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_csind_empresa = sprintf("SELECT * FROM bl_csind_empresa WHERE bl_csind_empresa.csind_emp_cnpj = %s", GetSQLValueString($empresa_rs_csind_empresa, "text"));
$rs_csind_empresa = mysql_query($query_rs_csind_empresa, $sindicato) or die(mysql_error());
$row_rs_csind_empresa = mysql_fetch_assoc($rs_csind_empresa);
$totalRows_rs_csind_empresa = mysql_num_rows($rs_csind_empresa);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rs_csind_empresa);
?>
