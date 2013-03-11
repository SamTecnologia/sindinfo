<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="upload.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="5">
    <tr>
      <th colspan="2" bgcolor="#0099FF" scope="row"><h3>Formulário de Envio de Contribuição Sindical</h3></th>
    </tr>
    <tr>
      <th bgcolor="#0099FF" scope="row">E-mail:</th>
      <td><span id="sprytextfield1">
      <label for="email"></label>
      <input name="email" type="text" id="email" size="50" />
      <span class="textfieldRequiredMsg">Um valor é necessário.</span><span class="textfieldInvalidFormatMsg">Formato inválido.</span></span></td>
    </tr>
    <tr>
      <th bgcolor="#0099FF" scope="row">Arquivo:</th>
      <td><input type="file" name="ARQUIVO" size="50" /></td>
    </tr>
    <tr>
      <th scope="row"><input type="hidden" name="MAX_SIZE_FILE" value="100000" /></th>
      <td align="right"><input type="submit" name="button" id="button" value="Enviar Arquivo" /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["blur"]});
</script>
</body>
</html>