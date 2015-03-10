<?php 
	require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");

	//cad-edit
	$permissao['0'] = "usu_cad_user";
	$permissao['1'] = "usu_cad_prod";
	$permissao['2'] = "usu_cad_cli";
	$permissao['3'] = "usu_cad_equ";
	$permissao['4'] = "usu_cad_os";
	
	//view
	$permissao['5'] = "usu_view_user";
	$permissao['6'] = "usu_view_cli";
	$permissao['7'] = "usu_view_doc";
	$permissao['8'] = "usu_view_os";
	
	//outros
	$permissao['9'] = "usu_aprov_os";
	$permissao['10'] = "usu_atrib_os";
	$permissao['11'] = "usu_alter_info";
	$permissao['12'] = "usu_block";
	$permissao['13'] = "usu_del_os";
	
	
	if($_GET["cmd"] == "ap"){
		
		$state = "S";
	
		//verificando estado atual da permissao
		$sql = "SELECT ".$permissao[$_GET["p"]]." FROM usuario WHERE usu_id=".$_GET["usu_id"]." FOR UPDATE";
		$p_sqlQuery = mysql_query($sql);
		$p_rs = mysql_fetch_array($p_sqlQuery);
		
		if($p_rs[$permissao[$_GET["p"]]] == "S"){
			$state = "N";
		}
		
		//atualizando permissao
		$sql = "UPDATE usuario SET
					usu_alter_usu_id='".$_GET["tec_id"]."',
					usu_dt_alter='".(time()-(date('I')*3600))."',
					".$permissao[$_GET["p"]]."='".$state."'
				WHERE usu_id=".$_GET["usu_id"];
		$ap_sqlQuery = mysql_query($sql);
		
	}elseif($_GET["cmd"] == "cadastrar"){
	
		(isset($_GET["usu_cad_user"]))? $usu_cad_user = "S" : $usu_cad_user = "N";
		(isset($_GET["usu_cad_prod"]))? $usu_cad_prod = "S" : $usu_cad_prod = "N";
		(isset($_GET["usu_cad_cli"]))? $usu_cad_cli = "S" : $usu_cad_cli = "N";
		(isset($_GET["usu_cad_equ"]))? $usu_cad_equ = "S" : $usu_cad_equ = "N";
		(isset($_GET["usu_cad_os"]))? $usu_cad_os = "S" : $usu_cad_os = "N";
		
		(isset($_GET["usu_view_user"]))? $usu_view_user = "S" : $usu_view_user = "N";
		(isset($_GET["usu_view_cli"]))?	$usu_view_cli = "S" : $usu_view_cli = "N";		
		(isset($_GET["usu_view_os"]))?	$usu_view_os = "S" : $usu_view_os = "N";
		
		
		(isset($_GET["otr_usu_aprov_os"]))? $otr_usu_aprov_os = "S" : $otr_usu_aprov_os = "N";
		(isset($_GET["otr_usu_atrib_os"]))? $otr_usu_atrib_os = "S" : $otr_usu_atrib_os = "N";
		(isset($_GET["otr_usu_alter_info"]))?	$otr_usu_alter_info = "S" : $otr_usu_alter_info = "N";
		(isset($_GET["otr_usu_block"]))? $otr_usu_block = "S" : $otr_usu_block = "N";
		(isset($_GET["otr_usu_del_os"]))?	$otr_usu_del_os = "S" : $otr_usu_del_os = "N";
		
		(isset($_GET["usu_not_user"]))? $usu_not_user = "S" : $usu_not_user = "N";
		(isset($_GET["usu_not_prod"]))?	$usu_not_prod = "S" : $usu_not_prod = "N";
		(isset($_GET["usu_not_cli"]))?	$usu_not_cli = "S" : $usu_not_cli = "N";
		(isset($_GET["usu_not_equ"]))?	$usu_not_equ = "S" : $usu_not_equ = "N";
		(isset($_GET["usu_not_os"]))?	$usu_not_os = "S" : $usu_not_os = "N";
		(isset($_GET["otr_not_atrib_os"]))?	$otr_not_atrib_os = "S" : $otr_not_atrib_os = "N";
		(isset($_GET["otr_not_rel_adm"]))?	$otr_not_rel_adm = "S" : $otr_not_rel_adm = "N";
		
		(isset($_GET["usu_status"]))? $usu_status = "AS" : $usu_status = "A";	
		
		$sql = "INSERT INTO usuario(
					usu_tipo,
					usu_nome,
					usu_email,					
					usu_tel,
					usu_criacao_usu_id,
					usu_dt_criacao,
					usu_login,
					usu_pass,
					usu_status,
					usu_cad_user,
					usu_cad_prod,
					usu_cad_cli,
					usu_cad_equ,
					usu_cad_os,
					usu_atrib_os,
					usu_view_user,
					usu_view_cli,					
					usu_view_os,
					usu_del_os,
					usu_not_user,
					usu_not_prod,
					usu_not_cli,
					usu_not_equ,
					usu_not_os,
					usu_aprov_os,
					usu_not_atrib_os,
					usu_not_rel_adm,
					usu_block,
					usu_alter_info,
					usu_adv_info,
					usu_obs
				)VALUES(
					'T',
					'".$_GET["obrg_tec_nome"]."',
					'".$_GET["usu_email"]."',					
					'".$_GET["usu_tel"]."',
					'".$_GET["usu_id"]."',
					'".(time()-(date('I')*3600))."',
					'".$_GET["obrg_usu_login"]."',
					'".$_GET["obrg_usu_npass"]."',
					'".$usu_status."',
					'".$usu_cad_user."',
					'".$usu_cad_prod."',
					'".$usu_cad_cli."',
					'".$usu_cad_equ."',
					'".$usu_cad_os."',
					'".$otr_usu_atrib_os."',
					'".$usu_view_user."',
					'".$usu_view_cli."',					
					'".$usu_view_os."',
					'".$otr_usu_del_os."',
					'".$usu_not_user."',
					'".$usu_not_prod."',
					'".$usu_not_cli."',
					'".$usu_not_equ."',
					'".$usu_not_os."',
					'".$otr_usu_aprov_os."',
					'".$otr_not_atrib_os."',
					'".$otr_not_rel_adm."',
					'".$otr_usu_block."',
					'".$otr_usu_alter_info."',
					'S',
					'".$_GET["usu_obs"]."'
				)";
				
		$usu_sqlQuery = mysql_query($sql) or die($error = "dupuser");
		$usu_id = mysql_insert_id();
		
		
		//notificando novo usuário
		$email = new Email($config["emp_email"]);
		$email->addTo($_GET["usu_email"]);		
		$email->setSubject("Cadastro de usuário realizado com sucesso");

		$message = new Notification("<b>Bem-vindo!<br/>Abaixo est&atilde;o as informa&ccedil;&otilde;es relativas a sua conta.</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setUser($usu_id);
		$message->setUserAdvinfo(true);
		$message->setUserLogin($usu_id);
		$message->setSysLink(true);
		$email->setMessage($message->create());
		$email->send();
		
		//notificando equipe tecnica
		$email = new Email($config["emp_email"]);		
		
		$sql = "SELECT usu_email FROM usuario WHERE usu_not_user='S' AND usu_status<>'I' AND usu_id<>".$usu_id;
		$not_sqlQuery = mysql_query($sql);
		while($not_rs = mysql_fetch_object($not_sqlQuery)){
			$email->addTo($not_rs->usu_email);
		}
		
		$email->setSubject("USID: #".$usu_id." Cadastro de usuário realizado com sucesso");

		$message = new Notification("<b> USID: #".$usu_id."<br/>Cadastro de usu&aacute;rio realizado com sucesso.</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setUser($usu_id);		
		$email->setMessage($message->create());
		$email->send();
		
	}elseif($_GET["cmd"] == "alterar"){
		(isset($_GET["usu_cad_user"]))? $usu_cad_user = "S" : $usu_cad_user = "N";
		(isset($_GET["usu_cad_prod"]))? $usu_cad_prod = "S" : $usu_cad_prod = "N";
		(isset($_GET["usu_cad_cli"]))? $usu_cad_cli = "S" : $usu_cad_cli = "N";
		(isset($_GET["usu_cad_equ"]))? $usu_cad_equ = "S" : $usu_cad_equ = "N";
		(isset($_GET["usu_cad_os"]))? $usu_cad_os = "S" : $usu_cad_os = "N";
		
		(isset($_GET["usu_view_user"]))? $usu_view_user = "S" : $usu_view_user = "N";
		(isset($_GET["usu_view_cli"]))?	$usu_view_cli = "S" : $usu_view_cli = "N";		
		(isset($_GET["usu_view_os"]))?	$usu_view_os = "S" : $usu_view_os = "N";
		
		
		(isset($_GET["otr_usu_aprov_os"]))? $otr_usu_aprov_os = "S" : $otr_usu_aprov_os = "N";
		(isset($_GET["otr_usu_atrib_os"]))? $otr_usu_atrib_os = "S" : $otr_usu_atrib_os = "N";
		(isset($_GET["otr_usu_alter_info"]))?	$otr_usu_alter_info = "S" : $otr_usu_alter_info = "N";
		(isset($_GET["otr_usu_block"]))? $otr_usu_block = "S" : $otr_usu_block = "N";
		(isset($_GET["otr_usu_del_os"]))?	$otr_usu_del_os = "S" : $otr_usu_del_os = "N";
		
		(isset($_GET["usu_not_user"]))? $usu_not_user = "S" : $usu_not_user = "N";
		(isset($_GET["usu_not_prod"]))?	$usu_not_prod = "S" : $usu_not_prod = "N";
		(isset($_GET["usu_not_cli"]))?	$usu_not_cli = "S" : $usu_not_cli = "N";
		(isset($_GET["usu_not_equ"]))?	$usu_not_equ = "S" : $usu_not_equ = "N";
		(isset($_GET["usu_not_os"]))?	$usu_not_os = "S" : $usu_not_os = "N";
		(isset($_GET["otr_not_atrib_os"]))?	$otr_not_atrib_os = "S" : $otr_not_atrib_os = "N";
		(isset($_GET["otr_not_rel_adm"]))?	$otr_not_rel_adm = "S" : $otr_not_rel_adm = "N";		
		
		(isset($_GET["usu_status"]))? $usu_status = "AS" : $usu_status = "A";	
		
		
		if($_GET["obrg_usu_npass"] == "000000"){
			$sql = "SELECT usu_pass FROM usuario WHERE usu_id=".$_GET["usu_id"];
			$pass_sqlQuery = mysql_query($sql);
			$pass_rs = mysql_fetch_object($pass_sqlQuery);
			$pass = $pass_rs->usu_pass;
		}else{
			$pass = $_GET["obrg_usu_npass"];
		}
		
		$sql = "UPDATE usuario SET
					usu_nome='".$_GET["obrg_tec_nome"]."',
					usu_email='".$_GET["usu_email"]."',					
					usu_tel='".$_GET["usu_tel"]."',
					usu_alter_usu_id='".$_GET["tec_id"]."',
					usu_dt_alter='".(time()-(date('I')*3600))."',
					usu_login='".$_GET["obrg_usu_login"]."',
					usu_pass='".$pass."',
					usu_status='".$usu_status."',
					usu_cad_user='".$usu_cad_user."',
					usu_cad_prod='".$usu_cad_prod."',
					usu_cad_cli='".$usu_cad_cli."',
					usu_cad_equ='".$usu_cad_equ."',
					usu_cad_os='".$usu_cad_os."',
					usu_aprov_os='".$otr_usu_aprov_os."',
					usu_atrib_os='".$otr_usu_atrib_os."',
					usu_view_user='".$usu_view_user."',
					usu_view_cli='".$usu_view_cli."',					
					usu_view_os='".$usu_view_os."',
					usu_del_os='".$otr_usu_del_os."',
					usu_not_user='".$usu_not_user."',
					usu_not_prod='".$usu_not_prod."',
					usu_not_cli='".$usu_not_cli."',
					usu_not_equ='".$usu_not_equ."',
					usu_not_os='".$usu_not_os."',
					usu_not_atrib_os='".$otr_not_atrib_os."',
					usu_not_rel_adm='".$otr_not_rel_adm."',
					usu_block='".$otr_usu_block."',
					usu_alter_info='".$otr_usu_alter_info."',
					usu_adv_info='S',
					usu_obs='".$_GET["usu_obs"]."'
				WHERE
					usu_id='".$_GET["usu_id"]."'
				";
				
		$usu_sqlQuery = mysql_query($sql) or die(mysql_error());
		
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
		
	}elseif($_GET["cmd"] == "al"){		
	
		//verificando status atual
		$sql = "SELECT usu_status FROM usuario WHERE usu_id=".$_GET["usu_id"]." FOR UPDATE";
		$p_sqlQuery = mysql_query($sql) or die($error = "dupuser");
		$p_rs = mysql_fetch_array($p_sqlQuery);
		
		$email = new Email($config["emp_email"]);
		
		$state = "A";
		if($p_rs["usu_status"] == "A" || $p_rs["usu_status"] == "AS"){
			$state = "I";
			$email->setSubject("USID: #".$_GET["usu_id"]." Usuário bloqueado");
			$message = new Notification("<b> USID: #".$_GET["usu_id"]."<br/>Usu&aacute;rio bloqueado.</b>");
		}else{
			$email->setSubject("USID: #".$_GET["usu_id"]." Usuário desbloqueado");
			$message = new Notification("<b> USID: #".$_GET["usu_id"]."<br/>Usu&aacute;rio desbloqueado.</b>");
		}
		
		
		//atualizando status
		$sql = "UPDATE usuario SET
					usu_alter_usu_id='".$_GET["tec_id"]."',
					usu_dt_alter='".(time()-(date('I')*3600))."',
					usu_status='".$state."' 
				WHERE usu_id=".$_GET["usu_id"];
		$ap_sqlQuery = mysql_query($sql) or die($error = "dupuser");
		
		
		$sql = "SELECT usu_email FROM usuario WHERE usu_id=".$_GET["usu_id"];
		$not_sqlQuery2 = mysql_query($sql);		
		$not_rs2 = mysql_fetch_object($not_sqlQuery2);
		$email->addTo($not_rs2->usu_email);
		
		$sql = "SELECT usu_email FROM usuario WHERE usu_not_user='S' AND usu_status<>'I'";
		$not_sqlQuery = mysql_query($sql);
		while($not_rs = mysql_fetch_object($not_sqlQuery)){
			$email->addTo($not_rs->usu_email);
		}
			

		
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setUser($_GET["usu_id"]);		
		$email->setMessage($message->create());
		$email->send();
		
	}
?>
<?php echo $error?>
