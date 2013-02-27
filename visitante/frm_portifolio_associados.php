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


# preparacao da clausula WHERE

#s-s-s
if (($_POST['empresa'] != "") and ($_POST['tipo_produto'] != "") and ($_POST['municipio'] != ""))
	{
		$clausula =  "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_produto.ass_id =  " . $_POST['empresa'] . " AND bl_produto.tipo_prod_id = " . $_POST['tipo_produto'] . " AND bl_associado.ass_municipio LIKE '%" . $_POST['municipio'] . "%' ";
		#echo $clausula;
	}
#s-s-n
elseif(($_POST['empresa'] != "") and ($_POST['tipo_produto'] != "") and ($_POST['municipio'] == ""))
	{
				$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_produto.ass_id =  " . $_POST['empresa'] . " AND bl_produto.tipo_prod_id = " . $_POST['tipo_produto'] . " ";
				#echo $clausula;
	}
#s-n-s
elseif (($_POST['empresa'] != "") and ($_POST['tipo_produto'] == "") and ($_POST['municipio'] != ""))
	{
		$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_produto.ass_id =  " . $_POST['empresa'] . " AND bl_associado.ass_municipio LIKE '%" . $_POST['municipio'] . "%' ";
		#echo $clausula;
	}
#s-n-n
elseif (($_POST['empresa'] != "") and ($_POST['tipo_produto'] == "") and ($_POST['municipio'] == ""))
	{
		$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_produto.ass_id =  " . $_POST['empresa'] . " ";
		#echo $clausula;
	}
#n-s-s
elseif (($_POST['empresa'] == "") and ($_POST['tipo_produto'] != "") and ($_POST['municipio'] != ""))
	{
		$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_produto.tipo_prod_id = " . $_POST['tipo_produto'] . " AND bl_associado.ass_municipio LIKE '%" . $_POST['municipio'] . "%' ";
		#echo $clausula;
	}
#n-s-n
elseif (($_POST['empresa'] == "") and ($_POST['tipo_produto'] != "") and ($_POST['municipio'] == ""))
	{
		$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_produto.tipo_prod_id = " . $_POST['tipo_produto'] . " ";
		#echo $clausula;
	}
#n-n-s
elseif (($_POST['empresa'] == "") and ($_POST['tipo_produto'] == "") and ($_POST['municipio'] != ""))
	{
		$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S' AND bl_associado.ass_municipio LIKE '%" . $_POST['municipio'] . "%'";
		#echo $clausula;
	}
#n-n-n
elseif (($_POST['empresa'] == "") and ($_POST['tipo_produto'] == "") and ($_POST['municipio'] == ""))
	{
		$clausula = "WHERE bl_associado.ass_cadastro_ativo = 'S'";
	}
mysql_select_db($database_sindicato, $sindicato);
$query_rs_produtos = "SELECT bl_tipo_produto.* FROM bl_produto INNER JOIN bl_tipo_produto ON bl_tipo_produto.tipo_prod_id = bl_produto.tipo_prod_id INNER JOIN bl_associado ON bl_associado.ass_id = bl_produto.ass_id $clausula GROUP BY bl_tipo_produto.tipo_prod_id ORDER BY bl_tipo_produto.tipo_prod_descricao, bl_associado.ass_razao_social, bl_produto.prod_descricao";
$rs_produtos = mysql_query($query_rs_produtos, $sindicato) or die(mysql_error());
$row_rs_produtos = mysql_fetch_assoc($rs_produtos);
$totalRows_rs_produtos = mysql_num_rows($rs_produtos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.letra {
	color: #2B617D;
	font-family: Arial;
	font-size: 14px;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #006;
}
.letra {
	font-weight: bold;
	color: #2B617D;
}
.outro {
	font-family: Arial;
	font-size: 14px;
	color: #0B1862;
	font-weight: bold;
}
.outro {
	font-family: Arial;
	font-size: 13px;
	color: #2598D2;
}
.outro3 {
	font-family: Arial;
	font-size: 13px;
	color: #1D2550;
	font-weight: bold;
}
.message {
	color: #1F497d;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<form id="form2" name="form2" method="post" action="frm_consulta_dinamica_produto.php">
 <center> <input name="voltar" type=image  class="KT_topnav" id="voltar" value="Voltar" src="../images/voltar.png" /> </center>
</form>
<?php if ($totalRows_rs_produtos == 0) { // Show if recordset empty ?>
  <p class="message">Nenhuma informa&ccedil;&atilde;o dispon&iacute;vel.</p>
  <?php } // Show if recordset empty ?>
<?php do { ?>
  <table width="100%">
    <tr>
      <td height="45" colspan="2" class="outro3"><?php echo $row_rs_produtos['tipo_prod_descricao']; ?></td>
    </tr>
    
    		<?php
            //inicio construcao clausua busca associado-municipio
			
			//n-n
            if (($_POST['empresa'] == "") and ($_POST['municipio'] == "")){$aux = 'WHERE bl_associado.ass_cadastro_ativo = '."'S'".' AND tipo_prod_id = '.$row_rs_produtos['tipo_prod_id'].'';}
			//n-s
            if (($_POST['empresa'] == "") and ($_POST['municipio'] != "")){$aux = 'WHERE tipo_prod_id = '.$row_rs_produtos['tipo_prod_id'].' AND bl_associado.ass_cadastro_ativo = '."'S'". 'AND ass_municipio= '."'".$_POST['municipio']."'";}
			//s-n
            if (($_POST['empresa'] != "") and ($_POST['municipio'] == "")){$aux = 'WHERE bl_produto.tipo_prod_id = '.$row_rs_produtos['tipo_prod_id'].' AND bl_associado.ass_cadastro_ativo = '."'S'".'AND bl_produto.ass_id = '.$_POST['empresa'].' ';}
			//s-s
            if (($_POST['empresa'] != "") and ($_POST['municipio'] != "")){$aux = 'WHERE bl_produto.tipo_prod_id = '.$row_rs_produtos['tipo_prod_id'].' AND bl_produto.ass_id = '.$_POST['empresa'].' AND bl_associado.ass_cadastro_ativo = '."'S'".' AND ass_municipio= '."'".$_POST['municipio']."'";}
            #echo $aux;
            //fim construcao clausula busca associados
            mysql_select_db($database_sindicato, $sindicato);
            $query_rs_associados =
            '
            SELECT bl_associado . *
            FROM bl_produto
            INNER JOIN bl_associado ON bl_associado.ass_id = bl_produto.ass_id '.
            $aux.' 
            GROUP BY bl_associado.ass_id
            ORDER BY bl_associado.ass_razao_social
            ';
            $rs_associados = mysql_query($query_rs_associados);
            $row_rs_associados = mysql_fetch_assoc($rs_associados);
            $totalRows_rs_associados = mysql_num_rows($rs_associados);
            ?>
    <?php do { ?>
    <tr>
      <td width="12%">&nbsp;</td>
      <td width="88%">
      	<div class="letra" id="razaosocial">
			<?php echo $row_rs_associados['ass_razao_social']; ?>
        </div>
      
        <table width="100%">
          <tr>
            <td colspan="2">
            <?php
			
			
				mysql_select_db($database_sindicato, $sindicato);
				$query_rs_rel_produtos =
				'
				SELECT
					*
				FROM
					bl_produto
				WHERE
				   tipo_prod_id = '.$row_rs_produtos['tipo_prod_id'].' AND ass_id = '.$row_rs_associados['ass_id'].' '.' 
				ORDER BY prod_descricao
				';
				$rs_rel_produtos = mysql_query($query_rs_rel_produtos);
				$row_rs_rel_produtos = mysql_fetch_assoc($rs_rel_produtos);
				$totalRows_rs_rel_produtos = mysql_num_rows($rs_rel_produtos);
			?>
			</td>
          </tr>
          <?php do{ ?>
          <tr>
            <td width="9%" align="center" valign="middle"><img src="../produtos/<?php echo $row_rs_associados['ass_id']; ?>/produto/<?php echo $row_rs_rel_produtos['prod_imagem']; ?>" /></td>
<td width="91%" valign="middle" class="outro"><?php echo $row_rs_rel_produtos['prod_descricao']; ?></td>
          </tr>
          <?php } while ($row_rs_rel_produtos = mysql_fetch_assoc($rs_rel_produtos));?>
        </table>
        </td>
    </tr>
    <?php } while ($row_rs_associados = mysql_fetch_assoc($rs_associados)); ?>
  </table>
<?php } while ($row_rs_produtos = mysql_fetch_assoc($rs_produtos)); ?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_produtos);
?>
