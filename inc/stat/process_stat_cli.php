<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
require("../../sys/_dbconn.php");
require("../../css/_colors.php");

if($_GET["dti"] != ""){
	$dt = split("/",$_GET["dti"]);	
	$dti = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
	$dti_str = " AND mov_sos_dt_saida>='".$dti."'";
}else{
	$dti_str = "";
}

if($_GET["dtf"] != ""){
	$dt = split("/",$_GET["dtf"]);	
	$dtf = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
	$dtf_str = " AND mov_sos_dt_saida<='".$dtf."'";
}else{
	$dtf_str =	"";
}

$totalvc = 0;

$orderType[0][0] = "usu_nome";
$orderType[1][0] = "usu_email";
$orderType[2][0] = "usu_end_estado";
$orderType[3][0] = "usu_id";
$orderType[4][0] = "cli_cp";
$orderType[5][0] = "usu_tel";
$orderType[6][0] = "usu_end_cidade";
$orderType[7][0] = "usu_end_pais";
$orderType[8][0] = "usu_end_logradouro";

$sql = "SELECT usu_id FROM usuario
		JOIN cliente ON usu_id=cli_usu_id";


if($_GET["cmd"] == "srch"){

	$fields = array_keys($_GET);
	$values = array_values($_GET);	
	for($i = 3; $i < (count($_GET)-1); $i++){		
		if($_GET[$fields[$i]] !=""){			
			$sql .= " WHERE ";
			break;
		}
	}
	
	$isFirst = true;
	for($i = 3; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "rc"){			
			if($isFirst){				
				$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				$isFirst = false;
			}else{
				$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";				
			}		
		}
	}

}


$cli_sqlQuery = mysql_query($sql);



while($rs = mysql_fetch_object($cli_sqlQuery)){
	
	$sql = "SELECT os_id FROM os WHERE os_cli_id=".$rs->usu_id;
	$os_sqlQuery = mysql_query($sql);


	while($os_rs = mysql_fetch_object($os_sqlQuery)){
	
		$sql = "SELECT mov_sos_prod_id, mov_sos_vvalor,mov_sos_quant_saida FROM mov_saida_os 
				WHERE mov_sos_os_id='".$os_rs->os_id."'";
		$sql .= $dti_str;
		$sql .= $dtf_str;
		
		$movsos_sqlQuery = mysql_query($sql);
		
		while($movsos_rs = mysql_fetch_object($movsos_sqlQuery)){
		
			$vcusto = 0;
			
			$sql = "SELECT prod_cvalor FROM produto WHERE prod_id=".$movsos_rs->mov_sos_prod_id;
			$sqlQuery = mysql_query($sql);
			$prod_rs = mysql_fetch_object($sqlQuery);
			
			if($prod_rs->prod_cvalor == 0 || $prod_rs->prod_cvalor == ""){
				$sql = "SELECT mov_entr_vcusto FROM mov_entrada WHERE mov_entr_prod_id=".$movsos_rs->mov_sos_prod_id." 
					AND  mov_entr_id = (SELECT MAX(mov_entr_id) FROM mov_entrada WHERE mov_entr_prod_id=".$movsos_rs->mov_sos_prod_id.")
					AND mov_entr_nf_dt_emissao = (SELECT MAX(mov_entr_nf_dt_emissao)FROM mov_entrada WHERE mov_entr_prod_id=".$movsos_rs->mov_sos_prod_id.")";
				$sqlQuery = mysql_query($sql);
				$moventr_rs = mysql_fetch_object($sqlQuery);
				
				$vcusto = $moventr_rs->mov_entr_vcusto;
				
			}else{				
				$vcusto = $prod_rs->prod_cvalor;
			}
			
			$totalvc += $vcusto*$movsos_rs->mov_sos_quant_saida;
			
		}
	}
}
echo number_format($totalvc,2,",",".");
?>