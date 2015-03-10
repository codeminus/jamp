<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	if($_GET["cmd"] == "atrib_tec"){
		$sql = "INSERT INTO osetec (
					ot_os_id,
					ot_tec_id,
					ot_atrib_tec_id,
					ot_atrib_just,
					ot_dt_inicio
				) VALUES(
					'".$_GET["os_id"]."',
					'".$_GET["obrg_tec_id"]."',
					'".$_GET["atrib_tec_id"]."',
					'Sem técnico responsável',
					'".(time()-(date('I')*3600))."'
				)";
		
		$sqlQuery1 = mysql_query($sql);
		
		$sql = "UPDATE os SET
					os_tec_id='".$_GET["obrg_tec_id"]."',
					os_sta_id=4,
					os_alter_usu_id='".$_GET["atrib_tec_id"]."',
					os_data_alter='".(time()-(date('I')*3600))."',
					os_data_inicio_manutencao='".(time()-(date('I')*3600))."'
				WHERE
					os_id='".$_GET["os_id"]."'	
				";
				
		$sqlQuery2 = mysql_query($sql);
				
		//verificando se o cliente deve ser notificado por email
		$sql = "SELECT cli_not_atrib_os,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs_cli = mysql_fetch_object($sqlQuery);
		
		$email = new Email($config["emp_email"]);
		
		if($rs_cli->cli_not_atrib_os == "S"){
			$email->addTo($rs_cli->usu_email);
		}		
		
		//notificando a equipe de suporte
		$sql = "SELECT * FROM usuario WHERE usu_tipo='T' AND usu_not_atrib_os='S' AND usu_status<>'I'";
		$sqlQuery = mysql_query($sql);	
		
		while($rs_tec = mysql_fetch_object($sqlQuery)){
			$email->addTo($rs_tec->usu_email);
		}	
		
		
		$email->setSubject("OSID: #".$_GET["os_id"]." Ordem de serviço em manutenção");
		$message = new Notification("<b>OSID: #".$_GET["os_id"]."<br/>Ordem de serviço em manutenção</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setOs($_GET["os_id"]);
		$message->setCliente($_GET["cli_id"]);
		$message->setEquipamento($_GET["equ_id"]);
		$email->setMessage($message->create());
		$email->send();
		
	}else{
		
		$sql = "SELECT * FROM osetec 
				WHERE ot_os_id=".$_GET["os_id"]." 
				AND ot_dt_inicio=(SELECT MAX(ot_dt_inicio) FROM osetec WHERE ot_os_id=".$_GET["os_id"].")";
		
		$sqlQuery0 = mysql_query($sql);
		$ot_atual = mysql_fetch_object($sqlQuery0);
		
		$sql = "UPDATE osetec SET
					ot_dt_fim='".(time()-(date('I')*3600))."'
				WHERE 
					ot_id='".$ot_atual->ot_id."'
				";
		
		$sqlQuery1 = mysql_query($sql);
		
		$sql = "INSERT INTO osetec (
					ot_os_id,
					ot_tec_id,
					ot_atrib_tec_id,
					ot_atrib_just,
					ot_dt_inicio
				) VALUES(
					'".$_GET["os_id"]."',
					'".$_GET["obrg_tec_id"]."',
					'".$_GET["atrib_tec_id"]."',
					'".$_GET["obrg_atrib_just"]."',
					'".(time()-(date('I')*3600))."'
				)";
		
		$sqlQuery2 = mysql_query($sql);
		
		$sql = "UPDATE os SET
					os_tec_id='".$_GET["obrg_tec_id"]."',
					os_alter_usu_id='".$_GET["atrib_tec_id"]."',
					os_data_alter='".(time()-(date('I')*3600))."'
				WHERE
					os_id='".$_GET["os_id"]."'	
				";
		$sqlQuery3 = mysql_query($sql);
		
		//verificando se o cliente deve ser notificado por email
		$sql = "SELECT cli_not_atrib_os,usu_email FROM usuario
				JOIN cliente ON cli_usu_id=usu_id WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs_cli = mysql_fetch_object($sqlQuery);
		
		$email = new Email($config["emp_email"]);
		
		if($rs_cli->cli_not_atrib_os == "S"){
			$email->addTo($rs_cli->usu_email);
		}		
		
		//notificando a equipe de suporte
		$sql = "SELECT * FROM usuario WHERE usu_tipo='T' AND usu_not_atrib_os='S' AND usu_status<>'I'";
		$sqlQuery = mysql_query($sql);	
		
		while($rs_tec = mysql_fetch_object($sqlQuery)){
			$email->addTo($rs_tec->usu_email);
		}	
		
		
		$email->setSubject("OSID: #".$_GET["os_id"]." Alteração de responsabilidade técnica");
		$message = new Notification("<b>OSID: #".$_GET["os_id"]."<br/>Alteração de responsabilidade técnica</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setAtribTec($ot_atual->ot_tec_id,$_GET["obrg_tec_id"],$_GET["obrg_atrib_just"]);
		$email->setMessage($message->create());
		$email->send();
	}
	
	mysql_close();
?>
<?php echo $erro?>