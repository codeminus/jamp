<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
if ($_GET["cmd"] == "cadastrar"){
	
		(isset($_GET["forn_status"]))? $status = "I": $status = "A";
	
		$sql = "INSERT INTO fornecedor(
					forn_nome,
					forn_cp,
					forn_inscricao,
					forn_email,
					forn_end_pais,
					forn_end_estado,
					forn_end_cidade,
					forn_end_logradouro,
					forn_end_complemento,
					forn_end_bairro,
					forn_end_cep,					
					forn_tel,
					forn_observacao,
					forn_cad_usu_id,
					forn_dt_cad,
					forn_status
				) VALUES(					
					'".$_GET["obrg_forn_nome"]."',
					'".$_GET["forn_cp"]."',
					'".$_GET["forn_inscricao"]."',
					'".$_GET["forn_email"]."',
					'".$_GET["forn_end_pais"]."',
					'".$_GET["forn_end_estado"]."',
					'".$_GET["forn_end_cidade"]."',
					'".$_GET["forn_end_logradouro"]."',
					'".$_GET["forn_end_complemento"]."',
					'".$_GET["forn_end_bairro"]."',
					'".$_GET["forn_end_cep"]."',					
					'".$_GET["forn_telefone"]."',
					'".$_GET["forn_observacao"]."',
					'".$_GET["usu_id"]."',
					'".(time()-(date('I')*3600))."',
					'".$status."'
				)";
			
		$sqlQuery = mysql_query($sql) or die(mysql_error());
		
		$forn_id = mysql_insert_id();		
		
		$email = new Email($config["emp_email"]);
			
		$email->setSubject("Código: #".$forn_id." Fornecedor cadastrado");
		$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
		$not_sqlQuery = mysql_query($sql);
		while($not_rs = mysql_fetch_object($not_sqlQuery)){
			$email->addTo($not_rs->usu_email);
		}
		
		$message = new Notification("<b>C&oacute;digo: #".$forn_id."<br/>Fornecedor cadastrado</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);	
		$message->setFornecedor($forn_id);
		$email->setMessage($message->create());
		$email->send();

}elseif($_GET["cmd"] == "al"){		
	
		//verificando status atual
		$sql = "SELECT forn_status FROM fornecedor WHERE forn_id=".$_GET["forn_id"]." FOR UPDATE";
		$p_sqlQuery = mysql_query($sql) or die($error = "dupuser");
		$p_rs = mysql_fetch_array($p_sqlQuery);
		
		$email = new Email($config["emp_email"]);
		
		$state = "A";
		if($p_rs["forn_status"] == "A"){
			$state = "I";
			$email->setSubject("Código: #".$_GET["forn_id"]." Fornecedor bloqueado");
			$message = new Notification("<b> C&oacute;digo: #".$_GET["forn_id"]."<br/>Fornecedor bloqueado.</b>");
		}else{
			$email->setSubject("Código: #".$_GET["forn_id"]." Fornecedor desbloqueado");
			$message = new Notification("<b> C&oacute;digo: #".$_GET["forn_id"]."<br/>Fornecedor desbloqueado.</b>");
		}
		
		
		//atualizando status
		$sql = "UPDATE fornecedor SET
					forn_alter_usu_id='".$_GET["tec_id"]."',
					forn_dt_alter='".(time()-(date('I')*3600))."',
					forn_status='".$state."' 
				WHERE forn_id=".$_GET["forn_id"];
		$ap_sqlQuery = mysql_query($sql) or die($error = "dupuser");
		
		$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
		$not_sqlQuery = mysql_query($sql);
		while($not_rs = mysql_fetch_object($not_sqlQuery)){
			$email->addTo($not_rs->usu_email);
		}
		
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);
		$message->setFornecedor($_GET["forn_id"]);		
		$email->setMessage($message->create());
		$email->send();
		
}else{
	
	$sql = "UPDATE fornecedor SET 
				forn_nome='".$_GET["obrg_forn_nome"]."',
				forn_cp='".$_GET["forn_cp"]."',
				forn_inscricao='".$_GET["forn_inscricao"]."',
				forn_email='".$_GET["forn_email"]."',
				forn_tel='".$_GET["forn_telefone"]."',
				forn_observacao='".$_GET["forn_observacao"]."',
				forn_end_pais='".$_GET["forn_end_pais"]."',
				forn_end_estado='".$_GET["forn_end_estado"]."',
				forn_end_cidade='".$_GET["forn_end_cidade"]."',
				forn_end_logradouro='".$_GET["forn_end_logradouro"]."',
				forn_end_complemento='".$_GET["forn_end_complemento"]."',
				forn_end_bairro='".$_GET["forn_end_bairro"]."',
				forn_end_cep='".$_GET["forn_end_cep"]."',
				forn_alter_usu_id='".$_GET["usu_id"]."',
				forn_dt_alter='".(time()-(date('I')*3600))."'
			WHERE forn_id=".$_GET["forn_id"];			
	$sqlQuery = mysql_query($sql) or die($erro = "erforn");
	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$_GET["forn_id"]." Cadastro de fornecedor alterado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$_GET["forn_id"]."<br/>Cadastro de fornecedor alterado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setFornecedor($_GET["forn_id"]);
	$email->setMessage($message->create());
	$email->send();
	
}	
?>
<?php echo $error?>
<?php mysql_close()?>