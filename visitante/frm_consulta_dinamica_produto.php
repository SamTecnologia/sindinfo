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

mysql_select_db($database_sindicato, $sindicato);
$query_rs_associado = "SELECT * FROM bl_associado WHERE ass_cadastro_ativo = 'S' ORDER BY ass_razao_social";
$rs_associado = mysql_query($query_rs_associado, $sindicato) or die(mysql_error());
$row_rs_associado = mysql_fetch_assoc($rs_associado);
$totalRows_rs_associado = mysql_num_rows($rs_associado);

mysql_select_db($database_sindicato, $sindicato);
$query_rs_tipo_produto = "SELECT * FROM bl_tipo_produto ORDER BY tipo_prod_descricao";
$rs_tipo_produto = mysql_query($query_rs_tipo_produto, $sindicato) or die(mysql_error());
$row_rs_tipo_produto = mysql_fetch_assoc($rs_tipo_produto);
$totalRows_rs_tipo_produto = mysql_num_rows($rs_tipo_produto);

mysql_select_db($database_sindicato, $sindicato);
$query_rs_municipio = "SELECT * FROM bl_municipio ORDER BY mun_nome";
$rs_municipio = mysql_query($query_rs_municipio, $sindicato) or die(mysql_error());
$row_rs_municipio = mysql_fetch_assoc($rs_municipio);
$totalRows_rs_municipio = mysql_num_rows($rs_municipio);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.letra {
	color: #003;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	font-weight: bold;
	color: #003;
}
.letra {
	font-family: Arial;
	font-size: 13px;
	color: #2B617D;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="frm_portifolio_associados.php">
  <p>
    <label><span class="letra">Selecione um associado:</span>
<select name="empresa" id="empresa">
        <option value="">Todos</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_associado['ass_id']?>"><?php echo $row_rs_associado['ass_razao_social']?></option>
        <?php
} while ($row_rs_associado = mysql_fetch_assoc($rs_associado));
  $rows = mysql_num_rows($rs_associado);
  if($rows > 0) {
      mysql_data_seek($rs_associado, 0);
	  $row_rs_associado = mysql_fetch_assoc($rs_associado);
  }
?>
      </select>
    </label>
  <p>
    <label><span class="letra">Selecione um tipo de produto:</span>
<select name="tipo_produto" id="tipo_produto">
        <option value="">Todos</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_tipo_produto['tipo_prod_id']?>"><?php echo $row_rs_tipo_produto['tipo_prod_descricao']?></option>
        <?php
} while ($row_rs_tipo_produto = mysql_fetch_assoc($rs_tipo_produto));
  $rows = mysql_num_rows($rs_tipo_produto);
  if($rows > 0) {
      mysql_data_seek($rs_tipo_produto, 0);
	  $row_rs_tipo_produto = mysql_fetch_assoc($rs_tipo_produto);
  }
?>
      </select>
    </label>
  </p>
  <p>
    <label><span class="letra">Selecione um município:</span>
<select name="municipio" id="municipio">
        <option value="">Todos</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_municipio['mun_nome']?>"><?php echo $row_rs_municipio['mun_nome']?></option>
        <?php
} while ($row_rs_municipio = mysql_fetch_assoc($rs_municipio));
  $rows = mysql_num_rows($rs_municipio);
  if($rows > 0) {
      mysql_data_seek($rs_municipio, 0);
	  $row_rs_municipio = mysql_fetch_assoc($rs_municipio);
  }
?>
      </select></label>
  </p>
  <p>
    <label><input type="submit" name="buscar" id="buscar" value="Buscar" />
    </label>
  </p>
</form>
</body>
</html>
<?php
mysql_free_result($rs_associado);

mysql_free_result($rs_tipo_produto);

mysql_free_result($rs_municipio);
?>
