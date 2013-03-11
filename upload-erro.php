<?php
	require_once('../Connections/sindicato.php');
	include('valida_cpf.php');
	include('validacnpj.php');
	/*	Upload de Arquivos (sistema para upload de arquivos e validação antes da inserção dos dados no banco de dados)
			Site - Sindinfo
			Desenvolvido por: Euflávio Rodrigo da Silva Sátiro
			Empresa: BL Tecnologia em Informática
	*/
	
	/* DEFINIÇÃO DE PARAMETROS DO ARQUIVO */
		$tamanho_maximo = 100000; //em bytes
		$tipo_aceito = 'text/plain';
	/* FINAL DA DEFINIÇÃO DE PARAMETROS DO ARQUIVO */
	
	/* VALIDAÇÃO DO ARQUIVO ENVIADO */
		$arquivo = $_FILES['ARQUIVO'];
		if($arquivo['error']<>0)
		{
			echo 'Erro de Upload de Arquivo <br />';
			switch($arquivo['error'])
		{
		case UPLOAD_ERR_INI_SIZE:
								echo 'O Arquivo excede o tamanho máximo permitido';
		break;
		case UPLOAD_ERR_FORM_SIZE:
								echo 'O Arquivo enviado é muito grande';
		break;
		case UPLOAD_ERR_PARTIAL:
								echo 'O upload não foi completo';
		break;
		case UPLOAD_ERR_NO_FILE:
								echo 'Nenhum Arquivo foi informado para envio';
		break;
		}
		exit;
		}
		if($arquivo['size']==0 or $arquivo['tmp_name']==NULL)
		{
			echo 'Nenhum arquivo enviado';
			exit;
		}
		if($arquivo['size']>$tamanho_maximo) {
			echo 'O Arquivo enviado é muito grande (Tamanho Máximo = '.$tamanho_maximo;
			exit;
		}
		if ($tipo_aceito === FALSE) {
			echo 'O arquivo não é do tipo (' .$arquivo .') Aceito para upload. Os tipos Aceitos São:';
			echo '<pre>';
			print_r($tipos_aceitos);
			echo'</pre>';
			exit;
		}
		$arquivo['name']; //recebe o nome do arquivo que está prestes a subir no site
	/* FINAL DA VALIDAÇÃO DO ARQUIVO ENVIADO */

	/* VALIDAÇÃO DO ANO DO NOME DO ARQUIVO */
		$ano1 = substr($arquivo['name'],15,4);
		if(!is_numeric($ano1))
			{
				echo "<script>alert('ERRO. O Ano ".$ano1." Não é um ano válido. Verifique!'); history.back();</script>"; die;
			}
	/* FINAL DA VALIDAÇÃO DO ANO DO NOME DO ARQUIVO */
		
	/* VALIDAÇÃO DO CNPJ DO NOME DO ARQUIVO */
		$cnpj = substr($arquivo['name'],0,14); // pega os primeiros 14 digitos para ver se o cnpj é valido
		if(is_numeric($cnpj)) //Verifica se os 14 digitos são numéricos
		{
			echo $cnpj.'<br />';
			$num = $cnpj;
		/* EXCLUI O NUMERO 00000000000000 DA LISTA DE CNPJ'S VALIDOS */
			if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
			{
				echo "<script>alert('ERRO. Não é permitido o CNPJ ".$cnpj .". Verifique!'); history.back();</script>"; die;
			}
		/*FINAL DE EXCLUI O NUMERO 00000000000000 DA LISTA DE CNPJ'S VALIDOS */
		
		/* CALCULA E COMPARA PRIMEIRO DIGITO VERIFICADOR */
			else
				{
					$j=5;
					for($i=0; $i<4; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);
					$j=9;
					for($i=4; $i<12; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);	
					$resto = $soma%11;			
					if($resto<2)
						{
							$dg=0;
						}
					else
						{
							$dg=11-$resto;
						}
					if($dg!=$num[12])
						{
						echo "<script>alert('ERRO. O CNPJ nº".$cnpj ." é inválido. Verifique!'); history.back();</script>"; die;
						} 
				}
		/* FIM DE CALCULA E COMPARA PRIMEIRO DIGITO VERIFICADOR */
		
		/* CALCULA E COMPARA SEGUNDO DIGITO VERIFICADOR */
			$j=6;
			for($i=0; $i<5; $i++)
			{
				$multiplica[$i]=$num[$i]*$j;
				$j--;
			}
			$soma = array_sum($multiplica);
			$j=9;
			for($i=5; $i<13; $i++)
			{
				$multiplica[$i]=$num[$i]*$j;
				$j--;
			}
			$soma = array_sum($multiplica);	
			$resto = $soma%11;			
			if($resto<2)
			{
				$dg=0;
			}
			else
			{
			$dg=11-$resto;
			}
			if($dg!=$num[13])
			{
				echo "<script>alert('ERRO. O CNPJ nº".$cnpj ." é inválido. Verifique!'); history.back();</script>"; die;
			}
		}
		/* FIM DE CALCULA E COMPARA SEGUNDO DIGITO VERIFICADOR */
		
	/* FINAL DA VALIDAÇÃO DO CNPJ DO NOME DO ARQUIVO */

	/* VERIFICAÇÃO DO DIRETÓRIO DE ENVIO DO ARQUIVO */
			else
			{
				$destino = "media/$cnpj/";
				$dr = is_dir("$destino");
			/* SE EXISTIR */
				if($dr)
				{
					/* PARAMETROS PARA CÓPIA DO ARQUIVO */
					$novoarquivo = $cnpj;
					$destino .=$novoarquivo;
					/* FIM DE PARAMETROS PARA CÓPIA DO ARQUIVO */
					
			if(move_uploaded_file($arquivo['tmp_name'],$destino))
					{
						echo '<p><font color="navy"><b>O Arquivo <a href="'.$destino.'" target="_blank">'.$cnpj.'</a> foi carregado com sucesso!!!</b</font></p>';
						$fn = $destino;
						$f_contents = file($fn);
						$ultima_liha = end($f_contents);// Armazena o conteúdo da Ultima linha
						$sua_linha = $f_contents[0]; // Le apenas a linha Header
						$empresa = substr($sua_linha,0,14); // Le os primeiros 14 números e os armazena para formar o cnpj
						$ano = substr($sua_linha,15,4); // Armazena o ano
						
						/* COMPARA CNPJ DO TITULO DO ARQUIVO COM CNPJ INCLUSO NO ARQUIVO */
						if($cnpj <> $empresa)
						{
							echo "<script>alert('ERRO. O CNPJ nº ".$cnpj ." contido no nome do arquivo não corresponde ao cnpj nº ".$empresa." contido na linha header do arquivo. Verifique!'); history.back();</script>"; die;
						}
						/* FIM COMPARA CNPJ DO TITULO DO ARQUIVO COM CNPJ INCLUSO NO ARQUIVO */
						
						/* COMPARA ANO DO TITULO DO ARQUIVO COM CNPJ INCLUSO NO ARQUIVO */
						if($ano1 <> $ano)
						{
							echo "<script>alert('ERRO. O CNPJ nº ".$ano ." contido no nome do arquivo não corresponde ao cnpj nº ".$empresa." contido na linha header do arquivo. Verifique!'); history.back();</script>"; die;
						}
						/* FIM COMPARA ANO DO TITULO DO ARQUIVO COM CNPJ INCLUSO NO ARQUIVO */
						
						/* VERIFICA NO BANCO DE DADOS SE JÁ EXISTE EMPRESA CADASTRADA  */
						mysql_select_db($database_sindicato,$sindicato); //Conexao com o banco
						$query_rs_empresa = sprintf("SELECT * FROM bl_csind_empresa WHERE csind_emp_cnpj = $empresa"); 
						$rs_empresa = mysql_query($query_rs_empresa,$sindicato) or die (mysql_error());
						$linha_rs_empresa = mysql_fetch_assoc($rs_empresa);
						$totaldeLinhas_rs_empresa = mysql_num_rows($rs_empresa);
						
						/* SE EMPRESA CADASTRADA */
							if ($linha_rs_empresa['csind_emp_id']<> NULL)
							{
								echo 'Empresa Já Cadastrada<br />';
								$data = date('Y-m-d');
								$emp_id = $linha_rs_empresa['csind_emp_id'];
								$nome_arquivo = $fn;
								
							/* VARRE O ARQUIVO PARA VER SE EXISTE ALGUM CPF INCONSISTENTE */
								for ($registros = 1; $registros <= $ultima_liha; $registros++)
								{
									$cbo = substr($f_contents[$registros],0,6);
									$cpf = substr($f_contents[$registros],26,11);
									//$colaborador = substr($f_contents[$registros],37);
									if ((strlen($cbo)<>6) or (is_numeric($cbo)))
									{
										echo "<script>alert('ERRO. O CBO nº".$cbo ." contido na linha ".$registros." é inválido. Possui".strlen($cpf)." caracteres. Verifique!'); history.back();</script>"; die;
									}
									if ((strlen($cpf)<>11) or (is_numeric($cpf)))
									{
										echo "<script>alert('ERRO. O CPF nº".$cpf ." contido na linha ".$registros." é inválido. Possui".strlen($cpf)." caracteres. Verifique!'); history.back();</script>"; die;
									}
									/* verifica se o cpf é valido */
										validaCPF($cpf);
										if(validaCPF($cpf)==FALSE)
										{
											
											echo "<script>alert('ERRO. O CPF nº ".$cpf ." contido no arquivo, na linha".$registros." não é valido. Verifique!'); history.back();</script>"; die;
										}
									/* final da verificação */
								}
							/* FINAL DE VARREDURA */
							
							/* INSERE ARQUIVO NO BANCO */
								mysql_select_db($database_sindicato,$sindicato); // Conecta com o Banco
								$query_inserirdados = sprintf("INSERT INTO  `sindinfo_sindicato`.`bl_csind_arquivo` (`csind_arq_data`,`csind_emp_id`,`csind_arq_referencia`,`arquivo`) VALUES ( '$data','$emp_id','$ano','$nome_arquivo')");
								$Result1 = mysql_query($query_inserirdados, $sindicato) or  die("<script>alert('ERRO. Já existe cadastro para o ano ".$ano ." para o CNPJ ".$cnpj."'); history.back();</script>");
								
									/* CONSULTA ARQUIVO INSERIDO PARA PEGAR SEU ID */
										mysql_select_db($database_sindicato,$sindicato); // conecta com o Banco
										$query_rs_arquivo = sprintf("SELECT * FROM bl_csind_arquivo WHERE csind_emp_id = '$emp_id' AND csind_arq_referencia = '$ano'");
										$rs_arquivo = mysql_query($query_rs_arquivo, $sindicato) or die(mysql_error());
										$linha_rs_arquivo = mysql_fetch_assoc($rs_arquivo);
										$totaldeLinhas_rs_arquivo = mysql_num_rows($rs_arquivo);
									/* FINAL DE CONSULTA */
									
							/* FINAL DE INSERÇÃO DE ARQUIVO */
							
							/* INSERE OS DADOS DO FUNCIONÁRIO */
								for ($registros = 1; $registros <= $ultima_liha; $registros++)
								{
								$colaborador = substr($f_contents[$registros],37);
								mysql_select_db($database_sindicato,$sindicato);
								$inserefuncionario = sprintf("INSERT INTO bl_csind_contribuicao(csind_arq_id,csind_cont_cbo,csind_cont_admissao,csind_cont_valor,csind_cont_cpf,csind_cont_nome) VALUES ($arq_id,'$cbo','$admissao',$valor,$cpf,'$colaborador')");
								$Result1 = mysql_query($inserefuncionario, $sindicato) or die(mysql_error());
								echo '<br />'.$inserefuncionario;
							}
							/* FIM DE INSERÇÃO */
						}
						/* FINAL DO CONDICIONAL DA EMPRESA */
?>