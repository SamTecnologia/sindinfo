<?php require_once('../Connections/sindicato.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');
?>
<?php require_once('../includes/common/KT_common.php');?>

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

#inicio da peparacao dos dados para a query
$nro_cnpj = $_POST['cnpj'];
$texto = $_POST['certidao'];
$sind = substr($texto,0,2);
$validade = substr($texto,2,8);
$validade_final = substr($validade,0,4) . '-' . substr($validade,4,2) . '-' . substr($validade,6,2);
$nro_certidao = substr($texto,10,4);
$cnpj_final = substr($nro_cnpj,0,2) . '.' . substr($nro_cnpj,2,3) . '.' . substr($nro_cnpj,5,3) . '/' . substr($nro_cnpj,8,4) . '-' . substr($nro_cnpj,12,2);
# echo '/';
# echo $sind;
# echo $validade; 
# echo $validade_final; 
# echo $nro_certidao;
# echo $cnpj_final;

$sindicato_rs_certidao = "-1";
if (isset($sind)) {
  $sindicato_rs_certidao = $sind;
}
$validade_rs_certidao = "Nenhum";
if (isset($validade_final)) {
  $validade_rs_certidao = $validade_final;
}
$certidao_rs_certidao = "-1";
if (isset($nro_certidao)) {
  $certidao_rs_certidao = $nro_certidao;
}
$associado_rs_certidao = "Nenhum";
if (isset($nro_cnpj)) {
  $associado_rs_certidao = $nro_cnpj;
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_certidao = sprintf("SELECT    bl_certidao.*,    bl_associado.* FROM    bl_certidao INNER JOIN bl_associado ON bl_associado.ass_id = bl_certidao.ass_id WHERE    bl_certidao.cer_sindicato_codigo = %s AND    bl_certidao.cer_data_validade = %s AND    bl_certidao.cer_id = %s AND    bl_associado.ass_cnpj = %s", GetSQLValueString($sindicato_rs_certidao, "int"),GetSQLValueString($validade_rs_certidao, "text"),GetSQLValueString($certidao_rs_certidao, "int"),GetSQLValueString($associado_rs_certidao, "text"));
$rs_certidao = mysql_query($query_rs_certidao, $sindicato) or die(mysql_error());
$row_rs_certidao = mysql_fetch_assoc($rs_certidao);
$totalRows_rs_certidao = mysql_num_rows($rs_certidao);

mysql_select_db($database_sindicato, $sindicato);
$query_rs_entidade = "SELECT * FROM bl_entidade";
$rs_entidade = mysql_query($query_rs_entidade, $sindicato) or die(mysql_error());
$row_rs_entidade = mysql_fetch_assoc($rs_entidade);
$totalRows_rs_entidade = mysql_num_rows($rs_entidade);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
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
-->
</style></head>

<body>
<p></p>
</p>
<?php if ($totalRows_rs_certidao > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1">
    <tr>
      <td align="justify" class="letra">A<strong> DECLARA&Ccedil;&Atilde;O DE EXCLUSIVIDADE E SEM SIMILARIDADE </strong>n&deg;. <?php echo $row_rs_certidao['cer_sindicato_codigo']; ?><?php echo $validade; ?><?php printf("%04d",$row_rs_certidao['ass_id']); ?> foi emitida em favor de <?php echo $row_rs_certidao['ass_razao_social']; ?>, inscrita no CNPJ sob o n&deg;. <?php echo $cnpj_final ?> e &eacute; v&aacute;lida at&eacute; <?php echo KT_FormatDate($row_rs_certidao['cer_data_validade']); ?>. Clique <a href="frm_visualiza_certidao.php?certidao=<?php echo $row_rs_certidao['cer_id']; ?>" target="_blank">aqui</a> para visualizar a Declara&ccedil;&atilde;o.</td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<?php if ($totalRows_rs_certidao == 0) { // Show if recordset empty ?>
  <table width="100%" border="1">
    <tr>
      <td class="letra">Declara&ccedil;&atilde;o n&atilde;o encontrada. Por favor, verifique as informa&ccedil;&otilde;es fornecidas e tente novamente. Caso continue visualizando esta mensagem, para maiores esclarecimentos, entre em contato com o(a) Sr.(a) <strong><?php echo $row_rs_entidade['ent_pessoa_contato']; ?></strong> pelo telefone: <strong><?php echo $row_rs_entidade['ent_telefone']; ?></strong> ou pelo e-mail: <strong><?php echo $row_rs_entidade['ent_email_pessoa_contato']; ?>.</strong></td>
    </tr>
  </table>
  <?php } // Show if recordset empty ?>
<br/>
  <form id="form2" name="form2" method="post" action="frm_consulta_cnpj_certidao.php">
    <label>
     <center> <input name="voltar" type=image  class="KT_topnav" id="voltar2" src="../images/voltar.png" /> </center>
    </label>
  </form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_certidao);

mysql_free_result($rs_entidade);
?>
