<?php require_once('../Connections/sindicato.php'); ?>
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

mysql_select_db($database_sindicato, $sindicato);
$query_rs_entidade = "SELECT * FROM bl_entidade";
$rs_entidade = mysql_query($query_rs_entidade, $sindicato) or die(mysql_error());
$row_rs_entidade = mysql_fetch_assoc($rs_entidade);
$totalRows_rs_entidade = mysql_num_rows($rs_entidade);

$certidao_rs_certidao = "-1";
if (isset($_GET['certidao'])) {
  $certidao_rs_certidao = $_GET['certidao'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_certidao = sprintf("SELECT    bl_associado.*,       bl_certidao.*,    YEAR(cer_data_validade),    MONTH(cer_data_validade),    DAY(cer_data_validade) FROM    bl_certidao INNER JOIN bl_associado ON bl_associado.ass_id = bl_certidao.ass_id WHERE    bl_certidao.cer_id = %s", GetSQLValueString($certidao_rs_certidao, "int"));
$rs_certidao = mysql_query($query_rs_certidao, $sindicato) or die(mysql_error());
$row_rs_certidao = mysql_fetch_assoc($rs_certidao);
$totalRows_rs_certidao = mysql_num_rows($rs_certidao);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../site/templates/sindicatos/css/viewimprimircer.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../site/templates/sindicatos/css/imprimircer.css" type="text/css" media="print" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body p {
	font-family: Arial;
	font-size: 20px;
	color: #000;
	line-height: 8mm;
}
#table p strong {
	color: #000;
	font-size: 20px;
}
#geral #table #cabecalho tr td h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	text-align: center;
}
#geral #cabecalho #cabec tr td h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
}
#geral #foot {
	text-align: center;
}
-->
</style></head>
<body>
<div id="geral">
<div id="conteudo">
<div id="cabecalho">
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="cabec">
          <tr>
            <td width="18%"><img src="../site/templates/sindicatos/images/logomarcaprint.png" alt="LogoBL" width="166" height="94" align="left" /></td>
            <td width="82%" align="center" valign="middle"><h1>Sindicato das Empresas de Inform&aacute;tica no Estado do Esp&iacute;rito Santo</h1></td>
          </tr>
    </table>
  </div>
  <p align="center">&nbsp;</p>
  <p align="center">&nbsp;</p>
  <p align="center"><strong>DECLARA&Ccedil;&Atilde;O DE EXCLUSIVIDADE E SEM SIMILARIDADE</strong></p>
<p align="center"><strong><?php echo $row_rs_certidao['cer_sindicato_codigo']; ?><?php printf("%04d",$row_rs_certidao['YEAR(cer_data_validade)']); ?><?php printf("%02d",$row_rs_certidao['MONTH(cer_data_validade)']); ?><?php printf("%02d",$row_rs_certidao['DAY(cer_data_validade)']); ?><?php printf("%04d",$row_rs_certidao['cer_id']); ?></strong></p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<p align="justify"><strong><?php echo $row_rs_entidade['ent_sigla']; ?> - <?php echo $row_rs_entidade['ent_razao_social']; ?></strong>, com sede na <?php echo $row_rs_entidade['ent_logradouro']; ?>, <?php echo $row_rs_entidade['ent_numero']; ?>, <?php echo $row_rs_entidade['ent_complemento']; ?>, Bairro <?php echo $row_rs_entidade['ent_bairro']; ?>, <?php echo $row_rs_entidade['ent_municipio']; ?>-<?php echo $row_rs_entidade['ent_uf']; ?>, declara para efeitos de comprova&ccedil;&atilde;o junto aos &oacute;rg&atilde;os governamentais, empresas p&uacute;blicas, privadas e de economia mista, que a empresa <strong><?php echo $row_rs_certidao['ass_razao_social']; ?></strong>, com sede na <?php echo $row_rs_certidao['ass_logradouro']; ?>, <?php echo $row_rs_certidao['ass_numero']; ?>, <?php echo $row_rs_certidao['ass_complemento']; ?>,  <?php echo $row_rs_certidao['ass_bairro']; ?>, <?php echo $row_rs_certidao['ass_municipio']; ?>-<?php echo $row_rs_certidao['ass_uf']; ?>, inscrita no CNPJ sob o n&deg;. <?php echo $row_rs_certidao['ass_cnpj']; ?>, &eacute; a desenvolvedora e &uacute;nica detentora dos direitos patrimoniais com exclusividade e sem similaridade, al&eacute;m de &uacute;nica empresa autorizada e capacitada a prover licen&ccedil;as de uso, suporte t&eacute;cnico e manuten&ccedil;&atilde;o do <strong><?php echo $row_rs_certidao['cer_prod_unico']; ?></strong><?php if ($row_rs_certidao['cer_prod_inf_adcionais'] <> NULL) {echo ", que &eacute; parte integrante do(a) ";}?><strong><?php echo $row_rs_certidao['cer_prod_inf_adcionais']; ?></strong>, conforme documenta&ccedil;&atilde;o apresentada e mantida nos arquivos do <?php echo $row_rs_entidade['ent_razao_social']; ?> - <?php echo $row_rs_entidade['ent_sigla']; ?>.</p>

<p align="justify">De acordo com o <strong>Artigo 25 da Lei 8.666, de 21/06/93</strong>, o <?php echo $row_rs_entidade['ent_sigla']; ?> tem compet&ecirc;ncia para emiss&atilde;o deste tipo de declara&ccedil;&atilde;o, sendo a mesma v&aacute;lida para todo territ&oacute;rio nacional.</p>
<p align="justify">A aceitac&atilde;o desta certid&atilde;o est&aacute; condicionada &agrave; verificac&atilde;o de sua autenticidade na Internet, nos endere&ccedil;os http:/www.sindinfo.com.br/portal/index.php/associadas/validar-declaracao-de-exclusividade</p>
<p align="justify">Declara&ccedil;&atilde;o v&aacute;lida at&eacute;: <?php echo KT_FormatDate($row_rs_certidao['cer_data_validade']); ?>.</p>
<p align="justify"><?php echo $row_rs_certidao['ass_municipio']; ?>-<?php echo $row_rs_certidao['ass_uf']; ?>, <?php setlocale(LC_ALL, 'pt_BR');echo strftime("%A, %d de %B de %Y", strtotime($row_rs_certidao['cer_data_emissao'])); ?>.</p>
<p align="justify">&nbsp;</p>
<p align="justify">&nbsp;</p>
<p align="center"><strong><?php echo $row_rs_entidade['ent_nome_presidente']; ?></strong></p>
<p align="center">Presidente</p>
<div id="foot">Av. Nossa Senhora da Penha, 2053 - Findes - B. Santa Lúcia - Tel.(027) 3334 5686 - 3334 5690 - Fax 3225 1833 Vitoria-ES-CEP 29045-401
</div>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_entidade);

mysql_free_result($rs_certidao);
?>
