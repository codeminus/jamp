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

$orderType[0][0] = "usu_id";
$orderType[1][0] = "usu_nome";
$orderType[2][0] = "usu_email";
$orderType[3][0] = "usu_dt_criacao";
$orderType[4][0] = "usu_dt_alter";
$orderType[5][0] = "usu_dt_login";

$orderType[6][0] = "usu_cad_user";
$orderType[7][0] = "usu_cad_prod";
$orderType[8][0] = "usu_cad_cli";
$orderType[9][0] = "usu_cad_equ";
$orderType[10][0] = "usu_cad_os";

$orderType[11][0] = "usu_view_user";
$orderType[12][0] = "usu_view_cli";
$orderType[13][0] = "usu_view_doc";
$orderType[14][0] = "usu_view_os";

$orderType[15][0] = "usu_aprov_os";
$orderType[16][0] = "usu_atrib_os";
$orderType[17][0] = "usu_alter_info";
$orderType[18][0] = "usu_block";
$orderType[19][0] = "usu_del_os";


$sql = "SELECT * FROM usuario WHERE usu_tipo='T' AND usu_id<>1";


if($_GET["cmd"] == "srch"){	
	
	$fields = array_keys($_GET);
	$values = array_values($_GET);
	
	$isFirst = true;
	for($i = 7; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "rc"){			
			if($isFirst){				
				$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
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
$html .= '<table width="120%"  align="center" style="font-family: Arial;font-size: 10pt">
			<tr>
				<td colspan="2" align="right" style="margin-bottom: 10px"><img src="'.$config["emp_site"]."/img/".$config["emp_logo"].'"></td>
			</tr>
			<tr>
				<td>
					Relat&oacute;rio de usu&aacute;rios<br/>
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
			<th>USID</th>
			<th align="left">Usu&aacute;rio</th>
			<th align="left">E-mail</th>
			<th>Telefone</th>
			<th>&Uacute;ltimo acesso</th>
		  </tr>';

$isColor = false;
while($rs = mysql_fetch_object($sqlQuery)){
	($isColor)? $color = $colors[2]: $color = $colors[1];
	($rs->usu_dt_login != "")? $dt_login = date("H:i d-m-y",$rs->usu_dt_login): $dt_login = "";
		
	$html .= '<tr bgcolor="'.$color.'">
				<td align="center" style="font-size: 7pt">'.$rs->usu_id.'</td>				
				<td>'.$rs->usu_nome.'</td>
				<td>'.$rs->usu_email.'</td>
				<td align="center">'.$rs->usu_tel.'</td>
				<td align="center">'.$dt_login.'</td>
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