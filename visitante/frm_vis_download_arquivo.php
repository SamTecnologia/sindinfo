<?php require_once('../Connections/sindicato.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

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

$nro_cnpj_rs_boleto = "Nenhum";
if (isset($_GET['cnpj'])) {
  $nro_cnpj_rs_boleto = $_GET['cnpj'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_boleto = sprintf("SELECT bl_boleto . * , bl_associado . * FROM bl_boleto INNER JOIN bl_associado ON bl_associado.ass_id = bl_boleto.ass_id WHERE bl_associado.ass_cnpj =%s", GetSQLValueString($nro_cnpj_rs_boleto, "text"));
$rs_boleto = mysql_query($query_rs_boleto, $sindicato) or die(mysql_error());
$row_rs_boleto = mysql_fetch_assoc($rs_boleto);
$totalRows_rs_boleto = mysql_num_rows($rs_boleto);

// Download File downloadObj1
$downloadObj1 = new tNG_Download("../", "KT_download1");
$downloadObj1->setConnection($conn_sindicato, "sindicato");
// Download Counter
$downloadObj1->setTable("bl_boleto");
$downloadObj1->setPrimaryKey("bol_id", "NUMERIC_TYPE", "{rs_boleto.bol_id}");
$downloadObj1->setCounterField("contar");
// Execute
$downloadObj1->setFolder("../boletos/{rs_boleto.ass_id}/");
$downloadObj1->setRenameRule("{rs_boleto.bol_endereco}");
$downloadObj1->Execute();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.outro {
	font-family: Arial;
	font-size: 13px;
	color: #000;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
	font-weight: bold;
}
.title2 {
	font-family: Arial;
	font-size: 14px;
	color: #003;
}
.title2 strong {
	font-weight: bold;
}
-->
</style></head>

<body>

<p align="center" class="title2"><strong>Download de Boletos</strong></p>
<p>&nbsp;</p>
<?php if ($totalRows_rs_boleto > 0) { // Show if recordset not empty ?>
  <p><span class="outro"><?php echo $row_rs_boleto['ass_cnpj']; ?></span> - <span class="outro"><?php echo $row_rs_boleto['ass_razao_social']; ?></span></p>
  <table width="100%" border="1">
    <tr>
      <td><strong class="letra">T&iacute;tulo</strong></td>
      <td><strong class="letra">Arquivo para Download</strong></td>
      <td><strong class="letra">Downloads Realizados</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td class="outro"><?php echo $row_rs_boleto['bol_titulo']; ?></td>
        <td><a href="<?php echo $downloadObj1->getDownloadLink(); ?>"><?php echo $row_rs_boleto['bol_endereco']; ?></a></td>
        <td class="outro"><?php echo $row_rs_boleto['contar']; ?></td>
      </tr>
      <?php } while ($row_rs_boleto = mysql_fetch_assoc($rs_boleto)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rs_boleto == 0) { // Show if recordset empty ?>
  <p class="outro">Nenhum boleto dispon&iacute;vel. Por favor, verifique as informa&ccedil;&otilde;es e tente novamente.</p>
  <?php } // Show if recordset empty ?>
<br/>
  <form id="form2" name="form2" method="post" action="frm_vis_consulta_download_arquivo.php">
    <label>
     <center> <input name="voltar" type=image  class="KT_topnav" id="voltar2" src="../images/voltar.png" /> </center>
    </label>
  </form>
</body>
</html>
<?php
mysql_free_result($rs_boleto);
?>
