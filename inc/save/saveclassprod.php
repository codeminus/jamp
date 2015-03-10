<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	
if($_GET["cmd"] == "cadastrar"){

	(isset($_GET["classprod_status"]))? $status = "I": $status = "A";

	$sql = "INSERT INTO classprod(
				classprod_nome,
				classprod_desc,
				classprod_cad_usu_id,
				classprod_dt_cad,
				classprod_status
			) VALUES(
				'".$_GET["obrg_classprod_nome"]."',
				'".$_GET["classprod_desc"]."',				
				'".$_GET["usu_id"]."',
				'".(time()-(date('I')*3600))."',
				'".$status."'
			)";
		
	$sqlQuery = mysql_query($sql);
	$classprod_id = mysql_insert_id();	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$classprod_id." Nova classificação cadastrada");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$classprod_id."<br/>Nova classifica&ccedil;&atilde;o cadastrada</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setClassProduto($classprod_id);
	$email->setMessage($message->create());
	$email->send();
	
}elseif($_GET["cmd"] == "al"){		
	
	//verificando status atual
	$sql = "SELECT classprod_status FROM classprod WHERE classprod_id=".$_GET["classprod_id"]." FOR UPDATE";
	$p_sqlQuery = mysql_query($sql);
	$p_rs = mysql_fetch_array($p_sqlQuery);
	
	$email = new Email($config["emp_email"]);
	
	$state = "A";
	if($p_rs["classprod_status"] == "A"){
		$state = "I";
		
		$email->setSubject("Código: #".$_GET["classprod_id"]." Classificação bloqueada");
		$message = new Notification("<b>C&oacute;digo: #".$_GET["classprod_id"]."<br/>Classifica&ccedil;&atilde;o bloqueada</b>");
	}else{
		$email->setSubject("Código: #".$_GET["classprod_id"]." Classificação desbloqueada");
		$message = new Notification("<b>C&oacute;digo: #".$_GET["classprod_id"]."<br/>Classifica&ccedil;&atilde;o desbloqueada</b>");
	}
	
	//atualizando status
	$sql = "UPDATE classprod SET
				classprod_alter_usu_id='".$_GET["tec_id"]."',
				classprod_dt_alter='".(time()-(date('I')*3600))."',
				classprod_status='".$state."' 
			WHERE classprod_id=".$_GET["classprod_id"];
	$ap_sqlQuery = mysql_query($sql);	
	
	
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setClassProduto($_GET["classprod_id"]);
	$email->setMessage($message->create());
	$email->send();

}elseif($_GET["cmd"] == "excluir"){
	$sql = "UPDATE classprod SET classprod_status='E' WHERE classprod_id=".$_GET["classprod_id"];
	$sqlQuery = mysql_query($sql) or die(mysql_error());
	
	$email = new Email($config["emp_email"]);
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$email->setSubject("Código: #".$_GET["classprod_id"]." Classificação excluída");
	$message = new Notification("<b>C&oacute;digo: #".$_GET["classprod_id"]."<br/>classifica&ccedil;&atilde;o exclu&iacute;da</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setClassProduto($_GET["classprod_id"]);
	$email->setMessage($message->create());
	$email->send();
	
}else{	
	
	$sql = "UPDATE classprod SET				
				classprod_nome='".$_GET["obrg_classprod_nome"]."',
				classprod_desc='".$_GET["classprod_desc"]."',
				classprod_alter_usu_id='".$_GET["usu_id"]."',
				classprod_dt_alter='".(time()-(date('I')*3600))."'
			WHERE classprod_id=".$_GET["classprod_id"];			
	$sqlQuery = mysql_query($sql) or die(mysql_error());
	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$_GET["classprod_id"]." Cadastro da classificação alterado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$_GET["classprod_id"]."<br/>Cadastro da classifica&ccedil;&atilde;o alterado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setClassProduto($_GET["classprod_id"]);
	$email->setMessage($message->create());
	$email->send();
}
?>
<?php mysql_close()?>