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

if($_POST['empresa'] != ""){$clausula = $_POST['empresa'];}

mysql_select_db($database_sindicato, $sindicato);
$query_rs_associados = "SELECT * FROM bl_associado WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_associado.ass_razao_social LIKE '%$clausula%' ORDER BY bl_associado.ass_razao_social ASC";
$rs_associados = mysql_query($query_rs_associados, $sindicato) or die(mysql_error());
$row_rs_associados = mysql_fetch_assoc($rs_associados);
$totalRows_rs_associados = mysql_num_rows($rs_associados);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="http://192.168.0.142/Joomla/templates/sindicatos/css/template.css" type="text/css" />
<link rel="stylesheet" href="../style/template.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.center {
	text-align: center;
}
.letra {
	color: #496E99;
	font-family: Arial;
	font-size: 14px;
}
.outra {
	color: #496E99;
}
-->
</style>
</head>
<body><span class="frames">
<!--<p align="center"><strong>Rela&ccedil;&atilde;o de Associados</strong></p>-->
</span>
<div id="consulta" align="right">
  <form id="form1" name="form1" method="post" action="">
    <label><span class="letra">Procurar por</span></label>
    <span class="letra">
    <label>:</label>
    </span>
    <label></label>
    <span class="frames">
    <label>
  <input name="empresa" type="text" id="empresa" value="<?php echo $_POST['empresa'];?>" />
    </label>
    <label>
      <input type="submit" name="procurar" id="procurar" value="Procurar" />
    </label>
    </span>
  </form>
</div>
<span class="frames">
<?php do { ?>
    <?php if ($totalRows_rs_associados > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0">
    <tr>
      <td width="23%" align="center" valign="middle"><?php if ($row_rs_associados['ass_logo'] != NULL) {?><img src="../produtos/<?php echo $row_rs_associados['ass_id']; ?>/logo/<?php echo $row_rs_associados['ass_logo']; ?>" /><?php } else {?><img src="../images/nenhuma.png"/><?php }?></td>
      <td width="77%" class="letra"><strong>
      <div class="outra" id="title1"><?php echo $row_rs_associados['ass_razao_social']; ?></div><div id="cor"><?php echo $row_rs_associados['ass_logradouro']; ?>, n&deg;. <?php echo $row_rs_associados['ass_numero']; ?>, <?php echo $row_rs_associados['ass_complemento']; ?>, <?php echo $row_rs_associados['ass_bairro']; ?>, <?php echo $row_rs_associados['ass_municipio']; ?>-<?php echo $row_rs_associados['ass_uf']; ?>, CEP: <?php echo $row_rs_associados['ass_cep']; ?><br />
        Sr(a).<?php echo $row_rs_associados['ass_pessoa_contato']; ?><br />
        Telefone: <?php echo $row_rs_associados['ass_telefone']; ?> / Fax: <?php echo $row_rs_associados['ass_fax']; ?><br />
        E-mail: <?php echo $row_rs_associados['ass_email_empresa']; ?></div>
        <strong><a href="http://<?php echo $row_rs_associados['ass_site']; ?>" target="_blank" class="title1"><?php echo $row_rs_associados['ass_site']; ?></a></strong></td>
        <br />
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<?php } while ($row_rs_associados = mysql_fetch_assoc($rs_associados)); ?>
</span>
<?php if ($totalRows_rs_associados == 0) { // Show if recordset empty ?>
  <strong class="bold"><span class="latestnews_usr1">Nenhum registro encontrado. Por favor verifique as informaçoes e tente novamente.</span></strong>
  <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($rs_associados);
?>
