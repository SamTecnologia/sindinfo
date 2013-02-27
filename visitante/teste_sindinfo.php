<?php
	require_once('../Connections/sindicato.php');

$fn = "teste_sindinfo.txt"; //Abre o arquivo
$f_contents = file($fn); // Armazena o conteudo do arquivo
$ultima_liha = end($f_contents);// Armazena o conteúdo da Ultima linha
$sua_linha = $f_contents[0]; // Le apenas a linha Header
$empresa = substr($sua_linha,0,14); // Le os primeiros 14 números e os armazena para formar o cnpj

// Inicio da consulta pelo cnpj
mysql_select_db($database_sindicato,$sindicato); //Conexao com o banco
$query_rs_empresa = sprintf("SELECT * FROM bl_csind_empresa WHERE csind_emp_cnpj = $empresa"); //consulta
$rs_empresa = mysql_query($query_rs_empresa,$sindicato) or die (mysql_error());
$linha_rs_empresa = mysql_fetch_assoc($rs_empresa);
$totaldeLinhas_rs_empresa = mysql_num_rows($rs_empresa);
// Finaliza a consulta
//condicional se retornar resultado
if ($linha_rs_empresa['csind_emp_id']<> NULL)
{
	echo 'Empresa Já Cadastrada<br />';
	$data = date('Y-m-d');
	echo $data;
	$emp_id = $linha_rs_empresa['csind_emp_id'];
	echo '<br />'. $emp_id;
	$ano = substr($sua_linha,15,4); // Armazena o ano
	echo '<br />'. $ano . '<br />';
	$nome_arquivo = $fn; // Cadastra arquivo no banco
	echo $nome_arquivo;
	mysql_select_db($database_sindicato,$sindicato); // Conecta com o Banco
	/*$query_inserirdados = sprintf("INSERT INTO  `sindinfo_sindicato`.`bl_csind_arquivo` (`csind_arq_data`,`csind_emp_id`,`csind_arq_referencia`,`arquivo`) VALUES ( '$data','$emp_id','$ano','$nome_arquivo')");
	$Result1 = mysql_query($query_inserirdados, $sindicato) or die(mysql_error());// Insert no banco de dados
	echo '<br />'.$query_inserirdados;*/
	//Consulta empresa e referencia inserida
	mysql_select_db($database_sindicato,$sindicato); // conecta com o Banco
	$query_rs_arquivo = sprintf("SELECT * FROM bl_csind_arquivo WHERE csind_emp_id = '$emp_id' AND csind_arq_referencia = '$ano'");
	$rs_arquivo = mysql_query($query_rs_arquivo, $sindicato) or die(mysql_error());
	$linha_rs_arquivo = mysql_fetch_assoc($rs_arquivo);
	$totaldeLinhas_rs_arquivo = mysql_num_rows($rs_arquivo);
	echo '<br /> Número do Arquivo ' . $linha_rs_arquivo['csind_arq_id'].'<br />';
	//Final consulta
	
	//Condiçao para inserir as linhas dos arquivos
	for ($registros = 1; $registros <= $ultima_liha; $registros++)
	{
		$arq_id = $linha_rs_arquivo['csind_arq_id'];
		echo $arq_id.'<br />';
		$cbo = substr($f_contents[$registros],0,6);
		echo $cbo.'<br />';
		$admissao = substr($f_contents[$registros],13,4).'-'.substr($sua_linha,10,2).'-'.substr($sua_linha,8,2);
		echo $admissao.'<br />';
		$valor = substr($f_contents[$registros],18,7);
		echo $valor.'<br />';
		$colaborador = substr($f_contents[$registros],26);
		echo $colaborador.'<br />';
		mysql_select_db($database_sindicato,$sindicato); //Conexao com o Banco de Dados
		$inserefuncionario = sprintf("INSERT INTO bl_csind_contribuicao(csind_arq_id,csind_cont_cbo,csind_cont_admissao,csind_cont_valor,csind_cont_nome) VALUES ($arq_id,'$cbo','$admissao',$valor,'$colaborador')");
	$Result1 = mysql_query($inserefuncionario, $sindicato) or die(mysql_error());// Insert no banco de dados
	echo '<br />'.$inserefuncionario;
		
	}
	
}


?>