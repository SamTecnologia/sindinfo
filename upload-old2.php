<?php
			/* 	Upload.php
				Upload de arquivos desenvolvido por Euflávio para a BL  Técnologia em informática, com o intuito de fazer uploads das contribuições sindicais do sindinfo.
			*/
			// 1º. Definir os parametros de teste
			$tamanho_maximo = 100000; // em bytes
			$tipos_aceitos = 'text/plain';
			//2º validar o arquivo enviado
			$arquivo = $_FILES['ARQUIVO'];
			if($arquivo['error']!=0)
			{
				echo '<p><b><font color="red"> Erro de Upload do arquivo <br />';
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
				echo '</font></b></p>';
				exit;
			}
			if($arquivo['size']==0 or $arquivo['tmp_name']==NULL)
			{
				echo '<p><b><font color="red">Nenhum arquivo enviado</font></b></p>';
				exit;
			}
			if($arquivo['size']>$tamanho_maximo) {
				echo '<p><b><font color="red">O Arquivo enviado é muito grande (Tamanho Máximo = '.$tamanho_maximo.'</font></b></p>';
				exit;
			}
			if ($tipos_aceitos === FALSE) {
				echo '<p><b><font color="red">O arquivo não é do tipo (' .$arquivo .') Aceito para upload. Os tipos Aceitos São: </font></b></p>';
				echo '<pre>';
				print_r($tipos_aceitos);
				echo'</pre>';
				exit;
			}
			// testa se o cnpj escrito no arquivo é valido.
			$arquivo['name']; //recebe o nome do arquivo que está prestes a subir no site
			$cnpj = substr($arquivo['name'],0,14); // pega os primeiros 14 digitos para ver se o cnpj é valido
			
// Fim Verifica se  o CNPJ é verdadeiro
				
						
			//Verifica se a pasta existe
			$destino = "media/$cnpj/";
			$dr = is_dir("$destino");
			if($dr){
				echo 'porra';
			}
			else
			{
				mkdir("media/$cnpj",0777);
			}		
			
			// Agora podemos copiar o arquivo enviado
			$destino = "media/$cnpj/";
			$novoarquivo = '04558964000100';
			$destino .=$novoarquivo;
			if(move_uploaded_file($arquivo['tmp_name'],$destino))
			{
				//tudo Ok, mostramos os dados
				echo '<p><font color="navy"><b>';
				echo 'O Arquivo foi carregado com Sucesso!!!</b<font></p>';
				$fn = $destino;
				$f_contents = file($fn);
				$sua_linha = $f_contents[0]; // Le apenas a linha Header
$empresa = substr($sua_linha,0,14); // Le os primeiros 14 números e os armazena para formar o cnpj
				echo $empresa;
			}
		?>