<?
$files = opendir("../../doc");
while(($file = readdir($files)) !== false){
	$tmp = split("_",$file);
	if($tmp[1] == $_GET["tid"]){
		unlink("../../doc/".$file);
		echo $file."<br/>";
	}
}

require("../../sys/_dbconn.php");
require("../../css/_colors.php");
require("../../sys/_config.php");
require("../_lib.php");

$orderType[0][0] = "prod_id";
$orderType[1][0] = "classprod_nome";
$orderType[2][0] = "prod_nome";
$orderType[3][0] = "prod_modelo";
$orderType[4][0] = "prod_fabricante";
$orderType[5][0] = "prod_localizacao";
$orderType[6][0] = "prod_vvalor";
$orderType[7][0] = "prod_min_quant";
$orderType[8][0] = "prod_dt_cad";




$sql = "SELECT * FROM produto 
		JOIN classprod ON prod_classprod_id=classprod_id";


if($_GET["cmd"] == "srch"){	
	
	$fields = array_keys($_GET);
	$values = array_values($_GET);	
	for($i = 7; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !=""){
			$sql .= " WHERE ";
			break;
		}
	}
	$isFirst = true;	
	for($i = 7; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg" ){			
			if($isFirst){
				if($fields[$i] == "ating_min" && $values[$i] == "A"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				}elseif($fields[$i] == "ating_min" && $values[$i] == "N"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) > prod_min_quant";
				}else{
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "ating_min" && $values[$i] == "A"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				}elseif($fields[$i] == "ating_min" && $values[$i] == "N"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) > prod_min_quant";	
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];		
		}
	}				
}


$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_GET["ord"];
$sqlQuery = mysql_query($sql);
$num_rows = mysql_num_rows($sqlQuery);

$html = '<body style="font-family: Arial; ">';
$html .= '<table width="120%"  align="center" style="font-family: Arial;font-size: 10pt">
			<tr>
				<td colspan="2" align="right" style="margin-bottom: 10px"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
			</tr>
			<tr>
				<td>
					Relat&oacute;rio de produtos<br/>
					Gerado por: '.$_GET["tn"].'
				</td>
				<td align="right" valign="top">'.date("H:i:s d/m/Y",(time()-(date('I')*3600))).'</td>
			</tr>
		  </table><br/><br/>';
$html .= '<table width="120%" style="font-family: Arial;font-size: 8pt;" align="center">';
$html .= '<tr><td colspan="8">'.$num_rows.' registros nesta pesquisa</td></tr>';
$html .= '</table>';
$html .= '<table width="120%" cellspacing="0" cellpadding="4" style="font-family: Arial;font-size: 7pt; border: solid 1px" align="center">';
$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<th>C&oacute;digo</th>
			<th align="left">Classifica&ccedil;&atilde;o - Produto</th>
			<th>Venda(R$)</th>
			<th>Quant. Dispon&iacute;vel</th>
			<th>Quant. M&iacute;nima </th>			
		  </tr>';

$isColor = false;
while($rs = mysql_fetch_object($sqlQuery)){
	
	$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=".$rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
	$quant_entrada = 0;
	while($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)){
		$quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
	}	

	
	$quant_saida = 0;
	
	$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=".$rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_sos = mysql_num_rows($mov_sqlQuery);
	
	while($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)){
		$quant_saida += $rs_mov_sos->mov_sos_quant_saida;
	}					
	
	$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=".$rs->prod_id;
	$mov_sqlQuery = mysql_query($sql);
	$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
	while($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)){
		$quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
	}
	
	$quant_disponivel = $quant_entrada - $quant_saida;
	
	($isColor)? $color = $colors[2]: $color = $colors[1];
	$html .= '<tr bgcolor="'.$color.'">
				<td align="center">'.$rs->prod_id.'</td>
				<td>'.$rs->classprod_nome.' - '.$rs->prod_nome.'</td>
                <td align="center" rowspan="3">'.number_format($rs->prod_vvalor,2,",",".").'</td>
                <td align="center" rowspan="3">'.$quant_disponivel." ".$rs->prod_unid_medida.'</td>
                <td align="center" rowspan="3">'.$rs->prod_min_quant." ".$rs->prod_unid_medida.'</td>				
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td>
				<td><b>Modelo:</b> '.$rs->prod_modelo.' <b>Fabricante: </b>'.$rs->prod_fabricante.'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td>
				<td><b>Localiza&ccedil;&atilde;o:</b> '.$rs->prod_localizacao.'</td>
			  </tr>';
	
	$isColor = !$isColor;
}

$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<td colspan="5" align="center">'.$config["emp_site"].'</td>
		  </tr>		  
		  </table>		  
		  </body>';


$htmltodoc= new HtmlToDoc();
$htmltodoc->setDir("../../doc/");
$htmltodoc->createDoc($html,$_GET["fn"]);
?>