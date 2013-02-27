<?php require_once('../Connections/sindicato.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_sindicato = new KT_connection($sindicato, $database_sindicato);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("ass_razao_social", true, "text", "", "", "", "");
$formValidation->addField("ass_cnpj", true, "text", "", "", "", "");
$formValidation->addField("ass_logradouro", true, "text", "", "", "", "");
$formValidation->addField("ass_complemento", true, "text", "", "", "", "");
$formValidation->addField("ass_bairro", true, "text", "", "", "", "");
$formValidation->addField("ass_municipio", true, "text", "", "", "", "");
$formValidation->addField("ass_uf", true, "text", "", "", "", "");
$formValidation->addField("ass_cep", true, "text", "", "", "", "");
$formValidation->addField("ass_telefone", true, "text", "", "", "", "");
$formValidation->addField("ass_email_empresa", true, "text", "", "", "", "");
$formValidation->addField("ass_nro_funcionarios", true, "numeric", "", "", "", "");
$formValidation->addField("ass_capital_social", true, "double", "", "", "", "");
$formValidation->addField("ass_optante_simples", true, "text", "", "", "", "");
$formValidation->addField("ass_nro_socios", true, "numeric", "", "", "", "");
$formValidation->addField("ass_ativ_principal", true, "text", "", "", "", "");
$formValidation->addField("ass_pessoa_contato", true, "text", "", "", "", "");
$formValidation->addField("ass_email_pessoa_contato", true, "text", "", "", "", "");
$formValidation->addField("ass_nome_admin_empresa", true, "text", "", "", "", "");
$formValidation->addField("ass_email_admin_empresa", true, "text", "", "", "", "");
$formValidation->addField("ass_telefone_admin_empresa", true, "text", "", "", "", "");
$formValidation->addField("ass_cadastro_ativo", true, "text", "", "", "", "");
$formValidation->addField("ass_pendente", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

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
$query_rs_municipio = "SELECT * FROM bl_municipio ORDER BY mun_nome";
$rs_municipio = mysql_query($query_rs_municipio, $sindicato) or die(mysql_error());
$row_rs_municipio = mysql_fetch_assoc($rs_municipio);
$totalRows_rs_municipio = mysql_num_rows($rs_municipio);

$nro_cnae_rs_cnae = "Nenhum";
if (isset($_POST['cnae'])) {
  $nro_cnae_rs_cnae = $_POST['cnae'];
}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_cnae = sprintf("SELECT * FROM bl_cnae WHERE bl_cnae.cnae_classe = %s", GetSQLValueString($nro_cnae_rs_cnae, "text"));
$rs_cnae = mysql_query($query_rs_cnae, $sindicato) or die(mysql_error());
$row_rs_cnae = mysql_fetch_assoc($rs_cnae);
$totalRows_rs_cnae = mysql_num_rows($rs_cnae);

// Make an insert transaction instance
$ins_bl_associado = new tNG_insert($conn_sindicato);
$tNGs->addTransaction($ins_bl_associado);
// Register triggers
$ins_bl_associado->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_bl_associado->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_bl_associado->registerTrigger("END", "Trigger_Default_Redirect", 99, "frm_cadastro_socios.php?associado={POST.ass_cnpj}&nro_socios={POST.ass_nro_socios}");
// Add columns
$ins_bl_associado->setTable("bl_associado");
$ins_bl_associado->addColumn("ass_razao_social", "STRING_TYPE", "POST", "ass_razao_social");
$ins_bl_associado->addColumn("ass_nome_fantasia", "STRING_TYPE", "POST", "ass_nome_fantasia");
$ins_bl_associado->addColumn("ass_cnpj", "STRING_TYPE", "POST", "ass_cnpj");
$ins_bl_associado->addColumn("ass_logradouro", "STRING_TYPE", "POST", "ass_logradouro");
$ins_bl_associado->addColumn("ass_numero", "NUMERIC_TYPE", "POST", "ass_numero");
$ins_bl_associado->addColumn("ass_complemento", "STRING_TYPE", "POST", "ass_complemento");
$ins_bl_associado->addColumn("ass_bairro", "STRING_TYPE", "POST", "ass_bairro");
$ins_bl_associado->addColumn("ass_municipio", "STRING_TYPE", "POST", "ass_municipio");
$ins_bl_associado->addColumn("ass_uf", "STRING_TYPE", "POST", "ass_uf");
$ins_bl_associado->addColumn("ass_cep", "STRING_TYPE", "POST", "ass_cep");
$ins_bl_associado->addColumn("ass_telefone", "STRING_TYPE", "POST", "ass_telefone");
$ins_bl_associado->addColumn("ass_fax", "STRING_TYPE", "POST", "ass_fax");
$ins_bl_associado->addColumn("ass_email_empresa", "STRING_TYPE", "POST", "ass_email_empresa");
$ins_bl_associado->addColumn("ass_site", "STRING_TYPE", "POST", "ass_site");
$ins_bl_associado->addColumn("ass_nro_funcionarios", "NUMERIC_TYPE", "POST", "ass_nro_funcionarios");
$ins_bl_associado->addColumn("ass_capital_social", "DOUBLE_TYPE", "POST", "ass_capital_social");
$ins_bl_associado->addColumn("ass_optante_simples", "STRING_TYPE", "POST", "ass_optante_simples");
$ins_bl_associado->addColumn("ass_nro_socios", "NUMERIC_TYPE", "POST", "ass_nro_socios");
$ins_bl_associado->addColumn("ass_ativ_principal", "STRING_TYPE", "POST", "ass_ativ_principal");
$ins_bl_associado->addColumn("ass_pessoa_contato", "STRING_TYPE", "POST", "ass_pessoa_contato");
$ins_bl_associado->addColumn("ass_email_pessoa_contato", "STRING_TYPE", "POST", "ass_email_pessoa_contato");
$ins_bl_associado->addColumn("ass_nome_admin_empresa", "STRING_TYPE", "POST", "ass_nome_admin_empresa");
$ins_bl_associado->addColumn("ass_email_admin_empresa", "STRING_TYPE", "POST", "ass_email_admin_empresa");
$ins_bl_associado->addColumn("ass_ci_admin_empresa", "NUMERIC_TYPE", "POST", "ass_ci_admin_empresa");
$ins_bl_associado->addColumn("ass_cpf_admin_empresa", "STRING_TYPE", "POST", "ass_cpf_admin_empresa");
$ins_bl_associado->addColumn("ass_telefone_admin_empresa", "STRING_TYPE", "POST", "ass_telefone_admin_empresa");
$ins_bl_associado->addColumn("ass_cadastro_ativo", "STRING_TYPE", "POST", "ass_cadastro_ativo", "N");
$ins_bl_associado->addColumn("ass_pendente", "STRING_TYPE", "POST", "ass_pendente", "S");
$ins_bl_associado->setPrimaryKey("ass_id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsbl_associado = $tNGs->getRecordset("bl_associado");
$row_rsbl_associado = mysql_fetch_assoc($rsbl_associado);
$totalRows_rsbl_associado = mysql_num_rows($rsbl_associado);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<link rel="stylesheet" href="../style/template.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/MaskedInput.js"></script>
<style type="text/css">
<!--
#form1 #table #button2 tr .form_validation_field_error_container label {
	color: #2B617D;
}
#titulos {
	color: #0B1862;
}
.letra {
	color: #1F497D;
}
.letra {
	color: #1F497D;
}
.letra {
	color: #1F497D;
}
.letraform {
	font-size: 14px;
}
.letra {
	color: #2B617D;
	font-family: Arial;
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<div id="titulos">
  Proposta de Cadastro de Associado<br/>
  Etapa 1/2 - Informa&ccedil;&otilde;es da Empresa
</div>
<p>&nbsp;</p>
<form id="form2" name="form2" method="post" action="">
  <label><span class="letra">Informe n&ordm; CNAE : </span>
    <input name="cnae" id="cnae" value="<?php echo $_POST['cnae'];?>" size="7" maxlength="7" wdg:subtype="MaskedInput" wdg:mask="99.99-9" wdg:restricttomask="no" wdg:type="widget" />
  </label>
</form>
<?php if (($totalRows_rs_cnae == 0)and ($_POST['cnae'] != "")) { // Show if recordset empty ?>
  <p class="letra">N&ordm; de CNAE inv&aacute;lido. Por favor, verifique as informa&ccedil;&otilde;es e tente novamente.</p>
  <?php } elseif ($_POST['cnae'] == ""){?>
  <p class="letra">Antes de iniciar, &eacute; necess&aacute;rio informar o n&ordm; do CNAE para consulta da Atividade Principal de sua empresa.</p> 
  <?php }// Show if recordset empty ?>
  
<?php if ($totalRows_rs_cnae > 0) { // Show if recordset not empty ?>
  <span class="letra"></span>
  <p><span class="letra">
    <?php
	echo $tNGs->getErrorMsg();
?>
  </span> </p>
  <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
    <div id="table">
      <table align="center" cellpadding="2" cellspacing="0" class="KT_topnav" id="button2">
        <tr>
          <th class="letraform"> <p>Empresa</p>
          </th>
        </tr>
        <tr>
          <td width="84" align="right" class="form_validation_field_error_container"><label for="ass_razao_social3">Razao Social:</label></td>
          <td width="225"><input name="ass_razao_social" type="text" class="mxw_add" id="ass_razao_social3" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_razao_social']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_razao_social");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_razao_social"); ?></td>
          <td width="84" align="right" class="form_validation_field_error_container"><label for="ass_nome_fantasia">Fantasia:</label></td>
          <td width="341"><input type="text" name="ass_nome_fantasia" id="ass_nome_fantasia" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_nome_fantasia']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_nome_fantasia");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_nome_fantasia"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_cnpj">CNPJ:</label></td>
          <td><span id="sprytextfield1">
            <input name="ass_cnpj" type="text" id="ass_cnpj" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_cnpj']); ?>" size="35" maxlength="14" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">CNPJ inv&aacute;lido.</span></span><?php echo $tNGs->displayFieldHint("ass_cnpj");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_cnpj"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_logradouro29">Logradouro:</label></td>
          <td><input type="text" name="ass_logradouro" id="ass_logradouro" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_logradouro']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_logradouro");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_logradouro"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_numero">Número:</label></td>
          <td><input type="text" name="ass_numero" id="ass_numero" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_numero']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_numero");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_numero"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_complemento">Complemento:</label></td>
          <td><input type="text" name="ass_complemento" id="ass_complemento" value="sem complemento" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_complemento");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_complemento"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_bairro">Bairro:</label></td>
          <td><input type="text" name="ass_bairro" id="ass_bairro" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_bairro']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_bairro");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_bairro"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_municipio">Município:</label></td>
          <td><select name="ass_municipio" class="textfieldRequiredState" id="ass_municipio">
            <option value="" <?php if (!(strcmp("", $row_rsbl_associado['ass_municipio']))) {echo "selected=\"selected\"";} ?>>Selecione</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rs_municipio['mun_nome']?>"<?php if (!(strcmp($row_rs_municipio['mun_nome'], $row_rsbl_associado['ass_municipio']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_municipio['mun_nome']?></option>
            <?php
} while ($row_rs_municipio = mysql_fetch_assoc($rs_municipio));
  $rows = mysql_num_rows($rs_municipio);
  if($rows > 0) {
      mysql_data_seek($rs_municipio, 0);
	  $row_rs_municipio = mysql_fetch_assoc($rs_municipio);
  }
?>
            </select>
            <?php echo $tNGs->displayFieldError("bl_associado", "ass_municipio"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_uf">UF:</label></td>
          <td><input name="ass_uf" type="text" id="ass_uf" value="ES" size="35" maxlength="2" />
            <?php echo $tNGs->displayFieldHint("ass_uf");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_uf"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_cep">CEP:</label></td>
          <td><input name="ass_cep" id="ass_cep" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_cep']); ?>" size="35" maxlength="10" wdg:subtype="MaskedInput" wdg:mask="99.999-999" wdg:restricttomask="yes" wdg:type="widget" />
            <?php echo $tNGs->displayFieldHint("ass_cep");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_cep"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_telefone">Telefone:</label></td>
          <td><input name="ass_telefone" id="ass_telefone" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_telefone']); ?>" size="35" maxlength="13" wdg:subtype="MaskedInput" wdg:mask="(99)9999-9999" wdg:restricttomask="yes" wdg:type="widget" />
            <?php echo $tNGs->displayFieldHint("ass_telefone");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_telefone"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_fax">Fax:</label></td>
          <td><input name="ass_fax" id="ass_fax" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_fax']); ?>" size="35" maxlength="13" wdg:subtype="MaskedInput" wdg:mask="(99)9999-9999" wdg:restricttomask="yes" wdg:type="widget" />
            <?php echo $tNGs->displayFieldHint("ass_fax");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_fax"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_email_empresa">E-mail da Empresa:</label></td>
          <td><span id="sprytextfield2">
            <input type="text" name="ass_email_empresa" id="ass_email_empresa" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_email_empresa']); ?>" size="35" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">E-mail inv&aacute;lido.</span></span><?php echo $tNGs->displayFieldHint("ass_email_empresa");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_email_empresa"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_site">Site:</label></td>
          <td><input type="text" name="ass_site" id="ass_site" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_site']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_site");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_site"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_nro_funcionarios">Número de Funcionários:</label></td>
          <td><input type="text" name="ass_nro_funcionarios" id="ass_nro_funcionarios" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_nro_funcionarios']); ?>" size="4" />
            <?php echo $tNGs->displayFieldHint("ass_nro_funcionarios");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_nro_funcionarios"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_capital_social">Capital Social:</label></td>
          <td><input type="text" name="ass_capital_social" id="ass_capital_social" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_capital_social']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_capital_social");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_capital_social"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_optante_simples_1">Optante Simples Nacional:</label></td>
          <td><div>
            <input name="ass_optante_simples" type="radio" class="selected_cal" id="ass_optante_simples_1" value="S" <?php if (!(strcmp(KT_escapeAttribute($row_rsbl_associado['ass_optante_simples']),"S"))) {echo "@@checked@@";} ?> />
            <label for="ass_optante_simples_1">Sim</label>
            </div>
            <div>
              <input name="ass_optante_simples" type="radio" class="selected_cal" id="ass_optante_simples_2" value="N" <?php if (!(strcmp(KT_escapeAttribute($row_rsbl_associado['ass_optante_simples']),"N"))) {echo "@@checked@@";} ?> />
              <label for="ass_optante_simples_2">Nao</label>
            </div>
            <?php echo $tNGs->displayFieldError("bl_associado", "ass_optante_simples"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_nro_socios">Número de Sócios:</label></td>
          <td><input type="text" name="ass_nro_socios" id="ass_nro_socios" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_nro_socios']); ?>" size="4" />
            <?php echo $tNGs->displayFieldHint("ass_nro_socios");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_nro_socios"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_ativ_principal">Atividade Principal:</label></td>
          <th><input name="ass_ativ_principal" type="text" id="ass_ativ_principal" value="<?php echo $_POST['cnae'];?>" size="7" maxlength="7" readonly="readonly" />
          <?php echo $row_rs_cnae['cnae_denominacao'];?><?php echo $tNGs->displayFieldHint("ass_ativ_principal");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_ativ_principal"); ?></td>
        </tr>
        <tr>
          <th class="letraform"><p> Contato </p></th>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_pessoa_contato">Nome :</label></td>
          <td><input type="text" name="ass_pessoa_contato" id="ass_pessoa_contato" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_pessoa_contato']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_pessoa_contato");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_pessoa_contato"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_email_pessoa_contato">E-mail:</label></td>
          <td><span id="sprytextfield">
            <input type="text" name="ass_email_pessoa_contato" id="ass_email_pessoa_contato" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_email_pessoa_contato']); ?>" size="35" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">E-mail inv&aacute;lido.</span></span><?php echo $tNGs->displayFieldHint("ass_email_pessoa_contato");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_email_pessoa_contato"); ?></td>
        </tr>
        <tr>
          <th class="letraform"><p>Administrador</p></th>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_nome_admin_empresa">Nome:</label></td>
          <td><input type="text" name="ass_nome_admin_empresa" id="ass_nome_admin_empresa" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_nome_admin_empresa']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_nome_admin_empresa");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_nome_admin_empresa"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_email_admin_empresa">E-mail:</label></td>
          <td><span id="sprytextfield3">
            <input type="text" name="ass_email_admin_empresa" id="ass_email_admin_empresa" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_email_admin_empresa']); ?>" size="35" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">E-mail inv&aacute;lido</span></span><?php echo $tNGs->displayFieldHint("ass_email_admin_empresa");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_email_admin_empresa"); ?></td>
        </tr>
        <tr>
          <td align="right" class="form_validation_field_error_container"><label for="ass_ci_admin_empresa">CI :</label></td>
          <td><input type="text" name="ass_ci_admin_empresa2" id="ass_ci_admin_empresa" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_ci_admin_empresa']); ?>" size="35" />
            <?php echo $tNGs->displayFieldHint("ass_ci_admin_empresa");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_ci_admin_empresa"); ?></td>
          <td align="right" class="form_validation_field_error_container"><label for="ass_cpf_admin_empresa">CPF:</label></td>
          <td><span id="sprytextfield2">
            <input type="text" name="ass_cpf_admin_empresa2" id="ass_cpf_admin_empresa" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_cpf_admin_empresa']); ?>" size="35" />
            <span class="textfieldInvalidFormatMsg">CPF inv&aacute;lido.</span></span><?php echo $tNGs->displayFieldHint("ass_cpf_admin_empresa");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_cpf_admin_empresa"); ?></td>
          <tr>
            <td align="right" class="form_validation_field_error_container"><label for="ass_telefone_admin_empresa">Telefoner:</label></td>
            <td><input name="ass_telefone_admin_empresa2" id="ass_telefone_admin_empresa" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_telefone_admin_empresa']); ?>" size="35" wdg:subtype="MaskedInput" wdg:mask="(99)9999-9999" wdg:restricttomask="yes" wdg:type="widget" />
            <?php echo $tNGs->displayFieldHint("ass_telefone_admin_empresa");?> <?php echo $tNGs->displayFieldError("bl_associado", "ass_telefone_admin_empresa"); ?></td>
        </tr>
        <tr></tr>
        <tr align="right" class="form_validation_field_error_container">
          <td height="86" colspan="4"><center>
            <input name="KT_Insert1" type="submit" class="KT_topnav" id="KT_Insert1" value="Enviar informa&ccedil;&otilde;es" />
          </center></td>
        </tr>
      </table>
    </div>
    <input type="hidden" name="ass_cadastro_ativo" id="ass_cadastro_ativo" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_cadastro_ativo']); ?>" />
    <input type="hidden" name="ass_pendente" id="ass_pendente" value="<?php echo KT_escapeAttribute($row_rsbl_associado['ass_pendente']); ?>" />
  </form>
  <?php } // Show if recordset not empty ?>
<script type="text/javascript">
<!--
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email", {validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "email", {validateOn:["change"]});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($rs_municipio);

mysql_free_result($rs_cnae);
?>
