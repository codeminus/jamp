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
						
$orderType[0][0] = "mov_sos_os_id";
$orderType[1][0] = "mov_sos_prod_id";
$orderType[2][0] = "classprod_nome";
$orderType[3][0] = "prod_nome";
$orderType[4][0] = "prod_fabricante";
$orderType[5][0] = "prod_modelo";
$orderType[6][0] = "mov_sos_vvalor";
$orderType[7][0] = "mov_sos_quant_saida";
$orderType[8][0] = "mov_sos_dt_saida";
$orderType[9][0] = "mov_sos_id";

$sql = "SELECT
			mov_sos_id,
			mov_sos_prod_id,
			classprod_nome,
			prod_nome,
			prod_fabricante,
			prod_modelo,
			prod_unid_medida,
			mov_sos_os_id,
			mov_sos_vvalor,
			mov_sos_quant_saida,
			mov_sos_dt_saida
		FROM produto
		JOIN classprod ON classprod_id=prod_classprod_id
		JOIN mov_saida_os ON mov_sos_prod_id=prod_id";


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
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg"){
			if($isFirst){
				if($fields[$i] == "dti"){
					
					if($values[$i] != ""){
						$dt = split("/",$values[$i]);	
						$dti = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
						$dti_str = " mov_sos_dt_saida>='".$dti."' ";
					}else{
						$dti_str = "";
					}					
					
					
					$sql .= $dti_str;
					
				}elseif($fields[$i] == "dtf"){
					
					if($values[$i] != ""){
						$dt = split("/",$values[$i]);	
						$dtf = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
						$dtf_str = " mov_sos_dt_saida<='".$dtf."' ";
					}else{
						$dtf_str =	"";
					}
					
					$sql .= $dtf_str;
					
				}else{
					
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "dti"){
					if($values[$i] != ""){
						$dt = split("/",$values[$i]);	
						$dti = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
						$dti_str = " AND mov_sos_dt_saida>='".$dti."' ";
					}else{
						$dti_str = "";
					}					
					
					$sql .= $dti_str;
					
				}elseif($fields[$i] == "dtf"){
					
					if($values[$i] != ""){
						$dt = split("/",$values[$i]);	
						$dtf = mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]);
						$dtf_str = " AND mov_sos_dt_saida<='".$dtf."' ";
					}else{
						$dtf_str =	"";
					}
					
					$sql .= $dtf_str;
					
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}			
		}
	}

}


$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_GET["ord"];
$sqlQuery = mysql_query($sql);
$num_rows = mysql_num_rows($sqlQuery);


$html = '<body style="font-family: Arial; ">';
$html .= '<table width="120%" align="center" style="font-family: Arial;font-size: 10pt">			
			<tr>
				<td colspan="2" align="right" style="margin-bottom: 10px"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
			</tr>
			<tr>
				<td>
					Relat&oacute;rio de movimenta&ccedil;&otilde;es de sa&iacute;da por ordem de servi&ccedil;o<br/>
					Gerado por: '.$_GET["tn"].'
				</td>
				<td align="right" valign="top">'.date("H:i:s d/m/Y",(time()-(date('I')*3600))).'</td>
			</tr>
		  </table><br/><br/>';
$html .= '<table width="120%" style="font-family: Arial;font-size: 8pt;" align="center">';
$html .= '<tr><td colspan="5">'.$num_rows.' registros nesta pesquisa</td></tr>';
$html .= '</table>';
$html .= '<table width="120%" cellspacing="0" cellpadding="4" style="font-family: Arial;font-size: 7pt; border: solid 1px" align="center">';
$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<th>Cod. Mov.</th>
			<th>OSID</th>
			<th>Cod. Produto</th>
			<th align="left">Classifica&ccedil;&atilde;o - Produto</th>
			<th>Dt. Sa&iacute;da</th>			
			<th>Quant. Sa&iacute;da</th>
			<th>Custo Unid.(R$)</th>
			<th>Custo Total(R$)</th>
		  </tr>';

$isColor = false;
while($rs = mysql_fetch_object($sqlQuery)){
	($isColor)? $color = $colors[2]: $color = $colors[1];
	$html .= '<tr bgcolor="'.$color.'">
				<td align="center">'.$rs->mov_sos_id.'</td>
				<td align="center">'.$rs->mov_sos_os_id.'</td>
				<td align="center">'.$rs->mov_sos_prod_id.'</td>
				<td>'.$rs->classprod_nome.' - '.$rs->prod_nome.'</td>				
				<td align="center" rowspan="2">'.date("d-m-y",$rs->mov_sos_dt_saida).'</td>				
				<td align="center" rowspan="2">'.$rs->mov_sos_quant_saida." ".$rs->prod_unid_medida.'</td>
				<td align="center" rowspan="2">'.number_format($rs->mov_sos_vvalor,"2",",",".").'</td>
				<td align="center" rowspan="2">'.number_format($rs->mov_sos_vvalor*$rs->mov_sos_quant_saida,"2",",",".").'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
				<td><b>Modelo: </b>'.$rs->prod_modelo.' <b>Fabricante: </b>'.$rs->prod_fabricante.'</td>
			  </tr>';
	
	$isColor = !$isColor;
}

$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<td colspan="8" align="center">'.$config["emp_site"].'</td>
		  </tr>		  
		  </table>		  
		  </body>';

$htmltodoc= new HtmlToDoc();
$htmltodoc->setDir("../../doc/");
$htmltodoc->createDoc($html,$_GET["fn"]);
?>