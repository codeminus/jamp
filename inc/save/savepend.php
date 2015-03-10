<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	if($_GET["cmd"] == "gerar"){
		$sql = "INSERT INTO os_pendencia(
					pend_os_id,
					pend_tec_id,
					pend_assunto,
					pend_desc,
					pend_status,
					pend_data_criacao
				) VALUES(
					'".$_GET["os_id"]."',
					'".$_GET["tec_id"]."',
					'".$_GET["obrg_pend_assunto"]."',
					'".$_GET["obrg_pend_desc"]."',
					'A',
					'".(time()-(date('I')*3600))."'
				)";
			
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		$pend_id = mysql_insert_id();
		
		//verificando se o cliente deve ser notificado por email
		$sql = "SELECT cli_not_cad_pend,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs_cli = mysql_fetch_object($sqlQuery);
		
		if($rs_cli->cli_not_cad_pend == "S"){
			
			$email = new Email($config["emp_email"]);
			$email->addTo($rs_cli->usu_email);		
			$email->setSubject("OSID:#".$_GET["os_id"]." Nova pendência gerada");
	
			$message = new Notification("<b>OSID:#".$_GET["os_id"]."<br/>Nova pendência gerada.</b>");
			$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
			$message->setColors($colors);
			$message->setOs($_GET["os_id"]);
			$message->setPend($pend_id);
			$email->setMessage($message->create());
			$email->send();
			
		}
	}else if($_GET["cmd"] == "aceitar"){
		$sql = "UPDATE os_pendencia SET 
					pend_status='I',
					pend_data_aceita='".(time()-(date('I')*3600))."'
				WHERE pend_id=".$_GET["pend_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		
		//verificando se o cliente deve ser notificado por email
		$sql = "SELECT cli_not_cad_pend,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs_cli = mysql_fetch_object($sqlQuery);
		
		if($rs_cli->cli_not_cad_pend == "S"){	
		
			$email = new Email($config["emp_email"]);
			$email->addTo($rs_cli->usu_email);		
			$email->setSubject("OSID:#".$_GET["os_id"]." Resposta para a pendência aceita");
	
			$message = new Notification("<b>OSID:#".$_GET["os_id"]."<br/>Resposta para a pendência aceita.</b>");
			$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
			$message->setColors($colors);
			$message->setOs($_GET["os_id"]);
			$message->setPend($_GET["pend_id"]);
			$email->setMessage($message->create());
			$email->send();
			
		}
	}else if($_GET["cmd"] == "rejeitar"){
		$sql = "UPDATE os_pendencia SET pend_status='RNS' WHERE pend_id=".$_GET["pend_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		
		$sql = "INSERT INTO os_pend_compl (
					os_pend_compl_pend_id,
					os_pend_compl_desc,
					os_pend_compl_desc_dt)
				VALUES (
					'".$_GET["pend_id"]."',
					'".$_GET["obrg_os_pend_compl_desc"]."',
					'".(time()-(date('I')*3600))."'
				)";				
		$sqlQuery = mysql_query($sql);
		
		//verificando se o cliente deve ser notificado por email
		$sql = "SELECT cli_not_cad_pend,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs_cli = mysql_fetch_object($sqlQuery);
		
		if($rs_cli->cli_not_cad_pend == "S"){	
		
			$email = new Email($config["emp_email"]);
			$email->addTo($rs_cli->usu_email);		
			$email->setSubject("OSID:#".$_GET["os_id"]." Resposta para a pendência não satisfatória");
	
			$message = new Notification("<b>OSID:#".$_GET["os_id"]."<br/>Resposta para a pendência não satisfatória.</b>");
			$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
			$message->setColors($colors);
			$message->setOs($_GET["os_id"]);
			$message->setPend($_GET["pend_id"]);
			$email->setMessage($message->create());
			$email->send();
			
		}
	}elseif($_GET["cmd"] == "resp_pend"){
		$sql = "UPDATE os_pendencia SET 
					pend_status='ACT',
					pend_resp='".$_GET["obrg_pend_resp"]."',
					pend_data_resp='".(time()-(date('I')*3600))."'
				WHERE pend_id=".$_GET["pend_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		
		//selecionando técnico
		$sql = "SELECT usu_email FROM usuario WHERE usu_id=".$_GET["tec_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		$rs_tec = mysql_fetch_object($sqlQuery);				
			
		$email = new Email($config["emp_email"]);
		$email->addTo($rs_tec->usu_email);		
		$email->setSubject("OSID:#".$_GET["os_id"]." Pendência respondida");

		$message = new Notification("<b>OSID:#".$_GET["os_id"]."<br/>Pendência respondida.</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setOs($_GET["os_id"]);
		$message->setPend($_GET["pend_id"]);
		$email->setMessage($message->create());
		$email->send();

	}elseif($_GET["cmd"] == "tec_resp_pend"){
		$sql = "UPDATE os_pendencia SET 
					pend_status='I',
					pend_resp='".$_GET["obrg_pend_resp"]."',
					pend_data_resp='".(time()-(date('I')*3600))."',					
					pend_data_aceita='".(time()-(date('I')*3600))."'
				WHERE pend_id=".$_GET["pend_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		
		
		//verificando se o cliente deve ser notificado por email
		$sql = "SELECT cli_not_cad_pend,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs_cli = mysql_fetch_object($sqlQuery);
		
		if($rs_cli->cli_not_cad_pend == "S"){
			$email = new Email($config["emp_email"]);
			$email->addTo($rs_cli->usu_email);		
			$email->setSubject("OSID:#".$_GET["os_id"]." Pendência respondida pela equipe técnica");
	
			$message = new Notification("<b>OSID:#".$_GET["os_id"]."<br/>Pendência respondida pela equipe técnica.</b>");
			$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
			$message->setColors($colors);
			$message->setOs($_GET["os_id"]);
			$message->setPend($_GET["pend_id"]);
			$email->setMessage($message->create());
			$email->send();
		}
	}elseif($_GET["cmd"] == "resp_pend_compl"){
		$sql = "UPDATE os_pendencia SET 
					pend_status='ACT'					
				WHERE pend_id=".$_GET["pend_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		
		$sql = "UPDATE os_pend_compl SET					
					os_pend_compl_resp='".$_GET["obrg_pend_compl_resp"]."',
					os_pend_compl_resp_dt='".(time()-(date('I')*3600))."'
				WHERE os_pend_compl_id=".$_GET["pend_compl_id"];				
		$sqlQuery = mysql_query($sql);
		
		
		//selecionando cliente
		$sql = "SELECT usu_nome,usu_email FROM usuario WHERE usu_id=".$_GET["cli_id"];
		$cli_sqlQuery = mysql_query($sql);	
		$rs_cli = mysql_fetch_object($cli_sqlQuery);
		
		//selecionando técnico
		$sql = "SELECT usu_email FROM usuario WHERE usu_id=".$_GET["tec_id"];
		$sqlQuery = mysql_query($sql) or die($error = "erpend");
		$rs_tec = mysql_fetch_object($sqlQuery);				
		
		
		$email = new Email($config["emp_email"]);
		$email->addTo($rs_tec->usu_email);		
		$email->setSubject("OSID:#".$_GET["os_id"]." Complemento da pendência respondido");

		$message = new Notification("<b>OSID:#".$_GET["os_id"]."<br/>Complemento da pendência respondido.</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setOs($_GET["os_id"]);
		$message->setPend($_GET["pend_id"]);
		$email->setMessage($message->create());
		$email->send();
		
	}
?>
<?php echo $error?>
<?php mysql_close()?>