<?php
	require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	$sql = "SELECT usu_pass FROM usuario WHERE usu_id=".$_GET["usu_id"];
	$sqlQuery2 = mysql_query($sql);
	$rs_tec = mysql_fetch_object($sqlQuery2);
	
	//preparando dados para alteração
	if($_GET["obrg_tec_npass"] != "000000"){
		$tec_pass = $_GET["obrg_tec_npass"];
	}else{
		$tec_pass = $rs_tec->usu_pass;
	}
	
	if($_GET["obrg_usu_reg_per_page"] < 1){
		$obrg_usu_reg_per_page = 20;
	}else{
		$obrg_usu_reg_per_page = $_GET["obrg_usu_reg_per_page"];
	}
	
	if(isset($_GET["usu_view_alertas"])){
		$usu_view_alertas = "S";
	}else{
		$usu_view_alertas = "N";	
	}
	
	$sql = "UPDATE usuario SET
				usu_nome='".$_GET["tec_nome"]."',
				usu_email='".$_GET["tec_email"]."',				
				usu_tel='".$_GET["tec_telefone"]."',				
				usu_pass='".$tec_pass."',
				usu_reg_per_page='".$obrg_usu_reg_per_page."',
				usu_view_alertas='".$usu_view_alertas."',
				usu_alter_usu_id='".$_GET["usu_id"]."',
				usu_dt_alter='".(time()-(date('I')*3600))."'
			WHERE usu_id=".$_GET["usu_id"];
				
	$sqlQuery = mysql_query($sql) or die (mysql_error());
	
	$email = new Email($config["emp_email"]);	
		
	//notificando equipe técnica
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_user='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$email->setSubject("USID: #".$_GET["usu_id"]." Cadastro de usuário alterado");

	$message = new Notification("<b> USID: #".$_GET["usu_id"]."<br/>Cadastro de usu&aacute;rio alterado.</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);
	$message->setUser($_GET["usu_id"]);		
	$email->setMessage($message->create());
	$email->send();
	
	mysql_close();
?>