<html>
	<head>
    	<title>
        	Valida Arquivo
        </title>
    </head>
    <body>
    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
        	<tr>
            	<td height="30" bgcolor="#8CDAFF">
                	<b>Upload de Arquivo - Resultado</b>
                </td>
                <td align="right" bgcolor="#8CDAFF">
                	<?=date("d-m-Y H:i:s") ?>&nbsp;
                </td>
            </tr>
        </table>
        <?php
			/* 	Upload.php
				Validação de arquivo enviado pelo usuario
				Walace Soares
				Janeiro 2004
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
			// Agora podemos copiar o arquivo enviado
			$cnpj = '11008831778';
			$destino = "media/$cnpj/";
			$novoarquivo = '04558964000100';
			mkdir("media/$cnpj",0777);
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
    </body>
</html>