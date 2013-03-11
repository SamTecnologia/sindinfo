<?php
	/*	Upload de Arquivos (sistema para upload de arquivos e validação antes da inserção dos dados no banco de dados)
		Site - Sindinfo
		Desenvolvido por: Euflávio Rodrigo da Silva Sátiro
		Empresa: BL Tecnologia em Informática
	 */
	 // 1º. Definir os parametros do arquivo
	 $tamanho_maximo = 100000; //em bytes
	 $tipo_aceito = 'text/plain';
	 
	 //2º validar o arquivo enviado
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
	
	//1ª validação de arquivo "CNPJ do nome do arquivo"
		$arquivo['name']; //recebe o nome do arquivo que está prestes a subir no site
		$cnpj = substr($arquivo['name'],0,14); // pega os primeiros 14 digitos para ver se o cnpj é valido
		$ano1 = substr($arquivo['name'],15,4);
		if(is_numeric($cnpj)) //Verifica se os 14 digitos são numéricos
		{
			if(!is_numeric($ano1))
			{
				echo "<script>alert('ERRO. O Ano ".$ano1." Não é um ano válido. Verifique!'); history.back();</script>"; die;
			}
			echo $cnpj.'<br />';
			$num = $cnpj;
			//Etapa 1: O número 00000000000 embora não seja um cnpj real resultaria um cnpj válido após o calculo dos dígitos verificadores e por isso precisa ser filtradas nesta etapa.
			if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
			{
				echo "<script>alert('ERRO. Não é permitido o CNPJ ".$cnpj .". Verifique!'); history.back();</script>"; die;
			}
			//Etapa 2: Calcula e compara o primeiro dígito verificador.
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
		//Etapa 3: Calcula e compara o segundo dígito verificador.
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
		else
			{
				//Verifica se a pasta para envio do arquivo existe
				$destino = "media/$cnpj/";
				$dr = is_dir("$destino");
				if($dr)
				{
					// Agora podemos copiar o arquivo enviado
					$novoarquivo = $cnpj;
					$destino .=$novoarquivo;
					if(move_uploaded_file($arquivo['tmp_name'],$destino))
					{
						//tudo Ok, mostramos os dados
						echo '<p><font color="navy"><b>O Arquivo <a href="'.$destino.'" target="_blank">'.$cnpj.'</a> foi carregado com sucesso!!!</b</font></p>';
						$fn = $destino;
						$f_contents = file($fn);
						$ultima_liha = end($f_contents);// Armazena o conteúdo da Ultima linha
						$sua_linha = $f_contents[0]; // Le apenas a linha Header
						$empresa = substr($sua_linha,0,14); // Le os primeiros 14 números e os armazena para formar o cnpj
						$ano = substr($sua_linha,15,4); // Armazena o ano
						//Compara o cnpj do nome do arquivo com o cnpj incluso na header do arquivo
						if($cnpj <> $empresa)
						{
							echo "<script>alert('ERRO. O CNPJ nº ".$cnpj ." contido no nome do arquivo não corresponde ao cnpj nº ".$empresa." contido na linha header do arquivo. Verifique!'); history.back();</script>"; die;
						}
						if($ano1 <> $ano)
						{
							echo "<script>alert('ERRO. O CNPJ nº ".$ano ." contido no nome do arquivo não corresponde ao cnpj nº ".$empresa." contido na linha header do arquivo. Verifique!'); history.back();</script>"; die;
						}
						
						// proxima etapa apos verificar o cnpj
						require_once('Connections/sindicato.php');
						
						//Realiza consulta pelo cnpj
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
							$emp_id = $linha_rs_empresa['csind_emp_id'];
							$nome_arquivo = $fn; // Cadastra arquivo no banco
							echo $nome_arquivo;
							mysql_select_db($database_sindicato,$sindicato); // Conecta com o Banco
							$query_inserirdados = sprintf("INSERT INTO  `sindinfo_sindicato`.`bl_csind_arquivo` (`csind_arq_data`,`csind_emp_id`,`csind_arq_referencia`,`arquivo`) VALUES ( '$data','$emp_id','$ano','$nome_arquivo')");
							$Result1 = mysql_query($query_inserirdados, $sindicato) or  die("<script>alert('ERRO. Já existe cadastro para o ano ".$ano ." para o CNPJ ".$cnpj."'); history.back();</script>");// Insert no banco de dados
							echo '<br />'.$query_inserirdados;
							//Consulta empresa e referencia inserida
							mysql_select_db($database_sindicato,$sindicato); // conecta com o Banco
							$query_rs_arquivo = sprintf("SELECT * FROM bl_csind_arquivo WHERE csind_emp_id = '$emp_id' AND csind_arq_referencia = '$ano'");
							$rs_arquivo = mysql_query($query_rs_arquivo, $sindicato) or die(mysql_error());
							$linha_rs_arquivo = mysql_fetch_assoc($rs_arquivo);
							$totaldeLinhas_rs_arquivo = mysql_num_rows($rs_arquivo);
							echo '<br /> Número do Arquivo ' . $linha_rs_arquivo['csind_arq_id'].'<br />';
							//Final consulta
							
							//Condiçao para inserir as linhas dos arquivos
							include('valida_cpf.php');
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

								//Valida o CPF
								validaCPF($cpf);
								if(validaCPF($cpf)==FALSE)
								{
									
									echo "<script>alert('ERRO. O CPF nº ".$cpf ." contido no arquivo, na linha".$registros." não é valido. Verifique!'); history.back();</script>"; die;
								}
							}
							for ($registros = 1; $registros <= $ultima_liha; $registros++)
							{
								$colaborador = substr($f_contents[$registros],37);
								echo $colaborador.'<br />';
								mysql_select_db($database_sindicato,$sindicato); //Conexao com o Banco de Dados
								$inserefuncionario = sprintf("INSERT INTO bl_csind_contribuicao(csind_arq_id,csind_cont_cbo,csind_cont_admissao,csind_cont_valor,csind_cont_cpf,csind_cont_nome) VALUES ($arq_id,'$cbo','$admissao',$valor,$cpf,'$colaborador')");
								$Result1 = mysql_query($inserefuncionario, $sindicato) or die(mysql_error());// Insert no banco de dados
								echo '<br />'.$inserefuncionario;
							}
						}
						else
						{
							$cnpj = substr($sua_linha,0,14); // Armazena o cnpj para realizar consulta no banco
							echo $cnpj.'<br />';
							$ano = substr($sua_linha,15,4); // Armazena o ano
							echo $ano.'<br />';
							$razao_social = substr($sua_linha,20); //Armazena a razão social
							
							// Cadastra a Empresa no Banco
							
							mysql_select_db($database_sindicato,$sindicato); //Conexão com o banco
							$insereempresa = sprintf("INSERT INTO bl_csind_empresa(csind_emp_cnpj, csind_emp_razao) VALUES ('$cnpj', '$razao_social')");
							$result2 = mysql_query($insereempresa,$sindicato) or die(mysql_error()); // Insert no banco de dados
							echo '<br />'.$insereempresa;
							if ($result2)
							{
								echo "<br />Empresa Cadastrada com Sucesso!";
							}
							else
							{
								echo "Falha ao Cadastrar! <br />".mysql_error();
							}
							
							// Inicio da consulta pelo cnpj
							mysql_select_db($database_sindicato,$sindicato); //Conexao com o banco
							$query_rs_empresa_nova = sprintf("SELECT * FROM bl_csind_empresa WHERE csind_emp_cnpj = $empresa"); //consulta
							$rs_empresa_nova = mysql_query($query_rs_empresa_nova,$sindicato) or die (mysql_error());
							$linha_rs_empresa_nova = mysql_fetch_assoc($rs_empresa_nova);
							$totaldeLinhas_rs_empresa_nova = mysql_num_rows($rs_empresa_nova);
							
							echo 'Empresa Já Cadastrada <br />';
							$data = date('Y-m-d');
							echo $data;
							$emp_id = $linha_rs_empresa_nova['csind_emp_id'];
							echo '<br />'. $emp_id;
							$ano = substr($sua_linha,15,4); // Armazena o ano
							echo '<br />'. $ano . '<br />';
							$nome_arquivo = $fn; // Cadastra arquivo no banco
							echo $nome_arquivo;
							mysql_select_db($database_sindicato,$sindicato); // Conecta com o Banco
							$query_inserirdados = sprintf("INSERT INTO  `sindinfo_sindicato`.`bl_csind_arquivo` (`csind_arq_data`,`csind_emp_id`,`csind_arq_referencia`,`arquivo`) VALUES ( '$data','$emp_id','$ano','$nome_arquivo')");
							$Result1 = mysql_query($query_inserirdados, $sindicato) or die(mysql_error());// Insert no banco de dados
							echo '<br />'.$query_inserirdados;
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
					}
					//final do if de envio do arquivo
					
					
					
					
					
				}
				else
				{
					mkdir("media/$cnpj",0777);
				}		
			}	
		}
		else
		{
		  echo "<script>alert('ERRO. CNPJ ".$cnpj ." não é valido.'); history.back();</script>"; die;
		}

?>