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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO bl_socios (ass_id, soc_nome, soc_telefone, soc_email, soc_ativo) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ass_id'], "int"),
                       GetSQLValueString($_POST['soc_nome'], "text"),
                       GetSQLValueString($_POST['soc_telefone'], "text"),
                       GetSQLValueString($_POST['soc_email'], "text"),
                       GetSQLValueString($_POST['soc_ativo'], "text"));

  mysql_select_db($database_sindicato, $sindicato);
  $Result1 = mysql_query($insertSQL, $sindicato) or die(mysql_error());

  $insertGoTo = "frm_revisao_cadastro_socios.php?associado=" . $row_rs_associado['ass_cnpj'] . "&nro_socios=" . $row_rs_associado['ass_nro_socios'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$associado_rs_associado = "Nenhum";
if (isset($_GET['associado'])) {
  $associado_rs_associado = $_GET['associado'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_associado = sprintf("SELECT    * FROM    bl_associado WHERE    ass_cnpj = %s", GetSQLValueString($associado_rs_associado, "text"));
$rs_associado = mysql_query($query_rs_associado, $sindicato) or die(mysql_error());
$row_rs_associado = mysql_fetch_assoc($rs_associado);
$totalRows_rs_associado = mysql_num_rows($rs_associado);

$associado_rs_socios = "Nenhum";
if (isset($_GET['associado'])) {
  $associado_rs_socios = $_GET['associado'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_socios = sprintf("SELECT bl_socios.*,    bl_associado.* FROM bl_socios INNER JOIN bl_associado ON bl_associado.ass_id = bl_socios.ass_id WHERE bl_associado.ass_cnpj = %s ORDER BY bl_socios.soc_nome", GetSQLValueString($associado_rs_socios, "text"));
$rs_socios = mysql_query($query_rs_socios, $sindicato) or die(mysql_error());
$row_rs_socios = mysql_fetch_assoc($rs_socios);
$totalRows_rs_socios = mysql_num_rows($rs_socios);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
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
	font-size: 14px;
	font-weight: bold;
	color: #003;
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
.outro {
	font-family: Arial;
	font-size: 13px;
	color: #000;
}
.outro {
	font-family: Arial;
	font-size: 13px;
	color: #000;
}
.outro {
	font-family: Arial;
	font-size: 13px;
	color: #000;
}
.outro {
	font-family: Arial;
	font-size: 13px;
	color: #000;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
}
.outra strong {
	font-family: Arial;
	font-size: 14px;
	color: #003;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
}
.letra {
}
.letra {
	font-family: Arial;
	color: #2B617D;
}
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
<p align="center" class="letra"><strong>Proposta de Cadastro de Associado</strong></p>
<p align="center" class="letra"><strong>Etapa 2/2</strong> - <strong>Cadastro de S&oacute;cios da Empresa</strong></p>
<p>&nbsp;</p>
<p class="outro"><?php #echo $_GET['nro_socios'];?></p>
<p class="outro"><?php #echo $_GET['associado'];?></p>

<?php if ($totalRows_rs_associado > 0) { // Show if recordset not empty ?>
  <p><span class="outro"><?php echo $row_rs_associado['ass_cnpj']; ?></span> - <span class="outro"><?php echo $row_rs_associado['ass_razao_social']; ?></span></p>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>


<?php if ($totalRows_rs_socios < $row_rs_associado['ass_nro_socios']) { // mostra formulario se nro socios cadastrados for menor que o declardo no formulário anterior ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><span class="letra">Nome</span>:</td>
      <td><input type="text" name="soc_nome" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Telefone:</td>
      <td><input name="soc_telefone" id="soc_telefone" value="" size="32" wdg:subtype="MaskedInput" wdg:mask="(99)9999-9999" wdg:restricttomask="yes" wdg:type="widget" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">E-mail:</td>
      <td><span id="sprytextfield1">
      <input type="text" name="soc_email" value="" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">E-mail inválido</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Cadastrar s&oacute;cio" /></td>
    </tr>
  </table>
  <input type="hidden" name="ass_id" value="<?php echo $row_rs_associado['ass_id']; ?>" />
  <input type="hidden" name="soc_ativo" value="S" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<?php } // termino da condicao menor ou igual ?>



<p>&nbsp;</p>
<?php if ($totalRows_rs_socios > 0) { // Show if recordset not empty ?>
  <p align="center" class="outra"><strong>Rela&ccedil;&atilde;o de S&oacute;cios Cadastrados</strong></p>
  <table width="100%" border="1">
    <tr>
      <td class="letra">Nome</td>
      <td class="letra">Telefone</td>
      <td colspan="2" class="letra">A&ccedil;&atilde;o</td>
    </tr>
    <?php do { ?>
    <tr>
      <td class="outra"><?php echo $row_rs_socios['soc_nome']; ?></td>
      <td class="outra"><?php echo $row_rs_socios['soc_telefone']; ?></td>
      <td><form id="form2" name="form2" method="post" action="../visitante/frm_revisao_cadastro_socios_rev.php">
        <label>
          <input type="hidden" name="socio" id="socio" value="<?php echo $row_rs_socios['soc_id']; ?>" />
          <input type="hidden" name="ass_id2" value="<?php echo $row_rs_socios['ass_id']; ?>" />
          <input type="hidden" name="associado" value="<?php echo $row_rs_socios['ass_cnpj']; ?>" />
          <input type="submit" name="alterar" id="alterar" value="Alterar" />
        </label>
      </form></td>
      <td><form id="form2" name="form2" method="post" action="frm_revisao_deleta_socio.php?socio=<?php echo $row_rs_socios['soc_id']; ?>">
        <label>
          <input type="submit" name="delete" id="delete" value="Excluir" />
        </label>
      </form></td>
    </tr>
    <?php } while ($row_rs_socios = mysql_fetch_assoc($rs_socios)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<?php if ($totalRows_rs_socios > 0) { // Show if recordset not empty ?>
  <form id="form3" name="form3" method="post" action="frm_confirma_cadastro_associado.php?proposta=<?php echo $row_rs_associado['ass_id']; ?>">
    <label>
      <input type="submit" name="finalizar" id="finalizar" value="Finalizar proposta de Cadastro" />
    </label>
  </form>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["change"]});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($rs_associado);

mysql_free_result($rs_socios);
?>
