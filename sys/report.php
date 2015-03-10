<?php
	require("_dbconn.php");
	$sql = "SELECT adm_nome,tec_nome,cli_nome,usu_email FROM usuario";
	$sqlQuery = mysql_query($sql);
	$usu_nome = "";
	
	
	
	$report = fopen("relatorio.pdf","w+bt");
	while($rs = mysql_fetch_object($sqlQuery)){		
		if($rs->adm_nome != ""){ $usu_nome = $rs->adm_nome;}
		if($rs->tec_nome != ""){ $usu_nome = $rs->tec_nome;}
		if($rs->cli_nome != ""){ $usu_nome = $rs->cli_nome;}
		fwrite($report,"Nome: ".$usu_nome." e-mail: ".$rs->usu_email."");
	}
	fclose($report);
	
	
	
	
?>