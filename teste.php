<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
</head>

<body>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
    	<td height="30" bgcolor="#0000FF">
        	<b>Upload de Arquivo - SINDINFO</b>
        </td>
        <td align="right" bgcolor="#0000FF">
        	<?=date("d-m-Y H:i:s") ?> &nbsp;
        </td>
  </tr>
</table>
<form name="usr" enctype="multipart/form-data" method="post" action="upload.php">
	<table border="0" cellpadding="5" cellspacing="5">
    	<tr>
        	<td width="25%" height="30"><b>Arquivo: </b></td>
            <td height="30">
            	<input type="hidden" name="MAX_SIZE_FILE" value="100000" />
                <input type="file" name="ARQUIVO" size="50" />
            </td>
        </tr>
        <tr>
        	<td colspan="2"><input type="submit" value="Enviar o Arquivo" /></td>
        </tr>
    </table>
</form>
</body>
</html>