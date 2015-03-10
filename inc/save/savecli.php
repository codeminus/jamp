<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
if ($_GET["cmd"] == "cadastrar"){

	(isset($_GET["cli_not_info"]))? $cli_not_info = "S" : $cli_not_info = "N";
	(isset($_GET["cli_not_cad_equ"]))? $cli_not_cad_equ = "S" : $cli_not_cad_equ = "N";
	(isset($_GET["cli_not_cad_os"]))? $cli_not_cad_os = "S" : $cli_not_cad_os = "N";
	(isset($_GET["cli_not_atrib_os"]))? $cli_not_atrib_os = "S" : $cli_not_atrib_os = "N";
	
	(isset($_GET["otr_not_rel"]))? $cli_not_cad_rel = "S" : $cli_not_cad_rel = "N";
	(isset($_GET["otr_not_pend"]))?	$cli_not_cad_pend = "S" : $cli_not_cad_pend = "N";
		
		$sql = "INSERT INTO usuario(					
					usu_tipo,
					usu_nome,
					usu_email,
					usu_end_pais,
					usu_end_estado,
					usu_end_cidade,
					usu_end_logradouro,
					usu_end_complemento,
					usu_end_bairro,
					usu_end_cep,					
					usu_tel,
					usu_criacao_usu_id,
					usu_dt_criacao,
					usu_login,
					usu_pass,
					usu_status,
					usu_obs
				) VALUES(					
					'C',
					'".$_GET["obrg_cli_nome"]."',
					'".$_GET["cli_email"]."',
					'".$_GET["cli_end_pais"]."',
					'".$_GET["cli_end_estado"]."',
					'".$_GET["cli_end_cidade"]."',
					'".$_GET["cli_end_logradouro"]."',
					'".$_GET["cli_end_complemento"]."',
					'".$_GET["cli_end_bairro"]."',
					'".$_GET["cli_end_cep"]."',					
					'".$_GET["cli_telefone"]."',
					'".$_GET["usu_id"]."',
					'".(time()-(date('I')*3600))."',
					'".$_GET["obrg_cli_user"]."',
					'".$_GET["obrg_cli_npass"]."',
					'AS',
					'".$_GET["usu_obs"]."'
				)";
			
		$sqlQuery = mysql_query($sql) or die(mysql_error());
		
		$cli_id = mysql_insert_id();
		
		$sql = "INSERT INTO cliente(
					cli_usu_id,
					cli_cp,
					cli_inscricao,
					cli_not_info,
					cli_not_cad_equ,
					cli_not_cad_os,
					cli_not_atrib_os,
					cli_not_cad_rel,
					cli_not_cad_pend
				) VALUES(
					'".$cli_id."',
					'".$_GET["cli_cp"]."',
					'".$_GET["cli_inscricao"]."',
					'".$cli_not_info."',
					'".$cli_not_cad_equ."',
					'".$cli_not_cad_os."',
					'".$cli_not_atrib_os."',
					'".$cli_not_cad_rel."',
					'".$cli_not_cad_pend."'
				)";
				
		$sqlQuery2 = mysql_query($sql) or die(mysql_error());
		
		if($cli_not_info == "S"){		
								
			$email = new Email($config["emp_email"]);
			$email->addTo($_GET["cli_email"]);
			$email->setSubject("Cliente cadastrado com sucesso");
	
			$message = new Notification("<b>".$config["emp_welcome"]."<br/>Abaixo estão as informações relativas a sua conta</b>");
			$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
			$message->setColors($colors);			
			$message->setCliente($cli_id);
			$message->setClienteAdvinfo(true);
			$message->setUserLogin($cli_id);
			$message->setSysLink(true);
			$email->setMessage($message->create());
			$email->send();
			
		}
		
		$email = new Email($config["emp_email"]);
		
		$email->setSubject("CLID: #".$cli_id." Cliente cadastrado com sucesso");
		$sql = "SELECT usu_email FROM usuario WHERE usu_not_cli='S' AND usu_status<>'I'";
		$not_sqlQuery = mysql_query($sql);
		while($not_rs = mysql_fetch_object($not_sqlQuery)){
			$email->addTo($not_rs->usu_email);
		}
		
		$message = new Notification("<b>CLID: #".$cli_id."<br/>Cliente cadastrado com sucesso</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);			
		$message->setCliente($cli_id);			
		$email->setMessage($message->create());
		$email->send();		

}else{	
	if($_GET["obrg_cli_npass"] == "000000"){
		$sql = "SELECT usu_pass FROM usuario WHERE usu_id=".$_GET["cli_id"];
		$sqlQuery = mysql_query($sql);
		$rs = mysql_fetch_object($sqlQuery);
		$pass = $rs->usu_pass;
	}else{
		$pass = $_GET["obrg_cli_npass"];
	}
	
	(isset($_GET["cli_not_info"]))? $cli_not_info = "S" : $cli_not_info = "N";
	(isset($_GET["cli_not_cad_equ"]))? $cli_not_cad_equ = "S" : $cli_not_cad_equ = "N";
	(isset($_GET["cli_not_cad_os"]))? $cli_not_cad_os = "S" : $cli_not_cad_os = "N";
	(isset($_GET["cli_not_atrib_os"]))? $cli_not_atrib_os = "S" : $cli_not_atrib_os = "N";
	
	(isset($_GET["otr_not_rel"]))? $cli_not_cad_rel = "S" : $cli_not_cad_rel = "N";
	(isset($_GET["otr_not_pend"]))?	$cli_not_cad_pend = "S" : $cli_not_cad_pend = "N";		
	
	(isset($_GET["cli_status"]))? $cli_status = "AS" : $cli_status = "A";
	
	$sql = "UPDATE usuario SET 
				usu_nome='".$_GET["obrg_cli_nome"]."',
				usu_email='".$_GET["cli_email"]."',
				usu_end_pais='".$_GET["cli_end_pais"]."',
				usu_end_estado='".$_GET["cli_end_estado"]."',
				usu_end_cidade='".$_GET["cli_end_cidade"]."',
				usu_end_logradouro='".$_GET["cli_end_logradouro"]."',
				usu_end_complemento='".$_GET["cli_end_complemento"]."',
				usu_end_bairro='".$_GET["cli_end_bairro"]."',
				usu_end_cep='".$_GET["cli_end_cep"]."',				
				usu_tel='".$_GET["cli_telefone"]."',
				usu_alter_usu_id='".$_GET["usu_id"]."',
				usu_dt_alter='".(time()-(date('I')*3600))."',
				usu_login='".$_GET["obrg_cli_user"]."',
				usu_pass='".$pass."',
				usu_status='".$cli_status."',
				usu_obs='".$_GET["usu_obs"]."'
			WHERE usu_id=".$_GET["cli_id"];			
	$sqlQuery = mysql_query($sql) or die($erro = "ercli");
	
	$sql = "UPDATE cliente SET
				cli_cp='".$_GET["cli_cp"]."',
				cli_inscricao='".$_GET["cli_inscricao"]."',
				cli_not_info='".$cli_not_info."',
				cli_not_cad_equ='".$cli_not_cad_equ."',
				cli_not_cad_os='".$cli_not_cad_os."',
				cli_not_atrib_os='".$cli_not_atrib_os."',
				cli_not_cad_rel='".$cli_not_cad_rel."',
				cli_not_cad_pend='".$cli_not_cad_pend."'				
			WHERE cli_usu_id=".$_GET["cli_id"];
			
	$sqlQuery2 = mysql_query($sql) or die(mysql_error());	
	
	
	if($cli_not_info == "S"){
			
		$email = new Email($config["emp_email"]);
		$email->addTo($_GET["cli_email"]);
		$email->setSubject("Cadastro alterado com sucesso");

		$message = new Notification("<b>Alteramos algumas informações do seu cadastro. <br/>Abaixo estão as informações relativas a sua conta.</b>");
		$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
		$message->setColors($colors);			
		$message->setCliente($_GET["cli_id"]);
		$message->setClienteAdvinfo(true);
		$email->setMessage($message->create());
		$email->send();
		
	}
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("CLID: #".$_GET["cli_id"]." cadastrado do cliente alterado com sucesso");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_cli='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>CLID: #".$_GET["cli_id"]."<br/>Cadastrado do cliente alterado com sucesso</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);			
	$message->setCliente($_GET["cli_id"]);			
	$email->setMessage($message->create());
	$email->send();
	
}	
?>
<?php echo $error?>
<?php mysql_close()?>