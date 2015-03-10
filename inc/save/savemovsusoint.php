<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	
if($_GET["cmd"] == "cadastrar"){	
	
	
	$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=".$_GET["prod_id"];
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
	$quant_entrada = 0;
	while($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)){
		$quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
	}	

	
	$quant_saida = 0;
	
	$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=".$_GET["prod_id"];
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_sos = mysql_num_rows($mov_sqlQuery);
	
	while($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)){
		$quant_saida += $rs_mov_sos->mov_sos_quant_saida;
	}					
	
	$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=".$_GET["prod_id"];
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
	while($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)){
		$quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
	}
	
	$quant_disponivel = $quant_entrada - $quant_saida;
	if($quant_disponivel < $_GET["obrg_mov_susoint_quant_saida"]){ 
		echo "ermovsusoint";
		exit;
	}
	
			
	$dt = split("/",$_GET["obrg_mov_susoint_dt_saida"]);
	
	$dt_saida = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);	
	
	$sql = "INSERT INTO mov_saida_usointerno(
				mov_susoint_prod_id,
				mov_susoint_solicitado_por,					
				mov_susoint_autorizado_por,
				mov_susoint_finalidade,
				mov_susoint_quant_saida,
				mov_susoint_svalor,
				mov_susoint_dt_saida,
				mov_susoint_cad_usu_id,				
				mov_susoint_dt_cad
			) VALUES(
				'".$_GET["prod_id"]."',
				'".$_GET["obrg_mov_susoint_solicitado_por"]."',
				'".$_GET["obrg_mov_susoint_autorizado_por"]."',
				'".$_GET["mov_susoint_finalidade"]."',
				'".$_GET["obrg_mov_susoint_quant_saida"]."',
				'".$_GET["vcusto"]."',
				'".$dt_saida."',
				'".$_GET["usu_id"]."',
				'".(time()-(date('I')*3600))."'				
			)";
	
	$sqlQuery = mysql_query($sql) or die(mysql_error());
	$mov_susoint_id = mysql_insert_id();	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$mov_susoint_id." Nova movimentação de saída por uso interno cadastrada");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$mov_susoint_id."<br/>Nova movimenta&ccedil;&atilde;o de sa&iacute;da por uso interno cadastrada</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);	
	$message->setProduto($_GET["prod_id"]);	
	$message->setMovSusoint($mov_susoint_id);
	$email->setMessage($message->create());
	$email->send();
	
}
?>
<?php mysql_close()?>