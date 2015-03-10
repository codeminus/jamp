<?php
require("../../sys/_config.php");
require("../../sys/_dbconn.php");
require("../../css/_colors.php");	
require("../_lib.php");
	
if($_GET["cmd"] == "cadastrar"){
	
	(isset($_GET["serv_status"]))? $status = "I": $status = "A";
	
	$sql = "INSERT INTO servico (
				serv_nome,
				serv_desc,
				serv_unid_medida,
				serv_valor,
				serv_cad_usu_id,
				serv_dt_cad,
				serv_status
			)VALUES(
				'".$_GET["obrg_serv_nome"]."',
				'".$_GET["serv_desc"]."',
				'".$_GET["serv_unid_medida"]."',
				'".str_replace(",",".",$_GET["obrg_serv_valor"])."',
				'".$_GET["usu_id"]."',
				'".(time()-(date('I')*3600))."',
				'".$status."'
			)";
	
	$sqlQuery = mysql_query($sql) or die($error = "dupserv");
	$serv_id = mysql_insert_id();
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$serv_id." Cadastro de serviço");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$serv_id."<br/>Cadastro de servi&ccedil;o</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setServico($serv_id);
	$email->setMessage($message->create());
	$email->send();
	
}elseif($_GET["cmd"] == "alterar"){
	$sql = "UPDATE servico SET
				serv_nome='".$_GET["obrg_serv_nome"]."',
				serv_desc='".$_GET["serv_desc"]."',
				serv_unid_medida='".$_GET["serv_unid_medida"]."',
				serv_valor='".str_replace(",",".",$_GET["obrg_serv_valor"])."',
				serv_alter_usu_id='".$_GET["usu_id"]."',
				serv_dt_alter='".(time()-(date('I')*3600))."'
			WHERE serv_id='".$_GET["serv_id"]."'
			";
	$sqlQuery = mysql_query($sql);
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$_GET["serv_id"]." Cadastro de serviço alterado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$_GET["serv_id"]."<br/>Cadastro de servi&ccedil;o alterado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setServico($_GET["serv_id"]);
	$email->setMessage($message->create());
	$email->send();
}elseif($_GET["cmd"] == "al"){		
	
	//verificando status atual
	$sql = "SELECT serv_status FROM servico WHERE serv_id=".$_GET["serv_id"]." FOR UPDATE";
	$p_sqlQuery = mysql_query($sql);
	$p_rs = mysql_fetch_array($p_sqlQuery);
	
	$state = "A";
	if($p_rs["serv_status"] == "A"){
		$state = "I";
		
		$email->setSubject("Código: #".$_GET["serv_id"]." Serviço bloqueado");
		$message = new Notification("<b>C&oacute;digo: #".$_GET["classprod_id"]."<br/>Servi&ccedilo; bloqueado</b>");
	}else{
		$email->setSubject("Código: #".$_GET["serv_id"]." Serviço desbloqueado");
		$message = new Notification("<b>C&oacute;digo: #".$_GET["serv_id"]."<br/>Servi&ccedilo; desbloqueado</b>");
	}
	
	//atualizando status
	$sql = "UPDATE servico SET
				serv_alter_usu_id='".$_GET["tec_id"]."',
				serv_dt_alter='".(time()-(date('I')*3600))."',
				serv_status='".$state."' 
			WHERE serv_id=".$_GET["serv_id"];
	$ap_sqlQuery = mysql_query($sql);
	
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setServico($_GET["serv_id"]);
	$email->setMessage($message->create());
	$email->send();
}
?>
<?php echo $error?>
<?php mysql_close()?>