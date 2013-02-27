<?php require_once('../Connections/sindicato.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

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

$associado = $_POST['socio'];
$nro_socio_rs_socio = "-1";
if (isset($_POST['socio'])) {
  $nro_socio_rs_socio = $_POST['socio'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_socio = sprintf("SELECT bl_socios.*, bl_associado.* FROM bl_socios INNER JOIN bl_associado ON bl_associado.ass_id = bl_socios.ass_id WHERE bl_socios.soc_id = %s", GetSQLValueString($nro_socio_rs_socio, "int"));
$rs_socio = mysql_query($query_rs_socio, $sindicato) or die(mysql_error());
$row_rs_socio = mysql_fetch_assoc($rs_socio);
$totalRows_rs_socio = mysql_num_rows($rs_socio);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE bl_socios SET soc_nome=%s, soc_telefone=%s, soc_email=%s WHERE soc_id=%s",
                       GetSQLValueString($_POST['soc_nome'], "text"),
                       GetSQLValueString($_POST['soc_telefone'], "text"),
                       GetSQLValueString($_POST['soc_email'], "text"),
                       GetSQLValueString($_POST['soc_id'], "int"));

  mysql_select_db($database_sindicato, $sindicato);
  $Result1 = mysql_query($updateSQL, $sindicato) or die(mysql_error());

  $updateGoTo = "frm_revisao_cadastro_socios.php?associado=" . $_POST['associado'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/MaskedInput.js"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
}
.letra {
	font-family: Arial;
	color: #2B617D;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
}
#form1 table tr td {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
	font-weight: bold;
}
-->
</style></head>

<body>
<p><span class="letra"><?php echo $row_rs_socio['ass_cnpj']; ?></span> -<span class="letra"><?php echo $row_rs_socio['ass_razao_social']; ?></span></p>
<span class="letra">cnpj:</span> <span class="letra"><?php echo $_POST['associado'];?></span>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nome:</td>
      <td><input type="text" name="soc_nome" value="<?php echo htmlentities($row_rs_socio['soc_nome'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Telefone:</td>
      <td><input name="soc_telefone" id="soc_telefone" value="<?php echo htmlentities($row_rs_socio['soc_telefone'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" wdg:subtype="MaskedInput" wdg:mask="(99)9999-9999" wdg:restricttomask="yes" wdg:type="widget" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">E-mail:</td>
      <td>
      <input type="text" name="soc_email" value="<?php echo htmlentities($row_rs_socio['soc_email'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">E-mail inv&aacute;lido.</span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>
      	<input type="hidden" name="associado" value="<?php echo $row_rs_socio['ass_cnpj']; ?>" />
        <input type="hidden" name="ass_id" value="<?php echo $row_rs_socio['ass_id']; ?>" />
      	<input type="submit" value="Salvar Altera&ccedil;&otilde;es" />
       </td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="soc_id" value="<?php echo $row_rs_socio['soc_id']; ?>" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["change"]});
//-->
</script>
</body>
</html>
<?php
#mysql_free_result($rs_socio);

#mysql_free_result($rs_socio);
?>
