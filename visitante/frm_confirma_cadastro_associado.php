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

$proposta_rs_proposta = "-1";
if (isset($_GET['proposta'])) {
  $proposta_rs_proposta = $_GET['proposta'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_proposta = sprintf("SELECT    bl_socios.*,    bl_associado.* FROM    bl_socios INNER JOIN bl_associado ON bl_associado.ass_id = bl_socios.ass_id WHERE    bl_associado.ass_id = %s ORDER BY    bl_socios.soc_nome", GetSQLValueString($proposta_rs_proposta, "int"));
$rs_proposta = mysql_query($query_rs_proposta, $sindicato) or die(mysql_error());
$row_rs_proposta = mysql_fetch_assoc($rs_proposta);
$totalRows_rs_proposta = mysql_num_rows($rs_proposta);

$proposta = $row_rs_proposta['ass_id'];
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
	color: #000;
}
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
	font-size: 13px;
	color: #2B617D;
}
.letra {
}
.letra {
}
.outra {
	font-family: Arial;
	color: #000;
	font-size: 13px;
}
-->
</style></head>

<body>
<table border="1">
  <tr>
    <td><p><span class="letra">N&deg; da Proposta:</span> <span class="outra"><?php echo $row_rs_proposta['ass_id']; ?></span></p>
    <p class="letra">CNPJ: <span class="outra"><?php echo $row_rs_proposta['ass_cnpj']; ?></span></p>
    <p><span class="letra">Empresa:</span> <span class="outra"><?php echo $row_rs_proposta['ass_razao_social']; ?></span></p>
    <p><span class="letra">Nome de Fantasia:</span> <span class="outra"><?php echo $row_rs_proposta['ass_nome_fantasia']; ?></span>)</p>
    <p><span class="letra">Atividade Principal:</span> <span class="outra"><?php echo $row_rs_proposta['ass_ativ_principal']; ?></span> </p>
    <p><span class="letra">Empresa Optante do Simples Nacional: </span><span class="outra"><?php echo $row_rs_proposta['ass_optante_simples']; ?></span></p>
    <p><span class="letra">Endere&ccedil;o:</span> <span class="outra"><?php echo $row_rs_proposta['ass_logradouro']; ?></span>, n&ordm;. <span class="outra"><?php echo $row_rs_proposta['ass_numero']; ?></span>, <span class="outra"><?php echo $row_rs_proposta['ass_complemento']; ?></span>, <span class="outra"><?php echo $row_rs_proposta['ass_bairro']; ?></span>, <span class="outra"><?php echo $row_rs_proposta['ass_municipio']; ?></span>-<span class="outra"><?php echo $row_rs_proposta['ass_uf']; ?></span>, CEP: <span class="outra"><?php echo $row_rs_proposta['ass_cep']; ?></span></p>
    <p><span class="letra">Telefone:</span> <span class="outra"><?php echo $row_rs_proposta['ass_telefone']; ?></span> / Fax: <span class="outra"><?php echo $row_rs_proposta['ass_fax']; ?></span> / E-mail: <span class="outra"><?php echo $row_rs_proposta['ass_email_empresa']; ?></span> /  Site: <span class="outra"><?php echo $row_rs_proposta['ass_site']; ?></span> </p>
    <p><span class="letra">Capital Social:</span> R$<span class="outra"><?php echo $row_rs_proposta['ass_capital_social']; ?></span> </p>
    <p> <span class="letra">N&uacute;mero de Funcion&aacute;rios:</span> <span class="outra"><?php echo $row_rs_proposta['ass_nro_funcionarios']; ?></span></p>
    <p><span class="letra">Contato: </span><span class="outra"><?php echo $row_rs_proposta['ass_pessoa_contato']; ?></span>/<span class="outra"><?php echo $row_rs_proposta['ass_email_pessoa_contato']; ?></span></p>
    <p><span class="letra">Administrador:</span> <span class="outra"><?php echo $row_rs_proposta['ass_nome_admin_empresa']; ?></span> CI: <span class="outra"><?php echo $row_rs_proposta['ass_ci_admin_empresa']; ?></span> CPF: <span class="outra"><?php echo $row_rs_proposta['ass_cpf_admin_empresa']; ?></span></p>
    <p><span class="letra">Telefone:</span> <span class="outra"><?php echo $row_rs_proposta['ass_telefone_admin_empresa']; ?></span> E-mail: <span class="outra"><?php echo $row_rs_proposta['ass_email_admin_empresa']; ?></span></p>
    <p><span class="letra">N&uacute;mero de S&oacute;cios:</span> <span class="outra"><?php echo $row_rs_proposta['ass_nro_socios']; ?></span></p>
    <?php do { ?>
      <p><span class="outra"><?php echo $row_rs_proposta['soc_nome']; ?></span>: <span class="outra"><?php echo $row_rs_proposta['soc_telefone']; ?></span>/<span class="outra"><?php echo $row_rs_proposta['soc_email']; ?></span></p>
    <?php } while ($row_rs_proposta = mysql_fetch_assoc($rs_proposta)); ?></td>
  </tr>
</table>
<p>&nbsp;</p>
<form id="form1" name="form1" method="get" action="../visitante/frm_revisao_cadastro_associado.php">
  <input type="hidden" name="ass_id" id="ass_id" value="<?php  echo $proposta;?>"/>
  <label>
    <input type="submit" name="revisao" id="revisao" value="Revisar informa&ccedil;&otilde;es" />
  </label>
</form>
<p>&nbsp; </p>
<form id="form2" name="form2" method="post" action="../visitante/frm_vis_mensagem_finaliza_cadastro.php">
  <label>
    <input type="submit" name="finalizar" id="finalizar" value="Finalizar" />
  </label>
</form>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($rs_proposta);
?>
