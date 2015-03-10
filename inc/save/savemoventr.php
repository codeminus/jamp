<?php	
    require("../../sys/_config.php");
    require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	require("../_lib.php");
	
	
if($_GET["cmd"] == "cadastrar"){
	
	/*$sql = "SELECT mov_entr_prod_id,mov_entr_forn_id,mov_entr_num_nf,mov_entr_quant_entrada FROM mov_entrada 
			WHERE mov_entr_prod_id='".$_GET["prod_id"]."' 
			AND mov_entr_forn_id='".$_GET["obrg_forn_id"]."' 
			AND mov_entr_num_nf='".$_GET["obrg_mov_entr_num_nf"]."' 
			AND mov_entr_quant_entrada=".$_GET["obrg_mov_entr_quant_entrada"];
			
	$sqlQuery = mysql_query($sql);
	
	if(mysql_num_rows($sqlQuery) > 0){
		echo "ermoventr";
		exit;
	}*/
	
	$dt = split("/",$_GET["obrg_mov_entr_nf_dt_emissao"]);
	
	$nf_dt_emissao = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);	
	
	$mov_entr_vcusto = str_replace(".","",$_GET["obrg_mov_entr_vcusto"]);
	$mov_entr_vcusto = str_replace(",",".",$mov_entr_vcusto);
	
	$sql = "INSERT INTO mov_entrada(
				mov_entr_prod_id,
				mov_entr_forn_id,					
				mov_entr_num_nf,
				mov_entr_nf_dt_emissao,
				mov_entr_vcusto,
				mov_entr_quant_entrada,
				mov_entr_cad_usu_id,				
				mov_entr_dt_cad
			) VALUES(
				'".$_GET["prod_id"]."',
				'".$_GET["obrg_forn_id"]."',
				'".$_GET["obrg_mov_entr_num_nf"]."',
				'".$nf_dt_emissao."',
				'".$mov_entr_vcusto."',
				'".$_GET["obrg_mov_entr_quant_entrada"]."',
				'".$_GET["usu_id"]."',
				'".(time()-(date('I')*3600))."'				
			)";
		
	$sqlQuery = mysql_query($sql);
	$mov_entr_id = mysql_insert_id();	
	
	$email = new Email($config["emp_email"]);
			
	$email->setSubject("Código: #".$mov_entr_id." Nova movimentação de entrada cadastrada");
	$sql = "SELECT usu_email FROM usuario WHERE usu_not_prod='S' AND usu_status<>'I'";
	$not_sqlQuery = mysql_query($sql);
	while($not_rs = mysql_fetch_object($not_sqlQuery)){
		$email->addTo($not_rs->usu_email);
	}
	
	$message = new Notification("<b>C&oacute;digo: #".$mov_entr_id."<br/>Nova movimenta&ccedil;&atilde;o de entrada cadastrada</b>");
	$message->setConfig($config["emp_site"],$config["emp_site"]."img/".$config["emp_logo"]);
	$message->setColors($colors);
	$message->setFornecedor($_GET["obrg_forn_id"]);
	$message->setProduto($_GET["prod_id"]);	
	$message->setMovEntr($mov_entr_id);
	$email->setMessage($message->create());
	$email->send();
	
}
?>
<?php mysql_close()?>