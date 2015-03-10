<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	
if($_GET["cmd"] == "cadastrar"){

	(isset($_GET["prod_status"]))? $status = "B": $status = "D";

	$prod_cvalor = str_replace(".","",$_GET["prod_cvalor"]);
	$prod_cvalor = str_replace(",",".",$prod_cvalor);
	
	$prod_vvalor = str_replace(".","",$_GET["obrg_prod_vvalor"]);	
	$prod_vvalor = str_replace(",",".",$prod_vvalor);

	$sql = "INSERT INTO produto(
				prod_classprod_id,					
				prod_nome,
				prod_modelo,
				prod_fabricante,
				prod_localizacao,
				prod_desc,
				prod_cvalor,
				prod_vvalor,
				prod_unid_medida,
				prod_min_quant,
				prod_cad_usu_id,
				prod_dt_cad,
				prod_status
			) VALUES(
				'".$_GET["obrg_classprod"]."',
				'".$_GET["obrg_prod_nome"]."',
				'".$_GET["prod_modelo"]."',
				'".$_GET["prod_fabricante"]."',
				'".$_GET["prod_localizacao"]."',
				'".$_GET["prod_desc"]."',
				'".$prod_cvalor."',
				'".$prod_vvalor."',
				'".$_GET["obrg_prod_unid_medida"]."',
				'".$_GET["obrg_prod_min_quant"]."',
				'".$_GET["usu_id"]."',
				'".(time()-(date('I')*3600))."',
				'".$status."'
			)";
		
	$sqlQuery = mysql_query($sql);
	$prod_id = mysql_insert_id();	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$prod_id." Novo produto cadastrado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$prod_id."<br/>Novo produto cadastrado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setProduto($prod_id);
	$email->setMessage($message->create());
	$email->send();
	
}elseif($_GET["cmd"] == "al"){		
	
	//verificando status atual
	$sql = "SELECT prod_status FROM produto WHERE prod_id=".$_GET["prod_id"]." FOR UPDATE";
	$p_sqlQuery = mysql_query($sql);
	$p_rs = mysql_fetch_array($p_sqlQuery);
	
	$email = new Email($config["emp_email"]);
	
	$state = "D";
	if($p_rs["prod_status"] == "D"){
		$state = "B";
		
		$email->setSubject("Código: #".$_GET["prod_id"]." Produto bloqueado");
		$message = new Notification("<b>C&oacute;digo: #".$_GET["prod_id"]."<br/>Produto bloqueado</b>");
	}else{
		$email->setSubject("Código: #".$_GET["prod_id"]." Produto desbloqueado");
		$message = new Notification("<b>C&oacute;digo: #".$_GET["prod_id"]."<br/>Produto desbloqueado</b>");
	}
	
	//atualizando status
	$sql = "UPDATE produto SET
				prod_alter_usu_id='".$_GET["tec_id"]."',
				prod_dt_alter='".(time()-(date('I')*3600))."',
				prod_status='".$state."' 
			WHERE prod_id=".$_GET["prod_id"];
	$ap_sqlQuery = mysql_query($sql);	
	
	
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setProduto($_GET["prod_id"]);
	$email->setMessage($message->create());
	$email->send();
	
}elseif($_GET["cmd"] == "excluir"){
	$sql = "UPDATE produto SET prod_status='E' WHERE prod_id=".$_GET["prod_id"];
	$sqlQuery = mysql_query($sql);
	
	$email = new Email($config["emp_email"]);
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$email->setSubject("Código: #".$_GET["prod_id"]." Produto excluído");
	$message = new Notification("<b>C&oacute;digo: #".$_GET["prod_id"]."<br/>Produto exclu&iacute;do</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setProduto($_GET["prod_id"]);
	$email->setMessage($message->create());
	$email->send();

}else{
	
	$prod_cvalor = str_replace(".","",$_GET["prod_cvalor"]);
	$prod_cvalor = str_replace(",",".",$prod_cvalor);
	
	$prod_vvalor = str_replace(".","",$_GET["obrg_prod_vvalor"]);	
	$prod_vvalor = str_replace(",",".",$prod_vvalor);
	
	
	$sql = "UPDATE produto SET
				prod_classprod_id='".$_GET["obrg_classprod"]."',
				prod_nome='".$_GET["obrg_prod_nome"]."',
				prod_modelo='".$_GET["prod_modelo"]."',
				prod_fabricante='".$_GET["prod_fabricante"]."',
				prod_localizacao='".$_GET["prod_localizacao"]."',
				prod_desc='".$_GET["prod_desc"]."',
				prod_cvalor='".$prod_cvalor."',
				prod_vvalor='".$prod_vvalor."',
				prod_unid_medida='".$_GET["obrg_prod_unid_medida"]."',
				prod_min_quant='".$_GET["obrg_prod_min_quant"]."',				
				prod_alter_usu_id='".$_GET["usu_id"]."',
				prod_dt_alter='".(time()-(date('I')*3600))."'
			WHERE prod_id=".$_GET["prod_id"];			
	$sqlQuery = mysql_query($sql) or die(mysql_error());
	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$_GET["prod_id"]." Cadastro do produto alterado");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$_GET["prod_id"]."<br/>Cadastro do produto alterado</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setProduto($_GET["prod_id"]);
	$email->setMessage($message->create());
	$email->send();
}
?>
<?php mysql_close()?>