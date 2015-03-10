<?php
	#usar esta linha na tag body
	/*onload="setInterval('getLoginTime(\'sys/monitor.php?usu_id=<?php echo $_SESSION["tec_id"]?>\')',1000)"*/

	$monitor_conn = mysql_connect("newserviceltda.net","newserviceuser","new123") or 
	die('
		<div align="center" style="margin-top: 100px">
			<img src="img/atencao.png" width="100px" height="100px" /><br/>
			Erro na conex&atilde;o, por favor tente novamente em instantes.<br/>
			<a href="javascript:location.reload()"><b>clique aqui para atualizar a p&aacute;gina</b></a>
		</div>
	');
	#mysql_connect("localhost","newserviceuser","new123") or die("Erro na conexão.");
	mysql_select_db("newserviceltda",$monitor_conn) or die("Banco inexistente.");	
	
	$sql = "UPDATE usuario SET usu_dt_logout='".(time()-(date('I')*3600))."' WHERE usu_id=".$_GET["usu_id"];
	$sqlQuery = mysql_query($sql,$monitor_conn);
?>

