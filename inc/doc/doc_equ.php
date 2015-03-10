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

$orderType[0][0] = "equ_nome";
$orderType[1][0] = "usu_nome";
$orderType[2][0] = "equ_modelo";
$orderType[3][0] = "equ_num_serie";
$orderType[4][0] = "equ_num_patrimonio";
$orderType[5][0] = "equ_id";
$orderType[6][0] = "equ_dt_cad";
$orderType[7][0] = "equ_dt_alter";
$orderType[8][0] = "equ_fabricante";


if($_GET["tt"] == "C"){
	$limitation = " AND equ_cli_id=".$_GET["tid"];
	$comp[0] = "Cliente: ";
}else{
	$limitation = "";
	$comp[0] = "Gerado por: ";	
}

$sql = "SELECT * FROM equipamento 
		JOIN usuario ON equ_cli_id=usu_id
		JOIN cliente ON equ_cli_id=cli_usu_id";


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

$sql .= $limitation;

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
					Relat&oacute;rio de equipamentos<br/>
					'.$comp[0].$_GET["tn"].'
				</td>
				<td align="right" valign="top">'.date("H:i:s d/m/Y",(time()-(date('I')*3600))).'</td>
			</tr>
		  </table><br/><br/>';
$html .= '<table width="120%" style="font-family: Arial;font-size: 8pt;" align="center">';
$html .= '<tr><td colspan="8">'.$num_rows.' registros nesta pesquisa</td></tr>';
$html .= '</table>';
$html .= '<table width="120%" cellspacing="0" cellpadding="4" style="font-family: Arial;font-size: 8pt; border: solid 1px" align="center">';
$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<th align="left">Equipamento</th>
		  </tr>';

$isColor = false;
while($rs = mysql_fetch_object($sqlQuery)){
	
	($isColor)? $color = $colors[2]: $color = $colors[1];
	
	if($_GET["tt"] == "C"){		
		$comp[1] = "";
	}else{		
		$comp[1] = '<tr bgcolor="'.$color.'"><td><b>Cliente: </b>'.$rs->usu_nome.'</td></tr>';
	}	
	
	$html .= '<tr bgcolor="'.$color.'">				
				<td>
					<b>EQUID: </b>'.$rs->equ_id.'
					<b>Num. S&eacute;rie: </b>'.$rs->equ_num_serie.'
					<b>Num. Patrim&ocirc;nio: </b>'.$rs->equ_num_patrimonio.'					
				</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
				<td><b>Nome: </b>'.$rs->equ_nome.'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
				<td><b>Modelo: </b>'.$rs->equ_modelo.' <b>Fabricante: </b>'.$rs->equ_fabricante.'</td>
			  </tr>
			  <tr bgcolor="'.$color.'">
				<td><b>Observa&ccedil;&atilde;o: </b>'.$rs->equ_descricao.'</td>
			  </tr>'.$comp[1];
	
	$isColor = !$isColor;
}

$html .= '<tr bgcolor="'.$colors[0].'" style="color: '.$colors[1].'">
			<td colspan="4" align="center">'.$config["emp_site"].'</td>
		  </tr>		  
		  </table>		  
		  </body>';


$htmltodoc= new HtmlToDoc();
$htmltodoc->setDir("../../doc/");
$htmltodoc->createDoc($html,$_GET["fn"]);
?>