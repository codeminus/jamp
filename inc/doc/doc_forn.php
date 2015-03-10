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
						
$orderType[0][0] = "forn_nome";
$orderType[1][0] = "forn_email";
$orderType[2][0] = "forn_end_estado";
$orderType[3][0] = "forn_id";
$orderType[4][0] = "forn_cp";
$orderType[5][0] = "forn_dt_cad";
$orderType[6][0] = "forn_end_cidade";
$orderType[7][0] = "forn_end_pais";
$orderType[8][0] = "forn_end_logradouro";

$sql = "SELECT * FROM fornecedor";


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
					Relat&oacute;rio de fornecedores<br/>
					Gerado por: '.$_GET["tn"].'
				</td>
				<td align="right" valign="top">'.date("H:i:s d/m/Y",(time()-(date('I')*3600))).'</td>
			</tr>
		  </table><br/><br/>';
$html .= '<table width="120%" style="font-family: Arial;font-size: 8pt;" align="center">';
$html .= '<tr><td colspan="5">'.$num_rows.' registros nesta pesquisa</td></tr>';
$html .= '</table>';
$html .= '<table width="120%" cellspacing="0" cellpadding="4" style="font-family: Arial;font-size: 8pt; border: solid 1px" align="center">';
$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<th>C&oacute;digo</th>
			<th align="left">Fornecedor</th>
		  </tr>';

$isColor = false;
while($rs = mysql_fetch_object($sqlQuery)){
	($isColor)? $color = $colors[2]: $color = $colors[1];
	$html .= '<tr bgcolor="'.$color.'">
				<td align="center">'.$rs->forn_id.'</td>
				<td><b>Nome: </b>'.$rs->forn_nome.' <b>CPF/CNPJ: </b>'.$rs->forn_cp.'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td>
				<td><b>E-mail: </b>'.$rs->forn_email.' <b>Telefones: </b>'.$rs->forn_tel.'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
			  	<td>&nbsp;</td>
				<td>
					<b>Logradouro:</b> '.$rs->forn_end_logradouro.
					' <b>Bairro: </b>'.$rs->forn_end_bairro.
					' <b>CEP: </b>'.$rs->forn_end_cep.
					' <b>Cidade: </b>'.$rs->forn_end_cidade.
					' <b>Estado: </b>'.$rs->forn_end_estado.
					' <b>Pa&iacute;s: </b>'.$rs->forn_end_pais.'
				</td>
			  </tr>';
	
	$isColor = !$isColor;
}

$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<td colspan="2" align="center">'.$config["emp_site"].'</td>
		  </tr>		  
		  </table>		  
		  </body>';

$htmltodoc= new HtmlToDoc();
$htmltodoc->setDir("../../doc/");
$htmltodoc->createDoc($html,$_GET["fn"]);
?>