<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sindicato = "localhost";
$database_sindicato = "sindinfo_sindicato";
$username_sindicato = "root";
$password_sindicato = "mysql";
$sindicato = mysql_pconnect($hostname_sindicato, $username_sindicato, $password_sindicato) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
