<?php
        require("bdConfig.php");
        
        $conecta = @mysql_connect($host_bd, $user_bd,$password_bd) or print (mysql_error());  	
        mysql_select_db($name_bd, $conecta) or  die(mysql_error()); 



?>
