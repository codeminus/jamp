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
						
$orderType[0][0] = "mov_entr_prod_id";
$orderType[1][0] = "classprod_nome";
$orderType[2][0] = "prod_nome";
$orderType[3][0] = "prod_fabricante";
$orderType[4][0] = "prod_modelo";
$orderType[5][0] = "forn_nome";
$orderType[6][0] = "mov_entr_num_nf";
$orderType[7][0] = "mov_entr_nf_dt_emissao";
$orderType[8][0] = "mov_entr_vcusto";
$orderType[9][0] = "mov_entr_quant_entrada";
$orderType[10][0] = "mov_entr_quant_entrada";

$sql = "SELECT
			mov_entr_id,
			mov_entr_prod_id,
			classprod_nome,
			prod_nome,
			prod_fabricante,
			prod_modelo,
			prod_unid_medida,
			forn_nome,
			mov_entr_num_nf,
			mov_entr_nf_dt_emissao,
			mov_entr_vcusto,
			mov_entr_quant_entrada
		FROM produto
		JOIN classprod ON classprod_id=prod_classprod_id
		JOIN mov_entrada ON mov_entr_prod_id=prod_id
		JOIN fornecedor ON mov_entr_forn_id=forn_id";


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
				if($fields[$i] == "mov_entr_nf_dt_emissao"){
					
					$dt = split("/",$values[$i]);
					
					$sql .= $fields[$i]."='".mktime(0,0,0,$dt[1],$dt[0],$dt[2])."'";
				}else{
					
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "mov_entr_nf_dt_emissao"){
					$dt = split("/",$values[$i]);
					
					$sql .= " AND ".$fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
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
					Relat&oacute;rio de movimenta&ccedil;&otilde;es de entrada<br/>
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
			<th>Cod. Produto</th>
			<th align="left">Classifica&ccedil;&atilde;o - Produto</th>			
			<th>Nota Fiscal</th>
			<th>Dt. Emiss&atilde;o</th>			
			<th>Quant. Entrada</th>
			<th>Custo Unid.(R$)</th>
			<th>Custo Total(R$)</th>
		  </tr>';

$isColor = false;
while($rs = mysql_fetch_object($sqlQuery)){
	($isColor)? $color = $colors[2]: $color = $colors[1];
	$html .= '<tr bgcolor="'.$color.'">
				<td align="center">'.$rs->mov_entr_id.'</td>
				<td align="center">'.$rs->mov_entr_prod_id.'</td>
				<td>'.$rs->classprod_nome.' - '.$rs->prod_nome.'</td>
				<td align="center" rowspan="3">'.$rs->mov_entr_num_nf.'</td>
				<td align="center" rowspan="3">'.date("d-m-y",$rs->mov_entr_nf_dt_emissao).'</td>
				<td align="center" rowspan="3">'.$rs->mov_entr_quant_entrada." ".$rs->prod_unid_medida.'</td>
				<td align="center" rowspan="3">'.number_format($rs->mov_entr_vcusto,"2",",",".").'</td>
				<td align="center" rowspan="3">'.number_format($rs->mov_entr_vcusto*$rs->mov_entr_quant_entrada,"2",",",".").'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><b>Modelo: </b>'.$rs->prod_modelo.' <b>Fabricante: </b>'.$rs->prod_fabricante.'</td>				
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td>
				<td>&nbsp;</td>
			  	<td><b>Fornecedor:</b> '.$rs->forn_nome.'</td>
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