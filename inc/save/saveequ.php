<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	
if($_GET["cmd"] == "cadastrar"){

	(isset($_GET["equ_status"]))? $status = "S": $status = "A";

	$sql = "INSERT INTO equipamento(
				equ_cli_id,					
				equ_nome,
				equ_modelo,
				equ_fabricante,
				equ_num_serie,
				equ_num_patrimonio,
				equ_descricao,
				equ_cad_usu_id,
				equ_dt_cad,
				equ_status
			) VALUES(
				'".$_GET["cli_id"]."',
				'".$_GET["obrg_equ_nome"]."',
				'".$_GET["equ_modelo"]."',
				'".$_GET["equ_fabricante"]."',
				'".$_GET["equ_num_serie"]."',
				'".$_GET["equ_num_patrimonio"]."',
				'".$_GET["equ_descricao"]."',
				'".$_GET["usu_id"]."',
				'".(time()-(date('I')*3600))."',
				'".$status."'
			)";
		
	$sqlQuery = mysql_query($sql);
	$equ_id = mysql_insert_id();
	
	//verificando se o cliente deve ser notificado por email
	$sql = "SELECT cli_not_cad_equ,usu_email FROM usuario
			JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
	$sqlQuery = mysql_query($sql);
	$rs_cli = mysql_fetch_object($sqlQuery);

	if($rs_cli->usu_not_cad_equ == "S"){
		
		$email = new Email($config["emp_email"]);
		$email->addTo($rs_cli->usu_email);
		$email->setSubject("EQID: #".$equ_id." Novo equipamento cadastrado");

		$message = new Notification("<b>EQID: #".$equ_id."<br/>Novo equipamento cadastrado</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);		
		$message->setCliente($_GET["cli_id"]);
		$message->setEquipamento($equ_id);

		$email->setMessage($message->create());
		$email->send();
	}
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("EQID: #".$equ_id." Novo equipamento cadastrado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_equ='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>EQID: #".$equ_id."<br/>Novo equipamento cadastrado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);			
	$message->setCliente($_GET["cli_id"]);
	$message->setEquipamento($equ_id);
	$email->setMessage($message->create());
	$email->send();
	
}elseif($_GET["cmd"] == "al"){		
	
	//verificando status atual
	$sql = "SELECT equ_status FROM equipamento WHERE equ_id=".$_GET["equ_id"]." FOR UPDATE";
	$p_sqlQuery = mysql_query($sql);
	$p_rs = mysql_fetch_array($p_sqlQuery);
	
	$email = new Email($config["emp_email"]);
	
	$state = "A";
	if($p_rs["equ_status"] == "A"){
		$state = "S";
		
		$email->setSubject("EQID: #".$_GET["equ_id"]." Equipamento bloqueado");
		$message = new Notification("<b>EQID: #".$_GET["equ_id"]."<br/>Equipamento bloqueado</b>");
	}else{
		$email->setSubject("EQID: #".$_GET["equ_id"]." Equipamento desbloqueado");
		$message = new Notification("<b>EQID: #".$_GET["equ_id"]."<br/>Equipamento desbloqueado</b>");
	}
	
	//atualizando status
	$sql = "UPDATE equipamento SET
				equ_alter_usu_id='".$_GET["tec_id"]."',
				equ_dt_alter='".(time()-(date('I')*3600))."',
				equ_status='".$state."' 
			WHERE equ_id=".$_GET["equ_id"];
	$ap_sqlQuery = mysql_query($sql);
	
	//verificando se o cliente deve ser notificado por email
	$sql = "SELECT cli_not_cad_equ,usu_email FROM usuario
			JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
	$sqlQuery = mysql_query($sql);
	$rs_cli = mysql_fetch_object($sqlQuery);
			
	if($rs_cli->cli_not_cad_equ == "S"){
		$email->addTo($rs_cli->usu_email);
	}	
	
	
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_equ='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);		
	$message->setCliente($_GET["cli_id"]);
	$message->setEquipamento($_GET["equ_id"]);
	$email->setMessage($message->create());
	$email->send();
	
}elseif($_GET["cmd"] == "excluir"){
	$sql = "DELETE FROM equipamento WHERE equ_id=".$_GET["equ_id"];
	$sqlQuery = mysql_query($sql);
	
	$email = new Email($config["emp_email"]);
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_equ='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$email->setSubject("EQID: #".$_GET["equ_id"]." Equipamento excluído");
	$message = new Notification("<b>EQID: #".$_GET["equ_id"]."<br/>Equipamento exclu&iacute;do</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);		
	$message->setCliente($_GET["cli_id"]);
	$message->setEquipamento($_GET["equ_id"]);
	$email->setMessage($message->create());
	$email->send();

}else{
	
	$sql = "UPDATE equipamento SET 
				equ_nome='".$_GET["obrg_equ_nome"]."',
				equ_modelo='".$_GET["equ_modelo"]."',
				equ_fabricante='".$_GET["equ_fabricante"]."',
				equ_num_serie='".$_GET["equ_num_serie"]."',
				equ_descricao='".$_GET["equ_descricao"]."',
				equ_num_patrimonio='".$_GET["equ_num_patrimonio"]."',
				equ_alter_usu_id='".$_GET["usu_id"]."',
				equ_dt_alter='".(time()-(date('I')*3600))."'
			WHERE equ_id=".$_GET["equ_id"];			
	$sqlQuery = mysql_query($sql) or die(mysql_error());
	
	
	//verificando se o cliente deve ser notificado por email
	$sql = "SELECT cli_not_cad_equ,usu_email FROM usuario
			JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
	$sqlQuery = mysql_query($sql);
	$rs_cli = mysql_fetch_object($sqlQuery);	

	if($rs_cli->cli_not_cad_equ == "S"){
		
		$email = new Email($config["emp_email"]);
		$email->addTo($rs_cli->usu_email);
		$email->setSubject("EQID: #".$_GET["equ_id"]." Cadastro do equipamento alterado");

		$message = new Notification("<b>EQID: #".$_GET["equ_id"]."<br/>Cadastro do equipamento alterado</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);		
		$message->setCliente($_GET["cli_id"]);
		$message->setEquipamento($_GET["equ_id"]);

		$email->setMessage($message->create());
		$email->send();
	}
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("EQID: #".$_GET["equ_id"]." Cadastro do equipamento alterado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_equ='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>EQID: #".$_GET["equ_id"]."<br/>Cadastro do equipamento alterado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);			
	$message->setCliente($_GET["cli_id"]);
	$message->setEquipamento($_GET["equ_id"]);
	$email->setMessage($message->create());
	$email->send();
}
?>
<?php mysql_close()?>